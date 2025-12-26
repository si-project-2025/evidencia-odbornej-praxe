<?php

namespace App\Http\Requests;

use App\Enums\SemesterEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InternshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'semester' => ['required', Rule::enum(SemesterEnum::class)],
            'start_at' => 'required|date_format:Y-m-d',
            'end_at' => 'nullable|date_format:Y-m-d',
            'year' => 'required|integer',
            'users_id' => 'required|integer|exists:users,users_id',
            'company_id' => 'required|integer|exists:companies,company_id',
            'contact_person_id' => 'required|integer|exists:contact_persons,id',
            'garant_id' => 'required|integer|exists:users,users_id',
            'is_paid' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'semester.required' => 'Semester je povinný.',
            'semester.enum' => 'Zvolená hodnota semestra nie je platná.',

            'start_at.required' => 'Dátum začiatku praxe je povinný.',
            'start_at.date_format' => 'Dátum začiatku musí byť vo formáte RRRR-MM-DD.',

            'end_at.date_format' => 'Dátum ukončenia musí byť vo formáte RRRR-MM-DD',

            'year.required' => 'Rok je povinný.',
            'year.integer' => 'Rok musí byť celé číslo.',

            'users_id.required' => 'Používateľ je povinný.',
            'users_id.integer' => 'ID používateľa musí byť celé číslo.',
            'users_id.exists' => 'Zvolený používateľ neexistuje.',

            'company_id.required' => 'Firma je povinná.',
            'company_id.integer' => 'ID firmy musí byť celé číslo.',
            'company_id.exists' => 'Zvolená firma neexistuje.',

            'status.required' => 'Status je povinný.',
            'status.string' => 'Status musí byť text.',
            'status.exists' => 'Zvolený status neexistuje.',

            'garant_id.required' => 'Garant je povinný.',
            'garant_id.integer' => 'ID garanta musí byť celé číslo.',
            'garant_id.exists' => 'Zvolený garant neexistuje.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->end_at) {
                return;
            }

            $start = strtotime($this->start_at);
            $end = strtotime($this->end_at);

            $diffDays = ($end - $start) / (60 * 60 * 24);

            if ($diffDays < 30) {
                $validator->errors()->add(
                    'end_at',
                    'Dátum ukončenia musí byť aspoň 30 dní po začiatku praxe.'
                );
            }
        });
    }
}
