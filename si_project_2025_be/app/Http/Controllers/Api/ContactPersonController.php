<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Company;
use App\Models\ContactPerson;
use App\Models\Internship;
use Illuminate\Http\Request;

class ContactPersonController extends Controller
{
    /**
     * Získa všetky kontaktné osoby
     */
    public function index()
    {
        return ContactPerson::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:contact_persons,email',
            'phone' => 'nullable|string|max:20',
            'company_id' => 'required|integer|exists:companies,company_id',
        ], [
            'email.unique' => 'Kontaktná osoba s týmto emailom už existuje.',
            'company_id.exists' => 'Vyberte firmu'
        ]);

        $contact = ContactPerson::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'company_id' => $validated['company_id']
        ]);

        return response()->json([
            'id' => $contact->id,
            'message' => 'Kontakt bol úspešne vytvorený',
        ], 201);
    }
}
