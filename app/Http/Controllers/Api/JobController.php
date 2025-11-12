<?php

namespace App\Http\Controllers\Api;

use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function __construct(private readonly JobService $jobService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateJob($request);

        $data['status'] = JobStatus::from($data['status']);

        $job = $this->jobService->create($data);

        return response()->json([
            'message' => __('Fuvar sikeresen létrehozva.'),
            'data' => new JobResource($job),
        ], 201);
    }

    public function update(Request $request, Job $job): JsonResponse
    {
        $data = $this->validateJob($request, updating: true);

        if (isset($data['status'])) {
            $data['status'] = JobStatus::from($data['status']);
        }

        $job = $this->jobService->update($job, $data);

        return response()->json([
            'message' => __('Fuvar sikeresen frissítve.'),
            'data' => new JobResource($job),
        ]);
    }

    public function updateStatus(Request $request, Job $job): JsonResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(array_column(JobStatus::cases(), 'value')),
            ],
        ]);

        $status = JobStatus::from($validated['status']);

        $job = $this->jobService->updateStatus($job, $status);

        return response()->json([
            'message' => __('Fuvar státusza frissítve.'),
            'data' => new JobResource($job),
        ]);
    }

    private function validateJob(Request $request, bool $updating = false): array
    {
        $rules = [
            'pickup_address' => [$updating ? 'sometimes' : 'required', 'string', 'min:3'],
            'delivery_address' => [$updating ? 'sometimes' : 'required', 'string', 'min:3'],
            'recipient_name' => [$updating ? 'sometimes' : 'required', 'string', 'min:3'],
            'recipient_phone' => [$updating ? 'sometimes' : 'required', 'string', 'min:6'],
            'status' => [$updating ? 'sometimes' : 'required', 'string', Rule::in(array_column(JobStatus::cases(), 'value'))],
            'carrier_id' => ['nullable', 'integer', 'exists:users,id'],
        ];

        return $request->validate($rules);
    }
}

