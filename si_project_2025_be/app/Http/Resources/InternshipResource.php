<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternshipResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'internships_id' => $this->internships_id,
            'semester' => $this->semester,
            'hours_total' => $this->hours_total,
            'year' => $this->year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'end_at' => $this->end_at,
            'users_id' => $this->users_id,

            'company' => [
                'company_id' => $this->company->company_id,
                'name' => $this->company->name,
                'ico' => $this->company->ico,
                'address' => [
                    'country' => $this->company->address->country,
                    'city' => $this->company->address->city,
                    'zip_code' => $this->company->address->zip_code,
                    'street' => $this->company->address->street,
                    'house_number' => $this->company->address->house_number,
                ],
            ],

            'status' => $this->status->type,

            'garant' => [
                'email' => $this->garant->email,
                'name' => $this->garant->name,
                'surname' => $this->garant->surname,
            ],
        ];
    }
}
