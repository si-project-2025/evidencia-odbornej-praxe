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
            'name'         => 'required|string|max:255',
            'ico'          => 'nullable|string|max:20',
            'country'      => 'required|string|max:255',
            'city'         => 'required|string|max:255',
            'zip_code'     => 'required|string|max:20',
            'street'       => 'required|string|max:255',
            'house_number' => 'required|string|max:20',
        ]);

        if (Company::where('name', $validated['name'])->exists()) {
            return response()->json([
                'error' => 'Firma s týmto názvom už existuje.',
            ], 422);
        }

        $address = Address::create([
            'country'      => $validated['country'],
            'city'         => $validated['city'],
            'zip_code'     => $validated['zip_code'],
            'street'       => $validated['street'],
            'house_number' => $validated['house_number'],
        ]);

        $company = Company::create([
            'name'       => $validated['name'],
            'ico'        => $validated['ico'] ?? null,
            'address_id' => $address->address_id,
        ]);

        return response()->json([
            'company_id' => $company->company_id,
            'message' => 'Firma bola úspešne vytvorená',
        ], 201);
    }

}
