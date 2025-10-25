<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'E-mail je povinný.',
            'email.email' => 'Neplatný formát e-mailu.',
            'email.exists' => 'E-mail neexistuje.',
            'token.required' => 'Platnosť tokenu vypršala.',
            'password.required' => 'Heslo je povinné.',
            'password.min' => 'Heslo musí mať aspoň :min znakov.',
            'password.confirmed' => 'Heslá sa nezhodujú.',
        ];
    }
}
