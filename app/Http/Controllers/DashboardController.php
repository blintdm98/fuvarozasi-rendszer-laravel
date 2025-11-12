<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $query = Job::query();

        if ($user?->isCarrier()) {
            $query->where('carrier_id', $user->id);
        }

        $activeJobs = (clone $query)
            ->whereIn('status', [JobStatus::Assigned->value, JobStatus::InProgress->value])
            ->count();

        $completedJobs = (clone $query)
            ->where('status', JobStatus::Completed->value)
            ->count();

        $carrierCount = $user?->isCarrier()
            ? null
            : User::query()->where('role', UserRole::Carrier->value)->count();

        return view('dashboard', [
            'activeJobs' => $activeJobs,
            'completedJobs' => $completedJobs,
            'carrierCount' => $carrierCount,
        ]);
    }
}

