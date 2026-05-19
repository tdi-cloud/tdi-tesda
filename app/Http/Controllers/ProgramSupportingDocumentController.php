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
            // 'document_type'   => ['required', 'string', 'in:Memorandum,Circular,Order,Resolution,Department Order,Executive Order,Administrative Order,Other'],
            'document_type'   => ['required', 'string'],
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

    public function main_index(Request $request)
    {
        $query = ProgramSupportingDocument::with('program');
 
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('document_number', 'like', "%{$search}%")
                  ->orWhere('program_code', 'like', "%{$search}%")
                  ->orWhereHas('program', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }
 
        // Filter: document_type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
 
        // Filter: document_series (year)
        if ($request->filled('document_series')) {
            $query->where('document_series', $request->document_series);
        }
 
        // Filter: program_id
        if ($request->filled('program_id')) {
            $query->where('program_id', $request->program_id);
        }
 
        // Filter: origin
        if ($request->filled('origin')) {
            $query->where('origin', $request->origin);
        }
 
        $documents = $query->latest()->paginate(15)->withQueryString();
 
        // For filter dropdowns
        $documentTypes = ProgramSupportingDocument::select('document_type')
            ->distinct()->orderBy('document_type')->pluck('document_type');
 
        $documentSeries = ProgramSupportingDocument::select('document_series')
            ->distinct()->orderByDesc('document_series')->pluck('document_series');
 
        $origins = ProgramSupportingDocument::select('origin')
            ->whereNotNull('origin')->distinct()->orderBy('origin')->pluck('origin');
 
        $programs = Program::orderBy('title')->get(['id', 'title', 'program_code']);
 
        return view('supporting-docs.supporting-documents', compact(
            'documents', 'documentTypes', 'documentSeries', 'origins', 'programs'
        ));
    }


}
