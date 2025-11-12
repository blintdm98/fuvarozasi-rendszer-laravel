<?php

namespace App\Livewire\Admin\Jobs;

use App\Enums\JobStatus;
use App\Livewire\Forms\JobForm;
use App\Models\Job;
use App\Models\JobAlert;
use App\Models\User;
use App\Services\CarrierService;
use App\Services\JobService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.app')]
class JobList extends Component
{
    use WireUiActions;

    public JobForm $form;

    public Collection $jobs;

    public array $carriers = [];

    public array $statusOptions = [];

    public array $alerts = [];

    public bool $showAlertModal = false;

    public bool $jobModal = false;

    public bool $assignModal = false;

    public bool $deleteModal = false;

    public ?int $assignJobId = null;

    public ?int $assignCarrierId = null;

    public ?array $assignJobPreview = null;

    public ?int $deleteJobId = null;

    public ?string $deleteJobLabel = null;

    public ?string $statusFilter = null;

    protected JobService $jobService;

    protected CarrierService $carrierService;

    public function boot(JobService $jobService, CarrierService $carrierService): void
    {
        $this->jobService = $jobService;
        $this->carrierService = $carrierService;
    }

    public function mount(): void
    {
        if (! auth()->user()?->isAdmin()) {
            redirect()->route('home')->send();
        }

        $this->statusOptions = JobStatus::toArray();

        $this->refreshJobs();
        $this->refreshCarriers();
        $this->form->init();
        $this->loadAlerts();
    }

    protected function loadJobs(): Collection
    {
        return $this->jobService->listForAdmin(
            $this->statusFilter ? JobStatus::from($this->statusFilter) : null
        );
    }

    public function refreshJobs(): void
    {
        $this->jobs = $this->loadJobs();
    }

    public function updatedStatusFilter(): void
    {
        $this->jobs = $this->loadJobs();
    }

    public function refreshCarriers(): void
    {
        $this->carriers = $this->carrierService->listActiveCarriers()
            ->map(fn (User $carrier) => [
                'label' => $carrier->name . ($carrier->vehicle ? ' · ' . $carrier->vehicle->license_plate : ''),
                'value' => $carrier->id,
            ])->values()->toArray();
    }

    public function openNewJob(): void
    {
        $this->form->init();
        $this->jobModal = true;
    }

    public function openEditJob(int $jobId): void
    {
        $job = Job::with('carrier')->findOrFail($jobId);

        $this->form->setJob($job);
        $this->jobModal = true;
    }

    public function saveJob(): void
    {
        $creating = ! $this->form->isEditing();

        $this->form->save($this->jobService);

        $this->notification()->success(
            title: __('Sikeres mentés'),
            description: $creating
                ? __('Új fuvarfeladat jött létre.')
                : __('A fuvarfeladat frissítve lett.')
        );

        $this->jobModal = false;
        $this->form->init();
        $this->refreshJobs();
    }

    public function openAssignJob(int $jobId): void
    {
        $this->refreshCarriers();

        $job = Job::with('carrier')->findOrFail($jobId);

        $this->assignJobId = $job->id;
        $this->assignCarrierId = $job->carrier_id;
        $this->assignJobPreview = [
            'pickup' => $job->pickup_address,
            'delivery' => $job->delivery_address,
            'recipient' => "{$job->recipient_name} · {$job->recipient_phone}",
            'carrier' => $job->carrier?->name,
        ];

        $this->assignModal = true;
    }

    public function assignJob(): void
    {
        $this->validate([
            'assignJobId' => ['required', 'integer', 'exists:transport_jobs,id'],
            'assignCarrierId' => ['required', 'integer', 'exists:users,id'],
        ]);

        $job = Job::findOrFail($this->assignJobId);
        /** @var User $carrier */
        $carrier = User::findOrFail($this->assignCarrierId);

        $this->carrierService->ensureCarrier($carrier);

        $this->jobService->assignToCarrier($job, $carrier);

        $this->notification()->success(
            title: __('Hozzárendelés mentve'),
            description: __('A feladat fuvarozóhoz rendelése sikeres volt.')
        );

        $this->assignModal = false;
        $this->assignJobId = null;
        $this->assignCarrierId = null;
        $this->assignJobPreview = null;
        $this->refreshJobs();
    }

    public function confirmDelete(int $jobId): void
    {
        $job = Job::findOrFail($jobId);

        $this->deleteJobId = $job->id;
        $this->deleteJobLabel = "{$job->pickup_address} → {$job->delivery_address}";
        $this->deleteModal = true;
    }

    public function deleteJob(): void
    {
        $this->validate([
            'deleteJobId' => ['required', 'integer', 'exists:transport_jobs,id'],
        ]);

        $job = Job::findOrFail($this->deleteJobId);

        $this->jobService->delete($job);

        $this->notification()->success(
            title: __('Feladat törölve'),
            description: __('A fuvarfeladat eltávolításra került.')
        );

        $this->deleteModal = false;
        $this->deleteJobId = null;
        $this->deleteJobLabel = null;
        $this->refreshJobs();
    }

    public function render()
    {
        return view('livewire.admin.jobs.job-list', [
            'carriers' => $this->carriers,
            'statusOptions' => $this->statusOptions,
            'alerts' => $this->alerts,
            'showAlertModal' => $this->showAlertModal,
        ])->with('pageTitle', __('Fuvarfeladatok'));
    }

    protected function loadAlerts(): void
    {
        $alerts = JobAlert::query()
            ->with('job')
            ->whereNull('read_at')
            ->latest()
            ->get();

        $this->alerts = $alerts->map(function (JobAlert $alert) {
            return [
                'id' => $alert->id,
                'message' => $alert->message,
                'created_at' => optional($alert->created_at)->format('Y.m.d H:i'),
                'job' => $alert->job ? [
                    'pickup' => $alert->job->pickup_address,
                    'delivery' => $alert->job->delivery_address,
                    'status' => $alert->job->status->label(),
                ] : null,
            ];
        })->toArray();

        $this->showAlertModal = ! empty($this->alerts);
    }

    public function acknowledgeAlerts(): void
    {
        if (! empty($this->alerts)) {
            $ids = collect($this->alerts)->pluck('id');

            JobAlert::whereIn('id', $ids)->update(['read_at' => now()]);
        }

        $this->alerts = [];
        $this->showAlertModal = false;
    }
}
