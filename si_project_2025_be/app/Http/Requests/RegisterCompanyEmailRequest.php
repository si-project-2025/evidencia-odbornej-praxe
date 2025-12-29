<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email je povinný.',
            'email.email' => 'Email musí byť platná emailová adresa.',
            'email.unique' => 'Tento email už je registrovaný.',
        ];
    }
}
