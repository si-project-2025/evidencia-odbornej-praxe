<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactPerson;
use App\Models\Internship;
use Illuminate\Http\Request;

class ContactPersonController extends Controller
{
    /**
     * Získa všetky kontaktné osoby firmy pre danú prax
     */
    public function index(string $internshipId)
    {
        $internship = Internship::findOrFail($internshipId);
        $contactPersons = $internship->company->contactPersons;

        return response()->json($contactPersons);
    }

    /**
     * Uloží novú kontaktnú osobu k firme
     */
    public function store(Request $request, string $internshipId)
    {
        $internship = Internship::findOrFail($internshipId);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $contactPerson = $internship->company->contactPersons()->create($validatedData);

        return response()->json($contactPerson, 201);
    }

    /**
     * Zobrazí detail kontaktnej osoby
     */
    public function show(string $internshipId, string $id)
    {
        $internship = Internship::findOrFail($internshipId);
        $contactPerson = ContactPerson::findOrFail($id);

        if ($contactPerson->company_id !== $internship->company_id) {
            return response()->json(['message' => 'Contact person does not belong to this company'], 403);
        }

        return response()->json($contactPerson);
    }

    /**
     * Aktualizuje kontaktnú osobu
     */
    public function update(Request $request, string $internshipId, string $id)
    {
        $internship = Internship::findOrFail($internshipId);
        $contactPerson = ContactPerson::findOrFail($id);

        if ($contactPerson->company_id !== $internship->company_id) {
            return response()->json(['message' => 'Contact person does not belong to this company'], 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'surname' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $contactPerson->update($validatedData);

        return response()->json($contactPerson);
    }

    /**
     * Zmaže kontaktnú osobu
     */
    public function destroy(string $internshipId, string $id)
    {
        $internship = Internship::findOrFail($internshipId);
        $contactPerson = ContactPerson::findOrFail($id);

        if ($contactPerson->company_id !== $internship->company_id) {
            return response()->json(['message' => 'Contact person does not belong to this company'], 403);
        }

        $contactPerson->delete();

        return response()->json(['message' => 'Contact person deleted successfully']);
    }
}
