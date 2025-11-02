<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',

            //validacia ukf emailu
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@(student\.)?ukf\.sk$/i'
            ],

            'role' => 'required|string|exists:roles,name',

            'alt_email' => 'nullable|string|email|max:191|unique:users,alt_email',
            'study_program' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',

            'address.street' => 'nullable|string|required_if:role,student',
            'address.house_number' => 'nullable|string|required_if:role,student',
            'address.city' => 'nullable|string|required_if:role,student',
            'address.zip_code' => 'nullable|string|required_if:role,student',
            'address.country' => 'nullable|string|required_if:role,student',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Meno je povinné.',
            'name.string' => 'Meno musí byť text.',
            'name.max' => 'Meno môže mať maximálne :max znakov.',

            'surname.required' => 'Priezvisko je povinné.',
            'surname.string' => 'Priezvisko musí byť text.',
            'surname.max' => 'Priezvisko môže mať maximálne :max znakov.',

            'email.required' => 'Email je povinný.',
            'email.string' => 'Email musí byť text.',
            'email.email' => 'Email musí byť platný.',
            'email.max' => 'Email môže mať maximálne :max znakov.',
            'email.unique' => 'Tento email už je registrovaný.',
            'email.regex' => 'Registrácia je povolená len pre e-maily z domény ukf.sk alebo student.ukf.sk.',

            'role.required' => 'Rola je povinná.',
            'role.string' => 'Rola musí byť text.',
            'role.exists' => 'Zvolená rola nie je platná.',

            'alt_email.string' => 'Alternatívny email musí byť text.',
            'alt_email.email' => 'Alternatívny email musí byť platný.',
            'alt_email.max' => 'Alternatívny email môže mať maximálne :max znakov.',
            'alt_email.unique' => 'Tento alternatívny email už je registrovaný.',

            'study_program.string' => 'Študijný program musí byť text.',
            'study_program.max' => 'Študijný program môže mať maximálne :max znakov.',

            'phone_number.string' => 'Telefónne číslo musí byť text.',
            'phone_number.max' => 'Telefónne číslo môže mať maximálne :max znakov.',

            'address.street.required_if' => 'Ulica je povinná pre študentov.',
            'address.street.string' => 'Ulica musí byť text.',

            'address.house_number.required_if' => 'Číslo domu je povinné pre študentov.',
            'address.house_number.string' => 'Číslo domu musí byť text.',

            'address.city.required_if' => 'Mesto je povinné pre študentov.',
            'address.city.string' => 'Mesto musí byť text.',

            'address.zip_code.required_if' => 'PSČ je povinné pre študentov.',
            'address.zip_code.string' => 'PSČ musí byť text.',

            'address.country.required_if' => 'Krajina je povinná pre študentov.',
            'address.country.string' => 'Krajina musí byť text.',
        ];
    }
}
