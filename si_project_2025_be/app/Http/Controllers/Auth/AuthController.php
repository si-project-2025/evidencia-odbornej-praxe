<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function setPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'Token nenájdený alebo už bol použitý.'
            ], 404);
        }

        // Overenie tokenu
        if (!Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'message' => 'Neplatný token.'
            ], 401);
        }

        // Kontrola expirácie (60 minút)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            return response()->json([
                'message' => 'Token expiroval. Požiadajte o nový aktivačný email.'
            ], 410);
        }

        // Nastavenie hesla
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Vymazanie tokenu
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Heslo bolo úspešne nastavené. Môžete sa prihlásiť.'
        ], 200);
    }
    public function register(Request $request): JsonResponse
    {
        $studentRoleId = 2;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'email' => 'required|string|email|max:191|unique:users',
            'role_id' => 'required|integer|exists:roles,role_id',

            'alt_email' => 'nullable|string|email|max:191|unique:users,alt_email',
            'study_program' => 'nullable|string|max:100',
            'phone_number' => 'nullable|string|max:20',

            'street' => "required_if:role_id,{$studentRoleId}|string|max:255",
            'house_number' => "required_if:role_id,{$studentRoleId}|string|max:10",
            'city' => "required_if:role_id,{$studentRoleId}|string|max:255",
            'zip_code' => "required_if:role_id,{$studentRoleId}|string|max:10",
            'country' => "required_if:role_id,{$studentRoleId}|string|max:255",
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $addressId = null;
            if ((int)$request->role_id === $studentRoleId) {
                $address = Address::create([
                    'street' => $request->street,
                    'house_number' => $request->house_number,
                    'city' => $request->city,
                    'zip_code' => $request->zip_code,
                    'country' => $request->country,
                ]);
                $addressId = $address->address_id;
            }

            $temporaryPassword = Str::random(40);

            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'role_id' => $request->role_id,
                'address_id' => $addressId, // Priradíme ID adresy (bude null pre ne-študentov)
                'alt_email' => $request->alt_email,
                'study_program' => $request->study_program,
                'phone_number' => $request->phone_number,
            ]);


            $token = Str::random(60);
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            $resetUrl = 'http://localhost:5173/set-password?token=' . $token . '&email=' . urlencode($user->email);

            Mail::send('emails.set-initial-password', ['user' => $user, 'url' => $resetUrl], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Aktivujte si účet a nastavte heslo');
            });

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registrácia zlyhala, skúste to znova.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Registrácia úspešná! Pre aktiváciu účtu a nastavenie hesla skontrolujte svoj e-mail.',
            'email' => $user->email
        ], 200);
    }
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $isPendingActivation = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->exists();

        if ($isPendingActivation) {
            return response()->json([
                'message' => 'Váš účet ešte nie je aktívny. Skontrolujte si e-mail pre nastavenie hesla a aktiváciu účtu.'
            ], 403);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Nesprávne prihlasovacie údaje.'
            ], 401);
        }

        $user = User::with(['role', 'address'])
            ->where('email', $request['email'])
            ->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->last_login = Carbon::now();
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Úspešne odhlásený.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Logout failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Odhlásenie zlyhalo.'
            ], 500);
        }
    }
}
