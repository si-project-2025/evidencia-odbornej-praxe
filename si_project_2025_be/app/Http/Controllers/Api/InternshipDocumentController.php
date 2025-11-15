<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternshipDocumentController extends Controller
{
    public function index($internshipId)
    {
        $internship = Internship::with('documents')->findOrFail($internshipId);

        $internship->documents->each(function ($doc) {
            $doc->file_url = Storage::disk('public')->url($doc->file_name);
        });

        return response()->json($internship->documents);
    }

    public function store(Request $request, $internshipId)
    {
        $request->validate([
            'file' => 'required|file|max:5120',   // 5 MB
            'type' => 'nullable|string|max:50',
        ]);

        $internship = Internship::findOrFail($internshipId);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();

        $path = $file->storeAs(
            'internship-documents/' . $internshipId,
            $originalName,
            'public'
        );

        $document = $internship->documents()->create([
            'type'        => $request->input('type', 'Dokument'),
            'file_name'   => $path,
            'is_verified' => false,
        ]);

        $document->file_url = Storage::disk('public')->url($document->file_name);

        return response()->json($document, 201);
    }

    public function destroy($internshipId, $documentId)
    {
        $document = Document::where('internships_id', $internshipId)
            ->where('document_id', $documentId)
            ->firstOrFail();

        if (Storage::disk('public')->exists($document->file_name)) {
            Storage::disk('public')->delete($document->file_name);
        }

        $document->delete();

        return response()->json(['message' => 'Dokument bol odstránený.']);
    }

    public function generateContractPdf($id)
    {
        $internship = new InternshipResource(Internship::findOrFail($id));
        $pdf = PDF::loadView('pdf.contract', compact('internship'));
        return $pdf->download('dohoda-o-praxi.pdf');
    }
}
