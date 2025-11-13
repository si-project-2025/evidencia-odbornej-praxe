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
            $internship->load(['student', 'company', 'status', 'contactPersons']);

            $student = $internship->student;
            $company = $internship->company;
            $status = $internship->status->type;

            $frontendUrl = config('app.frontend_url');
            $redirect = "/internships/{$internship->internships_id}";

            $studentUrl = "{$frontendUrl}/login?redirect=" . urlencode($redirect) . "&email=" . urlencode($student->email);

            // Email pre študenta
            $studentData = [
                'name' => $student->name . ' ' . $student->surname,
                'student' => $student->name . ' ' . $student->surname,
                'company' => $company->name,
                'status' => $status,
                'url' => $studentUrl,
            ];

            Mail::send('emails.internship-status-change', $studentData, function ($message) use ($student) {
                $message->to($student->email);
                $message->subject('Zmena stavu vašej praxe');
            });

            // Email pre každú kontaktnú osobu firmy
            foreach ($internship->contactPersons as $contactPerson) {

                if (empty($contactPerson->email)) {
                    continue;
                }

                $contactUrl = "{$frontendUrl}/login?redirect=" . urlencode($redirect) . "&email=" . urlencode($contactPerson->email);

                $contactData = [
                    'name' => $contactPerson->name . ' ' . $contactPerson->surname,
                    'student' => $student->name . ' ' . $student->surname,
                    'company' => $company->name,
                    'status' => $status,
                    'url' => $contactUrl,
                ];

                Mail::send('emails.internship-status-change', $contactData, function ($message) use ($contactPerson) {
                    $message->to($contactPerson->email);
                    $message->subject('Zmena stavu praxe študenta');
                });
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Chyba pri odosielaní emailu o zmene stavu praxe: ' . $e->getMessage());
            return false;
        }
    }
}
