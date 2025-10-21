<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'users_id' => $this->users_id,
            'email' => $this->email,
            'alt_email' => $this->alt_email,
            'name' => $this->name,
            'surname' => $this->surname,
            'study_program' => $this->study_program,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'last_login' => $this->last_login,
            'role' => $this->role->name,
            'address' => $this->address ? [
                'country' => $this->address->country,
                'city' => $this->address->city,
                'zip_code' => $this->address->zip_code,
                'street' => $this->address->street,
                'house_number' => $this->address->house_number,
            ] : null,
        ];
    }
}
