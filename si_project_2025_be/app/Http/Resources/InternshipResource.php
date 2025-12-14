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
            'year' => $this->year,
            'is_paid' => $this->is_paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,

            'company' => [
                'id' => $this->company->company_id,
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

            'student' => [
                'users_id' => $this->student->users_id,
                'email' => $this->student->email,
                'name' => $this->student->name,
                'surname' => $this->student->surname,
                'study_program' => $this->student->study_program,
                'phone_number' => $this->student->phone_number,
            ],

            'contact_person' => $this->contactPerson ? [
                'id' => $this->contactPerson->id,
                'name' => $this->contactPerson->name,
                'surname' => $this->contactPerson->surname,
                'email' => $this->contactPerson->email,
                'phone' => $this->contactPerson->phone,
            ] : [],

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
