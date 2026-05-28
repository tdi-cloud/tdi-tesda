<?php

namespace App\Http\Controllers;

use App\Models\ForeignProgram;
use Illuminate\Http\Request;

class ForeignProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = ForeignProgram::withCount('participants');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('program_title', 'like', "%{$search}%")
                  ->orWhere('organizing_sponsor', 'like', "%{$search}%")
                  ->orWhere('attached_agency', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Semester filter: 1 = Jan–Jun, 2 = Jul–Dec (based on program_start month)
        if ($semester = $request->input('semester')) {
            if ($semester == 1) {
                $query->whereMonth('program_start', '>=', 1)
                      ->whereMonth('program_start', '<=', 6);
            } elseif ($semester == 2) {
                $query->whereMonth('program_start', '>=', 7)
                      ->whereMonth('program_start', '<=', 12);
            }
        }

        // Year filter (based on program_start year)
        if ($year = $request->input('year')) {
            $query->whereYear('program_start', $year);
        }

        // Organizing sponsor filter (partial match)
        if ($sponsor = $request->input('sponsor')) {
            $query->where('organizing_sponsor', 'like', "%{$sponsor}%");
        }

        // Embassy deadline range filter
        if ($deadlineFrom = $request->input('deadline_from')) {
            $query->whereDate('embassy_deadline', '>=', $deadlineFrom);
        }
        if ($deadlineTo = $request->input('deadline_to')) {
            $query->whereDate('embassy_deadline', '<=', $deadlineTo);
        }

        // Interview date range filter
        if ($interviewFrom = $request->input('interview_from')) {
            $query->whereDate('interview_date', '>=', $interviewFrom);
        }
        if ($interviewTo = $request->input('interview_to')) {
            $query->whereDate('interview_date', '<=', $interviewTo);
        }

        // AJAX / JSON request — return paginated JSON
        if ($request->ajax() || $request->wantsJson()) {
            $programs = $query->latest()->paginate(10)->withQueryString();
            return response()->json($programs);
        }

        // Normal page load — return the view with status options for the filter dropdown
        return view('monitoring.fstp.index', [
            'statusOptions' => ForeignProgram::statusOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'program_title'      => 'required|string|max:255',
            'program_start'      => 'required|date',
            'program_end'        => 'required|date|after_or_equal:program_start',
            'slots'              => 'required|integer|min:1',
            'modality'           => 'required|in:in-person,online,hybrid',
            // Online schedule only required for hybrid
            'online_start'       => 'nullable|required_if:modality,hybrid|date',
            'online_end'         => 'nullable|required_if:modality,hybrid|date|after_or_equal:online_start',
            'organizing_sponsor' => 'required|string|max:255',
            'status'             => 'required|in:' . implode(',', array_keys(ForeignProgram::statusOptions())),
            'submission_date'    => 'nullable|date',
            'embassy_deadline'   => 'nullable|date',
            'interview_date'     => 'nullable|date',
            'invited_agencies'   => 'nullable|string',
            'attached_agency'    => 'nullable|string|max:255',
        ]);

        // Ensure inperson fields are always null — program_start/end serve that purpose
        $data['inperson_start'] = null;
        $data['inperson_end']   = null;

        // Clear online schedule if not hybrid
        if ($data['modality'] !== 'hybrid') {
            $data['online_start'] = null;
            $data['online_end']   = null;
        }

        $program = ForeignProgram::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($program, 201);
        }

        return redirect()->route('foreign-programs.index')
                         ->with('success', 'Program added successfully.');
    }

    public function update(Request $request, ForeignProgram $foreignProgram)
    {
        $data = $request->validate([
            'program_title'      => 'required|string|max:255',
            'program_start'      => 'required|date',
            'program_end'        => 'required|date|after_or_equal:program_start',
            'slots'              => 'required|integer|min:1',
            'modality'           => 'required|in:in-person,online,hybrid',
            // Online schedule only required for hybrid
            'online_start'       => 'nullable|required_if:modality,hybrid|date',
            'online_end'         => 'nullable|required_if:modality,hybrid|date|after_or_equal:online_start',
            'organizing_sponsor' => 'required|string|max:255',
            'status'             => 'required|in:' . implode(',', array_keys(ForeignProgram::statusOptions())),
            'submission_date'    => 'nullable|date',
            'embassy_deadline'   => 'nullable|date',
            'interview_date'     => 'nullable|date',
            'invited_agencies'   => 'nullable|string',
            'attached_agency'    => 'nullable|string|max:255',
        ]);

        // Ensure inperson fields are always null — program_start/end serve that purpose
        $data['inperson_start'] = null;
        $data['inperson_end']   = null;

        // Clear online schedule if not hybrid
        if ($data['modality'] !== 'hybrid') {
            $data['online_start'] = null;
            $data['online_end']   = null;
        }

        $foreignProgram->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($foreignProgram->fresh());
        }

        return redirect()->route('foreign-programs.index')
                         ->with('success', 'Program updated successfully.');
    }

    public function destroy(ForeignProgram $foreignProgram)
    {
        $foreignProgram->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['message' => 'Deleted.']);
        }

        return redirect()->route('foreign-programs.index')
                         ->with('success', 'Program deleted.');
    }

    /** Return program data as JSON (for edit/view modal population) */
    public function show(ForeignProgram $foreignProgram)
    {
        return response()->json($foreignProgram);
    }
}