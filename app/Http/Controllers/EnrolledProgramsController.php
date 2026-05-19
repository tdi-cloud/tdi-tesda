<?php

// namespace App\Http\Controllers\Employee;
namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Batch;
use App\Models\Program;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\ResourceSpeaker;
use App\Models\CoverPage;
use App\Models\ProgramSupportingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EnrolledProgramsController extends Controller
{
    /**
     * Show the employee's enrollment detail for a single program/batch.
     *
     * URL: /my-programs/{participant}
     */
    public function show(Participant $participant)
    {
        // Security: make sure the logged-in employee owns this participant record
        $employee = Auth::user()->employee; // adjust to however you resolve the employee
        abort_if($participant->empcode !== $employee->EMPCODE, 403);
 
        $batch   = Batch::findOrFail($participant->batch_id);
        $program = Program::where('program_code', $batch->program_code)->firstOrFail();
 
        // Related data
        $speakers       = ResourceSpeaker::where('program_code', $program->program_code)->get();
        $requirements   = Requirement::where('program_code', $program->program_code)->get();
        $submissions    = Submission::where('participant_id', $participant->id)->get();
        $coverPage      = CoverPage::where('program_id', $program->id)->latest()->first();
        $supportingDocs = ProgramSupportingDocument::where('program_id', $program->id)->get();
 
        return view('enrolled.new-view-program', compact(
            'participant',
            'batch',
            'program',
            'employee',
            'speakers',
            'requirements',
            'submissions',
            'coverPage',
            'supportingDocs',
        ));
    }
 
    /**
     * Store a new requirement submission.
     *
     * URL: POST /my-programs/submissions
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'batch_id'       => 'required|exists:batches,id',
            'program_code'   => 'required|string',
            'requirement_id' => 'required|exists:requirements,id',
            'file'           => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes'          => 'nullable|string|max:1000',
        ]);
 
        // Security: verify ownership
        $employee    = Auth::user()->employee;
        $participant = Participant::findOrFail($request->participant_id);
        abort_if($participant->empcode !== $employee->EMPCODE, 403);
 
        // Check if already submitted (and not rejected)
        $existing = Submission::where('participant_id', $request->participant_id)
            ->where('requirement_id', $request->requirement_id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
 
        if ($existing) {
            return back()->with('error', 'You have already submitted this requirement.');
        }
 
        // Store file
        $path = $request->file('file')->store(
            'submissions/' . $request->program_code . '/' . $request->participant_id,
            'public'
        );
 
        Submission::create([
            'participant_id' => $request->participant_id,
            'batch_id'       => $request->batch_id,
            'program_code'   => $request->program_code,
            'requirement_id' => $request->requirement_id,
            'status'         => 'pending',
            'file_path'      => $path,
            'notes'          => $request->notes,
            'submitted_at'   => now(),
        ]);
 
        return back()->with('success', 'Requirement submitted successfully! It is now under review.');
    }
 
    /**
     * Download certificate (basic stub — replace with actual PDF generation).
     *
     * URL: GET /my-programs/{participant}/certificate
     */
    public function downloadCertificate(Participant $participant)
    {
        $employee = Auth::user()->employee;
        abort_if($participant->empcode !== $employee->EMPCODE, 403);
 
        // TODO: Generate and return the certificate PDF here.
        // Example using a PDF library (e.g. barryvdh/laravel-dompdf):
        //
        // $batch   = Batch::find($participant->batch_id);
        // $program = Program::where('program_code', $batch->program_code)->first();
        // $pdf = PDF::loadView('certificates.template', compact('participant', 'program', 'batch', 'employee'));
        // return $pdf->download("Certificate_{$employee->LASTNAME}.pdf");
 
        return back()->with('error', 'Certificate generation is not yet configured.');
    }

    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);

        // delete physical file
        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        // delete database record
        $submission->delete();

        return back()->with('success', 'Submission deleted successfully.');
    }
}
