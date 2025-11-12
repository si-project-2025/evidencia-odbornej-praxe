<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Services\InternshipVerificationService;
use Illuminate\Http\Request;

class InternshipVerificationController extends Controller
{
    protected $verificationService;

    public function __construct(InternshipVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Odošle email na overenie firmy
     */
    public function sendVerificationEmail(string $internshipId)
    {
        try {
            $internship = Internship::with(['status'])->findOrFail($internshipId);

            if ($internship->status->type === 'Potvrdená') {
                return response()->json([
                    'message' => 'Prax už bola potvrdená.'
                ], 500);
            }

            $success = $this->verificationService->sendVerificationEmail($internship);

            if (!$success) {
                return response()->json([
                    'message' => 'Nepodarilo sa odoslať email.'
                ], 500);
            }

            return response()->json([
                'message' => 'Email na overenie bol úspešne odoslaný.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Získaj informácie o praxi pre overenie (pred potvrdením)
     */
    public function getVerificationDetails(Request $request)
    {
        try {
            $email = $request->query('email');
            $token = $request->query('token');

            if (!$email || !$token) {
                return response()->json(['message' => 'Token alebo email chýba.'], 400);
            }

            $details = $this->verificationService->getVerificationDetails($email, $token);

            return response()->json($details);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Overiť prax pomocou tokenu
     */
    public function verifyInternship(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'token' => 'required|string',
        ]);

        try {
            $result = $this->verificationService->verifyInternship(
                $request->email,
                $request->token
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
