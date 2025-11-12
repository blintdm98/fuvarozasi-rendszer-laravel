<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Job */
class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pickup_address' => $this->pickup_address,
            'delivery_address' => $this->delivery_address,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'carrier' => $this->carrier ? [
                'id' => $this->carrier->id,
                'name' => $this->carrier->name,
            ] : null,
            'assigned_at' => optional($this->assigned_at)->toDateTimeString(),
            'completed_at' => optional($this->completed_at)->toDateTimeString(),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}

