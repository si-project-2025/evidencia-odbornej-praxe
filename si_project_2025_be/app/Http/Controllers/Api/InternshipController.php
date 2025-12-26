<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipRequest;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Models\Status;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Services\InternshipStatusNotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


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
        $data['status_id'] = Status::where('type', 'Vytvorená')->value('status_id');
        $data['start_at'] = $data['start_at'] ? $data['start_at'] . ' 00:00:00' : null;
        $data['end_at'] = $data['end_at'] ? $data['end_at'] . ' 00:00:00' : null;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $internship = Internship::create($data);

        return response()->json(new InternshipResource($internship), 201);
    }

    public function show(string $id)
    {
        $internship = Internship::findOrFail($id);
        return response()->json(new InternshipResource($internship));
    }

    public function update(InternshipRequest $request, string $id)
    {
        $internship = Internship::findOrFail($id);
        $data = $request->validated();

        $oldStatus = $internship->status?->type;

        // Dátumy
        if (isset($data['start_at'])) {
            $data['start_at'] = $data['start_at'] ? $data['start_at'] . ' 00:00:00' : $internship->start_at;
        }

        if (isset($data['end_at'])) {
            $data['end_at'] = $data['end_at'] ? $data['end_at'] . ' 00:00:00' : null;
        }

        // Status pri update
        if (isset($data['status'])) {
            if ($data['status'] === 'Schválená') {
                $contract = $internship->documents()
                    ->where('type', 'Zmluva')
                    ->first();

                if (!$contract || !$contract->is_verified) {
                    return response()->json([
                        'message' =>
                            'Nie je možné zmeniť stav praxe na Schválená, kým zmluva nie je nahratá a potvrdená garantom.'
                    ], 409);
                }
            }

            $data['status_id'] = Status::where('type', $data['status'])->value('status_id');
        }
        unset($data['status']);

        $data['updated_at'] = now();

        $internship->update($data);
        $internship->load('status');


        // Ak sa zmenil stav, pošleme e-maily
        if ($oldStatus !== $internship->status?->type) {
            app(InternshipStatusNotificationService::class)
                ->sendStatusChangedEmails($internship);
        }

        return response()->json(new InternshipResource($internship));
    }

    public function destroy(string $id)
    {
        $internship = Internship::findOrFail($id);
        $internship->delete();

        return response()->json(['message' => 'Prax úspešne vymazaná']);
    }

    public function getInternshipsByUser(Request $request)
    {
        $user = $request->user();

        $query = Internship::query()
            ->orderByDesc('year');

        if ($user->role->name === 'garant') {
            $query->where('garant_id', $user->users_id);
        } else {
            $query->where('users_id', $user->users_id);
        }

        $internships = $query
            ->with(['company.address', 'garant', 'student', 'status'])
            ->get();

        return response()->json(InternshipResource::collection($internships));
    }

    public function getCompanies()
    {
        $companies = Company::with('address')
            ->select('company_id as id', 'name', 'address_id')
            ->orderBy('name')
            ->get();

        return response()->json($companies);
    }

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

    public function getStudents()
    {
        $studentRoleId = Role::where('name', 'student')->value('role_id');

        if (!$studentRoleId) {
            return response()->json([]);
        }

        $students = User::where('role_id', $studentRoleId)
            ->select('users_id', 'name', 'surname', 'email')
            ->orderBy('surname')
            ->get();

        return response()->json($students);
    }

    public function verify(Request $request, string $id)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
        ]);

        $user = $request->user();
        $internship = Internship::with(['status', 'documents'])->findOrFail($id);

        if ($user->role->name !== 'garant') {
            return response()->json(['message' => 'Nemáte oprávnenie vykonať túto akciu.'], 403);
        }

        if ($internship->garant_id !== $user->users_id) {
            return response()->json(['message' => 'Nemôžete spracovať prax iného garanta.'], 403);
        }

        if ($internship->status->type !== 'Potvrdená') {
            return response()->json(['message' => 'Prax nie je v stave Potvrdená.'], 409);
        }

        if ($request->decision === 'approve') {
            $contract = $internship->documents()
                ->where('type', 'Zmluva')
                ->first();

            if (!$contract || !$contract->is_verified) {
                return response()->json([
                    'message' => 'Nie je možné schváliť prax, kým zmluva nie je nahratá a potvrdená garantom.'
                ], 409);
            }

            $statusType = 'Schválená';
        } else {
            $statusType = 'Zamietnutá';
        }

        $internship->update([
            'status_id' => Status::where('type', $statusType)->value('status_id'),
        ]);

        app(InternshipStatusNotificationService::class)
            ->sendStatusChangedEmails($internship);

        return response()->json(new InternshipResource($internship));
    }
}
