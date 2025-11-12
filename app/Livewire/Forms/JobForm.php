<?php

namespace App\Livewire\Forms;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Validation\Rule;
use Livewire\Form;

class JobForm extends Form
{
    public ?int $jobId = null;

    public string $pickup_address = '';

    public string $delivery_address = '';

    public string $recipient_name = '';

    public string $recipient_phone = '';

    public string $status = JobStatus::Assigned->value;

    public ?int $carrier_id = null;

    public function rules(): array
    {
        return [
            'pickup_address' => ['required', 'string', 'min:3'],
            'delivery_address' => ['required', 'string', 'min:3'],
            'recipient_name' => ['required', 'string', 'min:3'],
            'recipient_phone' => ['required', 'string', 'min:6'],
            'status' => ['required', Rule::in(collect(JobStatus::cases())->map->value->all())],
            'carrier_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function isEditing(): bool
    {
        return ! is_null($this->jobId);
    }

    public function init(): void
    {
        $this->reset([
            'jobId',
            'pickup_address',
            'delivery_address',
            'recipient_name',
            'recipient_phone',
            'status',
            'carrier_id',
        ]);

        $this->status = JobStatus::Assigned->value;
        $this->carrier_id = null;
    }

    public function setJob(Job $job): void
    {
        $this->jobId = $job->id;
        $this->pickup_address = $job->pickup_address;
        $this->delivery_address = $job->delivery_address;
        $this->recipient_name = $job->recipient_name;
        $this->recipient_phone = $job->recipient_phone;
        $this->status = $job->status->value;
        $this->carrier_id = $job->carrier_id;
    }

    public function save(JobService $jobService): Job
    {
        $this->validate();

        $payload = [
            'pickup_address' => $this->pickup_address,
            'delivery_address' => $this->delivery_address,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'status' => JobStatus::from($this->status),
            'carrier_id' => $this->carrier_id,
        ];

        if ($this->isEditing()) {
            $job = Job::findOrFail($this->jobId);

            return $jobService->update($job, $payload);
        }

        $job = $jobService->create($payload);
        $this->jobId = $job->id;

        return $job;
    }
}

