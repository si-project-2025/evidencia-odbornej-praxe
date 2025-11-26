<?php

namespace App\Services;

use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InternshipVerificationService
{
    /**
     * Odošle verifikačný email kontaktným osobám
     */
    public function sendVerificationEmail(Internship $internship): bool
    {
        $internship->load(['contactPersons', 'company', 'user']);

        if ($internship->contactPersons->isEmpty()) {
            throw new \Exception('Pre túto prax nie je zadaná žiadna kontaktná osoba.');
        }

        try {
            foreach ($internship->contactPersons as $contactPerson) {
                $token = $this->createVerificationToken($contactPerson->email, $internship->internships_id);
                $this->sendVerificationEmailToContact($internship, $contactPerson->email, $token);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Nepodarilo sa odoslať verifikačný email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vytvorí verifikačný token (rovnaký princíp ako password reset)
     */
    private function createVerificationToken(string $email, int $internshipId): string
    {
        $token = Str::random(64);

        DB::table('internship_verification_tokens')->updateOrInsert(
            ['email' => $email, 'internships_id' => $internshipId],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        return $token;
    }

    /**
     * Odošle email s verifikačným linkom
     */
    private function sendVerificationEmailToContact(Internship $internship, string $contactEmail, string $token): void
    {
        $frontendUrl = config('app.frontend_url');
        $verificationUrl = "{$frontendUrl}/verify-internship?token={$token}&email=" . urlencode($contactEmail);

        Mail::send('emails.company-verification', [
            'internship' => $internship,
            'verificationUrl' => $verificationUrl
        ], function ($message) use ($contactEmail) {
            $message->to($contactEmail);
            $message->subject('Overenie praxe študenta');
        });
    }

    /**
     * Získaj detaily praxe pred overením (pre zobrazenie na frontende)
     */
    public function getVerificationDetails(string $email, string $token): array
    {
        $verificationRecord = DB::table('internship_verification_tokens')
            ->where('email', $email)
            ->first();

        if (!$verificationRecord) {
            throw new \Exception('Token nenájdený.');
        }

        if (!Hash::check($token, $verificationRecord->token)) {
            throw new \Exception('Neplatný token.');
        }

        if (Carbon::parse($verificationRecord->created_at)->addDays(7)->isPast()) {
            throw new \Exception('Token expiroval.');
        }

        $internship = Internship::where('internships_id', $verificationRecord->internships_id)->first();

        if (!$internship) {
            throw new \Exception('Prax nenájdená.');
        }

        return [
            'internship' => new InternshipResource($internship),
            'is_expired' => Carbon::parse($verificationRecord->created_at)->addDays(7)->isPast(),
        ];
    }

    /**
     * Potvrdí alebo zamietne prax podľa akcie
     */
    public function handleInternshipAction(string $email, string $token, string $action): array
    {
        $verificationRecord = DB::table('internship_verification_tokens')
            ->where('email', $email)
            ->first();

        if (!$verificationRecord) {
            throw new \Exception('Token nenájdený alebo už bol použitý.');
        }

        if (!Hash::check($token, $verificationRecord->token)) {
            throw new \Exception('Neplatný token.');
        }

        if (Carbon::parse($verificationRecord->created_at)->addDays(7)->isPast()) {
            throw new \Exception('Token expiroval.');
        }

        $internship = Internship::with('status')
            ->where('internships_id', $verificationRecord->internships_id)
            ->first();

        if (!$internship) {
            throw new \Exception('Prax nenájdená.');
        }

        if ($action === 'confirm') {
            if ($internship->status->type == 'Potvrdená') {
                throw new \Exception('Prax už bola overená.');
            }
            $statusId = Status::where('type', 'Potvrdená')->value('status_id');
            $internship->status_id = $statusId;
            $message = 'Prax bola úspešne overená.';
        } else { // reject
            if ($internship->status->type == 'Potvrdená') {
                throw new \Exception('Prax už bola potvrdená a nemôže byť zamietnutá.');
            }
            $statusId = Status::where('type', 'Zamietnutá')->value('status_id');
            $internship->status_id = $statusId;
            $message = 'Prax bola úspešne zamietnutá.';
        }

        $internship->save();
        $internship->load('status');

        // Odstránime token po akcii
        DB::table('internship_verification_tokens')
            ->where('email', $email)
            ->where('internships_id', $verificationRecord->internships_id)
            ->delete();

        return [
            'message' => $message,
            'internship' => new InternshipResource($internship)
        ];
    }
}
