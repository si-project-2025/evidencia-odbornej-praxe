<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterCompanyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNotNull('email_verified_at');
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email je povinný.',
            'email.email' => 'Email musí byť platná emailová adresa.',
            'email.unique' => 'Tento email už je registrovaný a aktívny.',
        ];
    }
}
