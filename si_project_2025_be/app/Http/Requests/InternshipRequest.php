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
            'hours_total' => 'required|integer',
            'end_at' => 'required|date_format:Y-m-d H:i:s',
            'year' => 'required|integer',
            'users_id' => 'required|integer|exists:users,users_id',
            'company_id' => 'required|integer|exists:companies,company_id',
            'status_id' => 'required|integer|exists:status,status_id',
            'garant_id' => 'required|integer|exists:users,users_id',
        ];
    }

    public function messages(): array
    {
        return [
            'semester.required' => 'Semester je povinný.',
            'semester.enum' => 'Zvolená hodnota semestra nie je platná.',

            'hours_total.required' => 'Počet hodín je povinný.',
            'hours_total.integer' => 'Počet hodín musí byť celé číslo.',

            'end_at.required' => 'Dátum ukončenia je povinný.',
            'end_at.date_format' => 'Dátum ukončenia musí byť vo formáte RRRR-MM-DD HH:MM:SS.',

            'year.required' => 'Rok je povinný.',
            'year.integer' => 'Rok musí byť celé číslo.',

            'users_id.required' => 'Používateľ je povinný.',
            'users_id.integer' => 'ID používateľa musí byť celé číslo.',
            'users_id.exists' => 'Zvolený používateľ neexistuje.',

            'company_id.required' => 'Firma je povinná.',
            'company_id.integer' => 'ID firmy musí byť celé číslo.',
            'company_id.exists' => 'Zvolená firma neexistuje.',

            'status_id.required' => 'Status je povinný.',
            'status_id.integer' => 'ID statusu musí byť celé číslo.',
            'status_id.exists' => 'Zvolený status neexistuje.',

            'garant_id.required' => 'Garant je povinný.',
            'garant_id.integer' => 'ID garanta musí byť celé číslo.',
            'garant_id.exists' => 'Zvolený garant neexistuje.',
        ];
    }
}
