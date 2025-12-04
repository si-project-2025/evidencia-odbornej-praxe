<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tfpdf\Fpdi;

class InternshipDocumentController extends Controller
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
        $request->validate([
            'file' => 'required|file|max:5120',
            'type' => 'nullable|string|max:50',
        ]);

        $internship = Internship::findOrFail($internshipId);

        $file = $request->file('file');

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

    public function download($id, $documentId)
    {
        $internship = Internship::findOrFail($id);

        // over, že prihlásený user má prístup
        if (auth()->id() !== $internship->users_id && auth()->id() !== $internship->garant_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $document = Document::where('internships_id', $id)
            ->where('document_id', $documentId)
            ->firstOrFail();

        if (!Storage::disk('local')->exists($document->file_name)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Storage::disk('local')->download($document->file_name);
    }

    // verzia pre contract.blade.php
   /* public function generateContractPdf($id)
    {
        $internship = new InternshipResource(Internship::findOrFail($id));
        $pdf = PDF::loadView('pdf.contract', compact('internship'));
        return $pdf->download('dohoda-o-praxi.pdf');
    }*/

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
            'contactPersons'
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

        $templatePath = storage_path('app/templates/dohoda.pdf');

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($templatePath);

        // Prvá strana
        $pdf->AddPage();
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);

        // Font
        $pdf->AddFont('LiberationSerif', '', 'LiberationSerif-Regular.ttf', true);
        $pdf->SetFont('LiberationSerif','',11);
        $pdf->SetTextColor(0,0,128);


        // Firma
        $pdf->SetXY(73, 82.8);
        $pdf->Write(5,
            $this->cleanJoin([
                $internship->company->name,
                $companyFullAddress
            ])
        );

        // Kontaktná osoba
        $contact = $internship->contactPersons->first();
        if ($contact) {
            $pdf->SetXY(60, 87.6);
            $pdf->Write(5, $contact->name . ' ' . $contact->surname);

            //keď bude v databáze aj pozícia kontaktnej osoby tak
            //vymazať predošlé 2 riadky a odkomentovať nasledovné:
           /*
           $pdf->SetXY(60, 87.6);
           $pdf->Write(5,
                $this->cleanJoin([
                    $contact->name . ' ' . $contact->surname,
                    $contact->position
                ])
            );*/
        }

        // Študent
        $pdf->SetXY(30, 105.5);
        $pdf->Write(5,
            $this->cleanJoin([
                trim($internship->student->name . ' ' . $internship->student->surname),
                $studentFullAddress
            ])
        );

        // Študent – kontakt
        $pdf->SetXY(30, 114.4);
        $pdf->Write(5,
            $this->cleanJoin([
                $internship->student->email,
                $internship->student->phone_number
            ])
        );

        // Študijný program
        $pdf->SetXY(80, 123.7);
        $pdf->Write(5, $internship->student->study_program);

        // Koniec praxe
        $pdf->SetXY(60, 162.8);
        $pdf->Write(5, $internship->end_at ? $internship->end_at->format('d.m.Y') : '');

        // Garant
        $pdf->SetXY(79, 197.5);
        $pdf->Write(5, $internship->garant->name . ' ' . $internship->garant->surname );

        // Garant – kontakt
        $pdf->SetXY(47, 202.4);
        $pdf->Write(5,
            $this->cleanJoin([
                $internship->garant->email,
                $internship->garant->phone_number
            ])
        );

        // Garant – meno
        $pdf->SetXY(101, 217.1);
        $pdf->Write(5, $internship->garant->name . ' ' . $internship->garant->surname );

        // Študent
        $pdf->SetXY(72, 222.1);
        $pdf->Write(5, $internship->student->name . ' ' . $internship->student->surname);


        // Druhá strana
        $pdf->AddPage();
        //$this->drawGrid($pdf);
        $template = $pdf->importPage(2);
        $pdf->useTemplate($template);

        // Kontaktná osoba
        if ($contact) {
            $pdf->SetXY(40, 34.2);
            $pdf->Write(5, $contact->name . ' ' . $contact->surname);
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="dohoda-o-praxi.pdf"');

    }


}
