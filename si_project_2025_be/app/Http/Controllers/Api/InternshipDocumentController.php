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

    public function verifyDocument($internshipId, $documentId)
    {
        $document = Document::where('internships_id', $internshipId)
            ->where('document_id', $documentId)
            ->firstOrFail();
        
        $document->is_verified = true;
        $document->save();

        return response()->json(['message' => 'Dokument bol potvrdený.']);
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

    public function generateContractPdf($id)
    {
        $internship = new InternshipResource(Internship::findOrFail($id));
        $pdf = PDF::loadView('pdf.contract', compact('internship'));
        return $pdf->download('dohoda-o-praxi.pdf');
    }
}
