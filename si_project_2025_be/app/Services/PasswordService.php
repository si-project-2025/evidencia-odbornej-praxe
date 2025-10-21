<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordService
{
    public function sendResetLink(string $email): bool
    {
        // Zakáž reset pre alternatívne študentské emaily
        // TODO

        // Skúsime nájsť používateľa
        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        }

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
        try {
            Mail::send('emails.reset-password', ['url' => $resetUrl], function ($message) use ($user) {
                $message->to($user->email)->subject('Obnova hesla');
            });
            return true;
        } catch (\Exception $e) {
            Log::error('Mail sa nepodarilo odoslať: ' . $e->getMessage());
            return false;
        }
    }

    public function resetPassword(string $email, string $token, string $password): bool
    {
        // Skúsime nájsť záznam v password_reset_tokens
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$resetRecord) {
            throw new \Exception('Token pre obnovenie hesla neexistuje..');
        }

        if ($resetRecord->token !== $token) {
            throw new \Exception('Neplatný token pre reset hesla.');
        }

        // Skontrolujeme, či token nevypršal po 60 minútach
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            throw new \Exception('Platnosť tokenu vypršala.');
        }

        // Nájdeme používateľa
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception('Používateľ s týmto emailom neexistuje.');
        }

        // Zmeníme heslo
        $user->update(['password' => Hash::make($password)]);

        // Odstránime token z tabuľky
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return true;
    }
}

