<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ExternalInternshipController extends Controller
{
    /**
     * Zmena stavu praxe zo "Schválená" na "Obhájená"
     *
     * @param Internship $internship
     * @return JsonResponse
     */

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'semester' => 'nullable|in:Z,L',
        ]);

        $query = Internship::where('status_id', 4);

        if (isset($validated['year'])) {
            $query->where('year', $validated['year']);
        }

        if (isset($validated['semester'])) {
            $query->where('semester', $validated['semester']);
        }

        $internships = $query->get(['internships_id', 'year', 'semester']); // ← Zmeň 'id' na 'internships_id'

        Log::info('Externý systém získal zoznam praxí', [
            'filters' => $validated,
            'count' => $internships->count(),
            'oauth_token_id' => $request->attributes->get('oauth_token')?->id,
        ]);

        return response()->json([
            'message' => 'Zoznam praxí v stave "Schválená" úspešne načítaný.',
            'filters' => $validated,
            'count' => $internships->count(),
            'data' => $internships,
        ], 200);
    }

    public function defend(Internship $internship): JsonResponse
    {
        // --- Definícia stavov ---
        $statusSchvalena = 4;
        $statusObhajena = 5;

        // --- Overenie oprávnenosti zmeny stavu ---
        if ($internship->status_id !== $statusSchvalena) {
            Log::warning('Pokus o zmenu stavu praxe, ktorá nie je v stave Schválená', [
                'internship_id' => $internship->internships_id,
                'current_status_id' => $internship->status_id,
                'oauth_token_id' => request()->attributes->get('oauth_token')?->id,
            ]);

            return response()->json([
                'message' => 'Operácia bola zamietnutá. Prax je možné označiť ako "Obhájená" iba ak je v stave "Schválená".',
                'current_status_id' => $internship->status_id,
                'internship_id' => $internship->internships_id,
            ], 409);
        }

        // --- Aktualizácia stavu praxe ---
        $internship->status_id = $statusObhajena;
        $internship->save();

        Log::info('Stav praxe úspešne zmenený na Obhájenú', [
            'internship_id' => $internship->internships_id,
            'oauth_token_id' => request()->attributes->get('oauth_token')?->id,
        ]);

        // --- Odpoveď API ---
        return response()->json([
            'message' => 'Stav praxe bol úspešne zmenený na "Obhájená".',
            'data' => [
                'id' => $internship->internships_id,
                'status_id' => $internship->status_id,
                'status' => $internship->status,
                'updated_at' => $internship->updated_at,
            ],
        ], 200);
    }
}
