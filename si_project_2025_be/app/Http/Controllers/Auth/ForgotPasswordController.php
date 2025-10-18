<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;


class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Zakáž reset pre alternatívne študentské emaily
        // TODO

        // Skúsime nájsť používateľa
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Používateľ s týmto e-mailom neexistuje.'], 404);
        }

        try {
            $token = Str::random(64);

            // Uloženie tokenu do tabuľky password_reset_tokens
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]
            );

            //Link na stránku resetu hesla
            $resetUrl = "http://localhost:5173/reset-password?token=$token&email=" . urlencode($user->email);

            // Pošleme email cez Brevo
            Mail::send('emails.reset-password', ['url' => $resetUrl], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Obnova hesla');
            });

            $status = true;

        } catch (\Exception $e) {
            Log::error('Chyba pri odosielaní e-mailu na reset hesla: ' . $e->getMessage());
            $status = false;
        }

        return $status
            ? response()->json(['message' => 'Odkaz na reset hesla bol odoslaný na váš email.'])
            : response()->json(['message' => 'Nepodarilo sa odoslať odkaz na obnovenie hesla.'], 500);
    }
}
