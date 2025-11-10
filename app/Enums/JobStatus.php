<?php

namespace App\Enums;

enum JobStatus: string
{
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
}

