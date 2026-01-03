<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tfpdf\Fpdi;

class DocumentController extends Controller
{
    public function index($internshipId)
    {
        $internship = Internship::with('documents')->findOrFail($internshipId);

        $documents = $internship->documents->map(function ($doc) use ($internshipId) {
            $doc->download_url = route('documents.download', [
                'internshipId' => $internshipId,
                'documentId'   => $doc->document_id,
            ]);
            return $doc;
        });

        return response()->json($documents);
    }

    public function store(Request $request, $internshipId)
    {
        $this->checkPermission($request, $internshipId);

        $request->validate([
            'file' => 'required|file|max:5120',
            'type' => 'nullable|string|max:50',
        ]);

        $internship = Internship::findOrFail($internshipId);

        $file = $request->file('file');
        $type = $request->input('type', 'Dokument');

        if ($internship->documents()->where('type', $type)->exists()) {
            return response()->json([
                'message' => "Dokument typu '{$type}' už existuje.",
            ], 409);
        }

        $originalName = $file->getClientOriginalName();
        $newFileName = auth()->id() . '_' . $originalName;

        $path = $file->storeAs(
            'internship-documents/' . $internshipId,
            $newFileName,
            'local'
        );

        $document = $internship->documents()->create([
            'type'        => $request->input('type', 'Dokument'),
            'file_name'   => $path,
            'is_verified' => false,
        ]);

        return response()->json($document, 201);
    }

    public function destroy($internshipId, $documentId)
    {
        $document = Document::where('internships_id', $internshipId)
            ->where('document_id', $documentId)
            ->firstOrFail();

        if (Storage::disk('local')->exists($document->file_name)) {
            Storage::disk('local')->delete($document->file_name);
        }

        $document->delete();

        return response()->json(['message' => 'Dokument bol odstránený.']);
    }

    public function verifyDocument(Request $request, $internshipId, $documentId)
    {
        $this->checkPermission($request, $internshipId);

        $document = Document::where('internships_id', $internshipId)
            ->where('document_id', $documentId)
            ->firstOrFail();

        $document->is_verified = true;
        $document->save();

        return response()->json(['message' => 'Dokument bol potvrdený.']);
    }

    public function download(Request $request, $id, $documentId)
    {
        $this->checkPermission($request, $id);

        $document = Document::where('internships_id', $id)
            ->where('document_id', $documentId)
            ->firstOrFail();

        if (!Storage::disk('local')->exists($document->file_name)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Storage::disk('local')->download($document->file_name);
    }

    private function cleanJoin(array $items, string $separator = ',    ')
    {
        $filtered = array_filter($items, function ($value) {
            return $value !== null && trim($value) !== '';
        });
        return implode($separator, $filtered);
    }

    public function generateContractPdf($id)
    {
        $internship = Internship::with([
            'company.address',
            'student',
            'garant',
            'contactPerson'
        ])->findOrFail($id);

        $studentAddress = $internship->student->address;

        $studentFullAddress = $studentAddress
            ? $this->cleanJoin([
                $studentAddress->street . ' ' . $studentAddress->house_number,
                $studentAddress->zip_code  . ' ' . $studentAddress->city
            ], ', ')
            : null;

        $companyAddress = $internship->company->address;

        $companyFullAddress = $companyAddress
            ? $this->cleanJoin([
                $companyAddress->street . ' ' . $companyAddress->house_number,
                $companyAddress->zip_code  . ' ' . $companyAddress->city
            ], ', ')
            : null;

        // Načíta PDF šablónu
        $templatePath = resource_path('templates/dohoda_o_odbornej_praxi.pdf');

        $pdf = new Fpdi();
        $pdf->setSourceFile($templatePath);

        $pdf->AddPage();
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);

        // Font
        $pdf->AddFont('LiberationSans', '', 'LiberationSans-Regular.ttf', true);
        $pdf->SetFont('LiberationSans','',10);


        // Firma
        $pdf->SetXY(65, 71.5);
        $pdf->Write(5,
            $this->cleanJoin([
                $internship->company->name,
                $companyFullAddress
            ])
        );

        // Kontaktná osoba
        $contact = $internship->contactPerson;
        if ($contact) {
            $pdf->SetXY(60, 76.3);
            $pdf->Write(5, $contact->name . ' ' . $contact->surname);
        }

        // Študent - meno
        $pdf->SetXY(99, 91.5);
        $pdf->Write(5, $internship->student->name . ' ' . $internship->student->surname);

        // Študent - adresa
        $pdf->SetXY(99, 96.2);
        $pdf->Write(5, $studentFullAddress);

        // Študent – kontakt
        $pdf->SetXY(99, 101);
        $pdf->Write(5,
            $this->cleanJoin([
                $internship->student->email,
                $internship->student->phone_number
            ],', ')
        );

        //Začiatok praxe
        $pdf->SetXY(33, 142.2);
        $pdf->Write(5, $internship->start_at ? $internship->start_at->format('d.m.Y') : '');

        // Koniec praxe
        $pdf->SetXY(75, 142.2);
        $pdf->Write(5, $internship->end_at ? $internship->end_at->format('d.m.Y') : '');

        // Kontaktná osoba
        if ($contact) {
            $pdf->SetXY(42, 262.1);
            $pdf->Write(5, $contact->name . ' ' . $contact->surname);
        }

        // Druhá strana
        $pdf->AddPage();
        $template = $pdf->importPage(2);
        $pdf->useTemplate($template);

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="dohoda-o-praxi.pdf"');

    }

    private function checkPermission(Request $request, int $id)
    {
        $internship = Internship::findOrFail($id);

        if (auth()->check()) {
            if (auth()->id() !== $internship->users_id
                && auth()->id() !== $internship->garant_id
                && auth()->id() !== $internship->company_id
            ) {
                abort(403, 'Unauthorized');
            }
        } else {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
            ]);

            $verification = DB::table('internship_verification_tokens')
                ->where('internships_id', $id)
                ->where('email', $request->email)
                ->first();

            if (
                !$verification ||
                !Hash::check($request->token, $verification->token)
            ) {
                abort(403, 'Invalid token');
            }
        }
    }
}
