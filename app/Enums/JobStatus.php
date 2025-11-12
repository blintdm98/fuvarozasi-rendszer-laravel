<?php

namespace App\Enums;

use App\Traits\EnumFunctions;

enum JobStatus: string
{
    use EnumFunctions;

    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Failed = 'failed';

    /**
     * Determine if the status represents a finished job.
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::Completed, self::Failed], true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Assigned => __('Kiosztva'),
            self::InProgress => __('Folyamatban'),
            self::Completed => __('Elvégezve'),
            self::Failed => __('Sikertelen'),
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Completed => 'emerald',
            self::Failed => 'rose',
            self::InProgress => 'indigo',
            self::Assigned => 'primary',
        };
    }
}

