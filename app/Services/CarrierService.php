<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Collection;

class CarrierService
{
    public function listActiveCarriers(): Collection
    {
        return User::query()
            ->where('role', UserRole::Carrier->value)
            ->with('vehicle')
            ->orderBy('name')
            ->get();
    }

    public function ensureCarrier(User $user): User
    {
        abort_unless($user->isCarrier(), 403);

        return $user;
    }
}

