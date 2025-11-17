<?php

namespace App\Services;

use App\Models\Internship;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InternshipStatusNotificationService
{
    public function sendStatusChangedEmails(Internship $internship): bool
    {
        try {
            $internship->load(['student', 'company', 'status', 'contactPersons', 'garant']);

            $this->sendEmailToStudent($internship);
            $this->sendEmailToGarant($internship);
            $this->sendEmailToCompany($internship);

            return true;

        } catch (\Exception $e) {
            Log::error('Chyba pri odosielaní emailu o zmene stavu praxe: ' . $e->getMessage());
            return false;
        }
    }

    private function buildUrl(string $email, Internship $internship): string
    {
        $frontendUrl = config('app.frontend_url');
        $redirect = "/internships/{$internship->internships_id}";
        return "{$frontendUrl}/login?redirect=" . urlencode($redirect) . "&email=" . urlencode($email);
    }

    private function sendEmailToStudent(Internship $internship): void
    {
        $student = $internship->student;
        if (!$student || !$student->email) return;

        $studentUrl = $this->buildUrl($student->email, $internship);

        $data = [
            'name' => $student->name . ' ' . $student->surname,
            'student' => $student->name . ' ' . $student->surname,
            'company' => $internship->company->name,
            'status' => $internship->status->type,
            'url' => $studentUrl,
        ];

        Mail::send('emails.internship-status-change', $data, function ($message) use ($student) {
            $message->to($student->email)
                ->subject('Zmena stavu vašej praxe');
        });
    }

    private function sendEmailToGarant(Internship $internship): void
    {
        $garant = $internship->garant;
        if (!$garant || !$garant->email) return;

        $garantUrl = $this->buildUrl($garant->email, $internship);

        $data = [
            'name' => $garant->name . ' ' . $garant->surname,
            'student' => $internship->student->name . ' ' . $internship->student->surname,
            'company' => $internship->company->name,
            'status' => $internship->status->type,
            'url' => $garantUrl,
        ];

        Mail::send('emails.internship-status-change', $data, function ($message) use ($garant) {
            $message->to($garant->email)
                ->subject('Zmena stavu praxe študenta');
        });
    }
    private function sendEmailToCompany(Internship $internship): void
    {
        $company = $internship->company;
        $contactPersons = $internship->contactPersons;

        if (!$company) return;

        foreach ($contactPersons as $person) {
            if (!$person->email) continue;

            $contactUrl = $this->buildUrl($person->email, $internship);

            $data = [
                'name' => $person->name . ' ' . $person->surname,
                'student' => $internship->student->name . ' ' . $internship->student->surname,
                'company' => $company->name,
                'status' => $internship->status->type,
                'url' => $contactUrl,
            ];

            Mail::send('emails.internship-status-change', $data, function ($message) use ($person) {
                $message->to($person->email)
                    ->subject('Zmena stavu praxe študenta');
            });
        }
    }
}
