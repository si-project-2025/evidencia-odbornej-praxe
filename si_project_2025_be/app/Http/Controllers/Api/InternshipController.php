<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternshipRequest;
use App\Models\Internship;
use Illuminate\Http\Request;

class InternshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $internships = Internship::all();
        return response()->json($internships);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InternshipRequest $request)
    {
        $internship = Internship::create($request->validated());

        return response()->json($internship, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        #$internship = Internship::findOrFail($id);
        $internship = Internship::with(['company', 'status', 'garant'])
            ->findOrFail($id);
        return response()->json($internship);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InternshipRequest $request, string $id)
    {
        $internship = Internship::findOrFail($id);
        $internship->update($request->validated());

        return response()->json($internship);
    }

    /**
     * Remove the specified resource from storage.
     */
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
}
