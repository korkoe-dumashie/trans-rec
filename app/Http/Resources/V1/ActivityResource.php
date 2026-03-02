<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
        'user' => [
            'id' => $this->user->id,
            'name' => $this->user->first_name . ' ' . $this->user->last_name,
            'staff_id' => $this->user->staff_id,
            'role' => $this->user->role ? $this->user->role->name : null,
        ],
        'action' => $this->action,
        'resource_type' => $this->resource_type,
        'metadata' => $this->metadata,
        'created_at' => $this->created_at,
    ];
    }
}
