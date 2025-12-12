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
    protected InternshipStatusNotificationService $notificationService;

    public function __construct(InternshipStatusNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Odošle verifikačný email kontaktnej osobe
     */
    public function sendVerificationEmail(Internship $internship): bool
    {

        $internship->load(['contactPerson', 'company', 'user']);
        if (!$internship->contactPerson) {
            throw new \Exception('Pre túto prax nie je priradená kontaktná osoba. Prosím, priraďte ju v nastaveniach praxe.');
        }

        try {
            $contactPerson = $internship->contactPerson;
            $token = $this->createVerificationToken($contactPerson->email, $internship->internships_id);
            $this->sendVerificationEmailToContact($internship, $contactPerson->email, $token);

            return true;
        } catch (\Exception $e) {
            Log::error('Nepodarilo sa odoslať verifikačný email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vytvorí verifikačný token
     */
    private function createVerificationToken(string $email, int $internshipId): string
    {
        $token = Str::random(64);

        // Ukladáme token k emailu a ID praxe
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
        // URL obsahuje token a email pre overenie
        $verificationUrl = "{$frontendUrl}/verify-internship?token={$token}&email=" . urlencode($contactEmail);

        Mail::send('emails.company-verification', [
            'internship' => $internship,
            'verificationUrl' => $verificationUrl,
            'contactPerson' => $internship->contactPerson // Môžeme poslať aj objekt osoby do šablóny
        ], function ($message) use ($contactEmail) {
            $message->to($contactEmail);
            $message->subject('Overenie praxe študenta');
        });
    }

    /**
     * Získaj detaily praxe pred overením
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

        // ZMENA: Načítame prax aj s priradenou kontaktnou osobou
        $internship = Internship::with(['contactPerson', 'company', 'user'])
            ->where('internships_id', $verificationRecord->internships_id)
            ->first();

        if (!$internship) {
            throw new \Exception('Prax nenájdená.');
        }

        // Validácia: Overíme, či email v tokene stále patrí priradenej kontaktnej osobe
        // Toto zabráni situácii, kedy sa osoba zmenila, ale starý link by stále fungoval
        if ($internship->contactPerson && $internship->contactPerson->email !== $email) {
            throw new \Exception('Kontaktná osoba pre túto prax bola zmenená. Tento odkaz už nie je platný.');
        }

        return [
            'internship' => new InternshipResource($internship),
            'is_expired' => Carbon::parse($verificationRecord->created_at)->addDays(7)->isPast(),
        ];
    }

    /**
     * Potvrdí alebo zamietne prax
     */
    public function handleInternshipAction(string $email, string $token, string $action): array
    {
        $verification = DB::table('internship_verification_tokens')
            ->where('email', $email)
            ->first();

        if (!$verification) {
            throw new \Exception('Token nenájdený alebo už bol použitý.');
        }

        if (!Hash::check($token, $verification->token)) {
            throw new \Exception('Neplatný token.');
        }

        if (Carbon::parse($verification->created_at)->addDays(7)->isPast()) {
            throw new \Exception('Token expiroval.');
        }

        $internship = Internship::with('status')
            ->where('internships_id', $verification->internships_id)
            ->first();

        if (!$internship) {
            throw new \Exception('Prax nenájdená.');
        }

        $this->updateInternshipStatus($internship, $action);

        // Po úspešnom overení zmažeme token
        DB::table('internship_verification_tokens')
            ->where('email', $email)
            ->where('internships_id', $verification->internships_id)
            ->delete();

        return [
            'message' => $this->actionMessage($action),
            'internship' => new InternshipResource($internship)
        ];
    }

    private function updateInternshipStatus(Internship $internship, string $action): void
    {
        $current = $internship->status->type;

        if ($action === 'confirm') {
            if ($current === 'Potvrdená') {
                throw new \Exception('Prax už bola overená.');
            }
            $type = 'Potvrdená';

        } else {
            if ($current === 'Potvrdená') {
                throw new \Exception('Prax už bola potvrdená a nemôže byť zamietnutá.');
            }
            $type = 'Zamietnutá';
        }

        $internship->status_id = Status::where('type', $type)->value('status_id');
        $internship->save();
        $internship->refresh();

        $this->sendEmailNotification($type, $internship);
    }

    private function sendEmailNotification(string $status, Internship $internship): void
    {
        $this->notificationService->sendEmailToStudent($internship);

        if ($status === 'Potvrdená') {
            $this->notificationService->sendEmailToGarant($internship);
        }
    }

    private function actionMessage(string $action): string
    {
        return $action === 'confirm'
            ? 'Prax bola úspešne overená.'
            : 'Prax bola úspešne zamietnutá.';
    }
}
