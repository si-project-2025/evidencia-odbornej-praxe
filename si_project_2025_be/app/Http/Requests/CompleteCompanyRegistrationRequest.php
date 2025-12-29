<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteCompanyRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,company_id',
            'company_name' => 'required_without:company_id|string|max:255',
            'ico' => 'required_without:company_id|string|max:20|unique:companies,ico',
            'address.country' => 'required_without:company_id|string|max:100',
            'address.city' => 'required_without:company_id|string|max:100',
            'address.zip_code' => 'required_without:company_id|string|max:20',
            'address.street' => 'required_without:company_id|string|max:255',
            'address.house_number' => 'required_without:company_id|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.exists' => 'Vybraná firma neexistuje.',
            'company_name.required_without' => 'Názov firmy je povinný.',
            'ico.required_without' => 'IČO je povinné.',
            'ico.unique' => 'Firma s týmto IČO je už zaregistrovaná.',
            'address.country.required_without' => 'Krajina je povinná.',
            'address.city.required_without' => 'Mesto je povinné.',
            'address.zip_code.required_without' => 'PSČ je povinné.',
            'address.street.required_without' => 'Ulica je povinná.',
            'address.house_number.required_without' => 'Číslo domu je povinné.',
        ];
    }
}
