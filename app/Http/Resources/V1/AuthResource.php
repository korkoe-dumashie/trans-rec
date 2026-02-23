<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
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
            // 'user_id' => $this->user_id,
            'staff_id' => $this->staff_id,
            'reset_password' => $this->reset_password,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
        ];
    }
}
