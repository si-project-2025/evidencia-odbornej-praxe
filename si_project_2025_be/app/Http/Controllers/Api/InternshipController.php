<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipRequest;
use App\Models\Internship;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\User;
use App\Models\Role;


class InternshipController extends Controller
{
    public function index()
    {
        $internships = Internship::all();
        return response()->json($internships);
    }

    public function store(InternshipRequest $request)
    {
        $data = $request->validated();
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $internship = Internship::create($data);

        return response()->json($internship, 201);
    }

    public function show(string $id)
    {
        #$internship = Internship::findOrFail($id);
        $internship = Internship::with(['company', 'status', 'garant','documents','company.address'])
            ->findOrFail($id);
        return response()->json($internship);
    }

    public function update(InternshipRequest $request, string $id)
    {
        $internship = Internship::findOrFail($id);

        $data = $request->validated();
        $data['updated_at'] = now();

        $internship->update($data);

        return response()->json($internship);
    }

    public function destroy(string $id)
    {
        $internship = Internship::findOrFail($id);
        $internship->delete();

        return response()->json(['message' => 'Internship deleted successfully']);
    }

    public function internshipsOfStudent(Request $request)
    {
        $user = $request->user();

        $internships = Internship::with(['company', 'status', 'garant'])
            ->where('users_id', $user->users_id)
            ->orderByDesc('year')
            ->get();

        return response()->json($internships);
    }

    //Len dočasné riešenie
    //Zoznam firiem (pre dropdown vo formulári)
    public function getCompanies()
    {
        $companies = Company::select('company_id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($companies);
    }

    // Zoznam garantov (pre dropdown vo formulári)
    public function getGarants()
    {
        $garantRoleId = Role::where('name', 'garant')->value('role_id');

        if (!$garantRoleId) {
            return response()->json([]);
        }

        $garants = User::where('role_id', $garantRoleId)
            ->select('users_id', 'name', 'surname', 'email')
            ->orderBy('surname')
            ->get();

        return response()->json($garants);
    }

}
