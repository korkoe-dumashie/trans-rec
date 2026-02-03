<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'user_id' => new UserResource($this->user),
        //     'role_id' => new RoleResource($this->role),
        // ];

        return [
            'user'=> [
                'id'=> $this->user->id,
                'name'=> $this->user->first_name . ' ' . $this->user->last_name,
                'staff_id'=> $this->user->staff_id
            ],
            'role'=> [
                'id'=> $this->role->id,
                'name'=> $this->role->name,
                'description'=> $this->role->description
            ]
        ];
    }
}
