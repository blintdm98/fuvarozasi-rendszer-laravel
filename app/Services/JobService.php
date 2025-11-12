<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\JobAlert;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class JobService
{
    public function listForAdmin(?JobStatus $status = null): Collection
    {
        $query = Job::with('carrier')
            ->latest();

        if ($status) {
            $query->where('status', $status->value);
        }

        return $query->get();
    }

    public function listForCarrier(User $carrier): Collection
    {
        return Job::query()
            ->with('carrier')
            ->where('carrier_id', $carrier->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(array $attributes): Job
    {
        /** @var Job $job */
        $job = Job::create($attributes);

        $job->forceFill([
            'assigned_at' => $job->carrier_id ? $this->resolveAssignedAt($job->status) : null,
            'completed_at' => $job->status->isTerminal() ? now() : null,
        ]);

        $job->save();

        return $job->refresh();
    }

    public function update(Job $job, array $attributes): Job
    {
        $previousStatus = $job->status;

        $job->fill($attributes);

        if ($job->isDirty('status')) {
            $job->assigned_at = $job->status === JobStatus::Assigned ? now() : $job->assigned_at;
            $job->completed_at = $job->status->isTerminal() ? now() : null;
        }

        $job->save();

        $job->refresh();

        if ($previousStatus !== JobStatus::Failed && $job->status === JobStatus::Failed) {
            $this->createFailureAlert($job);
        }

        return $job;
    }

    public function delete(Job $job): void
    {
        $job->delete();
    }

    public function assignToCarrier(Job $job, User $carrier): Job
    {
        return $this->update($job, [
            'carrier_id' => $carrier->id,
            'status' => JobStatus::Assigned,
            'assigned_at' => now(),
        ]);
    }

    public function updateStatus(Job $job, JobStatus $status): Job
    {
        return $this->update($job, [
            'status' => $status,
        ]);
    }

    protected function resolveAssignedAt(JobStatus $status): ?\Illuminate\Support\Carbon
    {
        return in_array($status, [JobStatus::Assigned, JobStatus::InProgress, JobStatus::Completed], true)
            ? now()
            : null;
    }

    protected function createFailureAlert(Job $job): void
    {
        JobAlert::create([
            'job_id' => $job->id,
            'message' => __('Sikertelen fuvar: :pickup → :delivery', [
                'pickup' => $job->pickup_address,
                'delivery' => $job->delivery_address,
            ]),
        ]);
    }
}



