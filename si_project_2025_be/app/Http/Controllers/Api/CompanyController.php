<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Address;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name',
            'ico' => 'required|string|max:20|unique:companies,ico',
            'address.country' => 'required|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.zip_code' => 'required|string|max:20',
            'address.street' => 'required|string|max:255',
            'address.house_number' => 'required|string|max:20',
        ], [
            'name.unique' => 'Firma s týmto názvom už existuje.',
            'ico.unique' => 'Firma s týmto IČO už existuje.',
        ]);

        $address = Address::create([
            'country' => $validated['address']['country'],
            'city' => $validated['address']['city'],
            'zip_code' => $validated['address']['zip_code'],
            'street' => $validated['address']['street'],
            'house_number' => $validated['address']['house_number'],
        ]);

        $company = Company::create([
            'name' => $validated['name'],
            'ico' => $validated['ico'] ?? null,
            'address_id' => $address->address_id,
        ]);

        return response()->json([
            'company_id' => $company->company_id,
            'message' => 'Firma bola úspešne vytvorená',
        ], 201);
    }

}
