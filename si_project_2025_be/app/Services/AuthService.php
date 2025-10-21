<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class AuthService
{
    public function login(array $credentials): JsonResponse
    {
        if (DB::table('password_reset_tokens')->where('email', $credentials['email'])->exists()) {
            return response()->json([
                'message' => 'Váš účet ešte nie je aktívny. Skontrolujte si e-mail.'
            ], 403);
        }

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Nesprávne prihlasovacie údaje.'
            ], 401);
        }

        $user = User::with(['role', 'address'])
            ->where('email', $credentials['email'])
            ->firstOrFail();


        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update(['last_login' => Carbon::now()]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function logout($user): JsonResponse
    {
        try {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Úspešne odhlásený.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Odhlásenie zlyhalo.'], 500);
        }
    }
}
