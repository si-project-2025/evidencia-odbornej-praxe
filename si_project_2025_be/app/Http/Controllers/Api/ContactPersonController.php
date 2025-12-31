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
    public function index(Request $request)
    {
        $user = $request->user();
        $query = ContactPerson::query();

        if ($user->role->name === 'firma') {
            $companyId = Company::where('user_id', $user->users_id)->value('company_id');
            $query->where('company_id', $companyId);
        } else {
            $companyId = $request->query('company_id');
            if ($companyId) {
                $query->where('company_id', $companyId);
            }
        }
        return response()->json($query->get());
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

    public function destroy(Request $request, ContactPerson $contactPerson)
    {
        $user = $request->user();

        if ($user->role->name === 'firma') {
            $companyId = Company::where('user_id', $user->users_id)->value('company_id');

            if (!$companyId || $contactPerson->company_id !== $companyId) {
                return response()->json(['error' => 'Nemáte oprávnenie vymazať tento kontakt.'], 403);
            }
        }
        $contactPerson->delete();

        return response()->json(['message' => 'Kontakt bol vymazaný.'], 200);
    }


}
