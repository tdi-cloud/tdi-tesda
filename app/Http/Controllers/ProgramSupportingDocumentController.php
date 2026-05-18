<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramSupportingDocument;
use Illuminate\Http\Request;

class ProgramSupportingDocumentController extends Controller
{
    /**
     * Show the full page view (Blade).
     */
    public function show(Program $program)
    {
        return view('programs.supporting-documents.index', compact('program'));
    }
 
    /**
     * Return all documents for a program as JSON (called by fetchDocs() via AJAX).
     */
    public function index(Program $program)
    {
        return response()->json([
            'data' => $program->supportingDocuments()->latest()->get(),
        ]);
    }
 
    /**
     * Store a new supporting document (AJAX POST).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id'      => ['required', 'integer', 'exists:programs,id'],
            'program_code'    => ['nullable', 'string', 'max:255'],
            'document_type'   => ['required', 'string', 'in:Memorandum,Circular,Order,Resolution,Department Order,Executive Order,Administrative Order,Other'],
            'subject'         => ['required', 'string', 'max:500'],
            'document_series' => ['required', 'integer', 'digits:4', 'min:1900', 'max:' . (date('Y') + 5)],
            'origin'          => ['nullable', 'string', 'max:255'],
            'document_number' => ['required', 'string', 'max:255'],
            'date_issued'     => ['nullable', 'date'],
            'link'            => ['nullable', 'url', 'max:2048'],
        ]);
 
        $document = ProgramSupportingDocument::create($validated);
 
        return response()->json([
            'message'  => 'Supporting document saved successfully.',
            'document' => $document,  
        ], 201);
    }
 
    /**
     * Delete a supporting document (AJAX DELETE).
     */
    public function destroy(ProgramSupportingDocument $supportingDocument)
    {
        $supportingDocument->delete();
 
        return response()->json([
            'message' => 'Document deleted successfully.',
        ]);
    }
}
