<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'transport_jobs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'pickup_address',
        'delivery_address',
        'recipient_name',
        'recipient_phone',
        'status',
        'carrier_id',
        'assigned_at',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => JobStatus::class,
            'assigned_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * A job may be assigned to a carrier.
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'carrier_id');
    }
}
