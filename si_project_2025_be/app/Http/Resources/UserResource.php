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
        // Základné údaje pre všetky roly
        $data = [
            'users_id' => $this->users_id,
            'email' => $this->email,
            'alt_email' => $this->alt_email,
            'created_at' => $this->created_at,
            'last_login' => $this->last_login,
            'role' => $this->role->name,
        ];

        // Ak je to firma
        if ($this->role->name === 'firma') {
            $data['company'] = $this->company ? [
                'company_id' => $this->company->company_id,
                'name' => $this->company->name,
                'ico' => $this->company->ico,
                'address' => $this->company->address ? [
                    'country' => $this->company->address->country,
                    'city' => $this->company->address->city,
                    'zip_code' => $this->company->address->zip_code,
                    'street' => $this->company->address->street,
                    'house_number' => $this->company->address->house_number,
                ] : null,
            ] : null;
        } else {
            $data['name'] = $this->name;
            $data['surname'] = $this->surname;
            $data['study_program'] = $this->study_program;
            $data['phone_number'] = $this->phone_number;
            $data['address'] = $this->address ? [
                'country' => $this->address->country,
                'city' => $this->address->city,
                'zip_code' => $this->address->zip_code,
                'street' => $this->address->street,
                'house_number' => $this->address->house_number,
            ] : null;
        }

        return $data;
    }
}
