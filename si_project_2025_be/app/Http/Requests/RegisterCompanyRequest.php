<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'company_name' => 'required|string|max:255',
            'ico' => 'required|string|max:20|unique:companies,ico',
            'alt_email' => 'nullable|email',
            'address.country' => 'required|string|max:100',
            'address.city' => 'required|string|max:100',
            'address.zip_code' => 'required|string|max:20',
            'address.street' => 'required|string|max:255',
            'address.house_number' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email je povinný.',
            'email.email' => 'Email musí byť platná emailová adresa.',
            'email.unique' => 'Tento email už je registrovaný.',
            'company_name.required' => 'Názov firmy je povinný.',
            'ico.required' => 'IČO je povinné.',
            'ico.unique' => 'Firma s týmto IČO je už zaregistrovaná.',
            'address.country.required' => 'Krajina je povinná.',
            'address.city.required' => 'Mesto je povinné.',
            'address.zip_code.required' => 'PSČ je povinné.',
            'address.street.required' => 'Ulica je povinná.',
            'address.house_number.required' => 'Číslo domu je povinné.',
        ];
    }
}
