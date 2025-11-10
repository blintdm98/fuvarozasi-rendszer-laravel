<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'carrier_id',
        'brand',
        'model',
        'license_plate',
    ];

    /**
     * A vehicle belongs to a carrier (user).
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'carrier_id');
    }
}
