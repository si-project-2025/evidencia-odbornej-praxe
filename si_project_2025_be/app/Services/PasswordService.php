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

        // Skúsime nájsť používateľa podľa e-mailu alebo alternatívneho e-mailu
        $user = User::where('email', $email)
            ->orWhere('alt_email', $email)
            ->first();

        if (!$user) {
            throw new \Exception('Používateľ s týmto e-mailom neexistuje.');
        }

        // Overenie e-mailu študenta
        if ($user->role_id === 2) { // študent
            if ($user->alt_email === $email) {
                throw new \Exception('Študent nemôže použiť alternatívny e-mail na obnovenie hesla.');
            }
            if ( $user->email === $email && !str_ends_with($email, '@student.ukf.sk')) {
                throw new \Exception('Študent musí použiť študentský e-mail končiaci @student.ukf.sk.');
            }
            // Ak je školský e-mail, pokračujeme ďalej
        }

        // Ak používateľ nie je študent, e-mail aj alt_email sú povolené na reset hesla
        $targetEmail = ($user->alt_email === $email) ? $user->alt_email : $user->email;

        // Vygenerovanie tokenu
        $token = Str::random(64);

        // Uloženie tokenu do tabuľky password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $targetEmail],
            [
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        //Link na stránku resetu hesla
        $resetUrl = "http://localhost:5173/reset-password?token=$token&email=" . urlencode($targetEmail);

        // Pošleme email
        try {
            Mail::send('emails.reset-password', ['url' => $resetUrl], function ($message) use ($targetEmail) {
                $message->to($targetEmail)->subject('Obnova hesla');
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


        // Nájdeme používateľa podľa e-mailu alebo alt_email
        $user = User::where('email', $email)
            ->orWhere('alt_email', $email)
            ->first();

        if (!$user) {
            throw new \Exception('Používateľ s týmto emailom neexistuje.');
        }

        if ($user->role_id === 2) { // 2 = študent
            if ($user->alt_email === $email) {
                throw new \Exception('Študent nemôže obnoviť heslo pomocou alternatívneho e-mailu.');
            }
            if (!str_ends_with($email, '@student.ukf.sk')) {
                throw new \Exception('Študent musí použiť školský e-mail na reset hesla.');
            }
        }


        // Zmeníme heslo
        $user->update(['password' => Hash::make($password)]);

        // Odstránime token z tabuľky
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return true;
    }
}

