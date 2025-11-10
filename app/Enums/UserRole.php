<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Carrier = 'carrier';

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isCarrier(): bool
    {
        return $this === self::Carrier;
    }
}

