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

        // Dátumy
        if (isset($data['start_at'])) {
            $data['start_at'] = $data['start_at'] ? $data['start_at'] . ' 00:00:00' : $internship->start_at;
        }

        if (isset($data['end_at'])) {
            $data['end_at'] = $data['end_at'] ? $data['end_at'] . ' 00:00:00' : null;
        }

        $data['updated_at'] = now();

        if (isset($data['company_id'])) {
            $data['status_id'] = Status::where('type', 'Vytvorená')->value('status_id');
            $internship->documents()->update(['is_verified' => false]);
        }

        $internship->update($data);

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
        } else if ($user->role->name === 'firma') {
            $company_id = Company::where('user_id', $user->users_id)->value('company_id');
            $query->where('company_id', $company_id);
        }
        else {
            $query->where('users_id', $user->users_id);
        }

        $internships = $query
            ->with(['company.address', 'garant', 'student', 'status'])
            ->get();

        return response()->json(InternshipResource::collection($internships));
    }

    public function getCompanies(Request $request)
    {
        $query = Company::with('address')
            ->select('company_id as id', 'name', 'address_id', 'ico')
            ->orderBy('name');

        if ($request->boolean('onlyAvailable')) {
            $query->whereNull('user_id');
        }

        return response()->json($query->get());
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

    public function getStudents(Request $request)
    {
        $studentRoleId = Role::where('name', 'student')->value('role_id');

        if (!$studentRoleId) {
            return response()->json([]);
        }

        if ($request->user()->role->name === 'firma') {
            $students = User::join('internships', 'internships.users_id', '=', 'users.users_id')
                ->where('users.role_id', $studentRoleId)
                ->where('internships.company_id', $request->user()->users_id)
                ->select('users.users_id', 'users.name', 'users.surname', 'users.email')
                ->orderBy('users.surname')
                ->get();
        } else {
            $students = User::where('role_id', $studentRoleId)
                ->select('users_id', 'name', 'surname', 'email')
                ->orderBy('surname')
                ->get();
        }

        return response()->json($students);
    }

    public function verify(Request $request, string $id)
    {
        $request->validate([
            'decision' => ['required', 'in:approve,reject'],
        ]);

        $userId = $request->user()->users_id;
        $userRole = $request->user()->role->name;
        $internship = Internship::with(['status', 'documents'])->findOrFail($id);

        $this->checkPermission($internship, $userId, $userRole);

        if ($request->decision === 'approve' && $userRole === 'garant') {
            $contract = $internship->documents()
                ->where('type', 'Zmluva')
                ->first();

            if (!$contract || !$contract->is_verified) {
                return response()->json([
                    'message' => 'Nie je možné schváliť prax, kým zmluva nie je nahratá a potvrdená garantom.'
                ], 409);
            }

            $statusType = 'Schválená';
        } else if ($request->decision === 'approve' && $userRole === 'firma') {
            $statusType = 'Potvrdená';
        } else if ($request->decision === 'reject' && $userRole === 'firma') {
            $statusType = 'Zamietnutá';
        } else {
            $statusType = 'Neschválená';
        }

        $internship->status_id = Status::where('type', $statusType)->value('status_id');
        $internship->save();
        $internship->refresh();

        app(InternshipStatusNotificationService::class)
            ->sendStatusChangedEmails($internship, $userRole, $statusType);

        return response()->json(new InternshipResource($internship));
    }

    private function checkPermission(Internship $internship, int $userId, string $role)
    {
        if (!in_array($role, ['garant', 'firma'])) {
            abort(403, 'Nemáte oprávnenie vykonať túto akciu.');
        }

        $companyId = Company::where('user_id', $userId)->value('company_id');

        if (
            ($role === 'garant' && $internship->garant_id !== $userId) ||
            ($role === 'firma' && $internship->company_id !== $companyId)
        ) {
            abort(403, 'Nemôžete spracovať prax pridelenú niekomu inému.');
        }

        if ($role === 'garant' && $internship->status->type !== 'Potvrdená') {
            abort(409, 'Prax musí byť potvrdená firmou.');
        }
    }
}
