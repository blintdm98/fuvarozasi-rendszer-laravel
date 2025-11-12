<?php

namespace App\Livewire\Carrier\Jobs;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.app')]
class MyJobs extends Component
{
    use WireUiActions;

    public Collection $jobs;

    /**
     * @var array<int, array{label:string,value:string}>
     */
    public array $statusOptions = [];

    /**
     * @var array<int, string>
     */
    public array $statusSelections = [];

    protected JobService $jobService;

    public function boot(JobService $jobService): void
    {
        $this->jobService = $jobService;
    }

    public function mount(): void
    {
        if (! auth()->user()?->isCarrier()) {
            redirect()->route('home')->send();
        }

        $this->statusOptions = JobStatus::toArray();
        $this->refreshJobs();
    }

    public function refreshJobs(): void
    {
        $this->jobs = $this->jobService->listForCarrier(auth()->user());

        $this->statusSelections = $this->jobs
            ->mapWithKeys(fn (Job $job) => [$job->id => $job->status->value])
            ->toArray();
    }

    public function confirmStatus(int $jobId): void
    {
        $statusValue = $this->statusSelections[$jobId] ?? null;

        if (! $statusValue) {
            return;
        }

        $newStatus = JobStatus::from($statusValue);

        /** @var Job|null $job */
        $job = Job::query()
            ->where('id', $jobId)
            ->where('carrier_id', auth()->id())
            ->first();

        if (! $job) {
            return;
        }

        if ($job->status === $newStatus) {
            $this->notification()->info(
                title: __('Nincs változás'),
                description: __('A megadott státusz megegyezik a jelenlegivel.')
            );

            return;
        }

        if ($job->status->isTerminal()) {
            $this->notification()->warning(
                title: __('Státusz zárolva'),
                description: __('A már lezárt fuvar státusza nem módosítható.')
            );

            $this->refreshJobs();

            return;
        }

        $this->jobService->updateStatus($job, $newStatus);

        $this->notification()->success(
            title: __('Sikeres frissítés'),
            description: __('A fuvar státusza frissült.')
        );

        $this->refreshJobs();
    }

    public function render()
    {
        return view('livewire.carrier.jobs.my-jobs')
            ->with('pageTitle', __('Kiosztott fuvarjaim'));
    }
}