<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InternshipResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'users_id' => $this->users_id,
            'internships_id' => $this->internships_id,
            'semester' => $this->semester,
            'hours_total' => $this->hours_total,
            'year' => $this->year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'end_at' => $this->end_at,

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
                'users_id' => $this->garant->users_id,
                'email' => $this->garant->email,
                'name' => $this->garant->name,
                'surname' => $this->garant->surname,
            ],

            'documents' => $this->documents->map(function ($document) {
                return [
                    'document_id' => $document->document_id,
                    'type' => $document->type,
                    'file_name' => $document->file_name,
                    'is_verified' => $document->is_verified,
                    'created_at' => $document->created_at,
                ];
            }),
        ];
    }
}
