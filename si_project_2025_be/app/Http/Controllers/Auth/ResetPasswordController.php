<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\User;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            // Skúsime nájsť záznam v password_reset_tokens
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$resetRecord) {
                return response()->json(['message' => 'Token pre obnovenie hesla neexistuje.'], 404);
            }

            // Overíme, či token sedí
            if ($resetRecord->token !== $request->token) {
                return response()->json(['message' => 'Neplatný token pre reset hesla.'], 400);
            }

            // Skontrolujeme, či token nevypršal po 60 minútach
            if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
                return response()->json(['message' => 'Platnosť tokenu vypršala.'], 400);
            }

            // Nájdeme používateľa
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'Používateľ s týmto e-mailom neexistuje.'], 404);
            }

            // Zmeníme heslo
            $user->password = Hash::make($request->password);
            $user->save();

            // Odstránime token z tabuľky
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            $status = true;

        } catch (\Exception $e) {
            Log::error('Chyba pri resete hesla: ' . $e->getMessage());
            $status = false;
        }


        return $status
            ? response()->json(['message' => 'Heslo bolo úspešne zmenené.'])
            : response()->json(['message' => 'Reset hesla zlyhal.'], 500);
    }
}
