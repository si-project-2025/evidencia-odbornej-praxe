<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InternshipResource;
use App\Models\Internship;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InternshipDocumentController extends Controller
{
    public function index($internshipId)
    {
        // vytvoriť DocumentResource v app/Http/Resources a ten používať ako aj InternshipResource
    }

    public function store(Request $request, $internshipId)
    {
        // uložiť dokument do storage
        // uložiť dokument do DB tabuľky documents s cestou k nemu
    }

    public function destroy($internshipId, $documentId)
    {
        // vymazať so storage
        //vymazať z DB
    }

    public function generateContractPdf($id)
    {
        $internship = new InternshipResource(Internship::findOrFail($id));
        $pdf = PDF::loadView('pdf.contract', compact('internship'));
        return $pdf->download('dohoda-o-praxi.pdf');
    }
}
