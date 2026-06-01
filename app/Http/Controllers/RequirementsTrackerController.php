<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requirement;
use App\Models\Participant;
use App\Models\Batch;
use App\Models\employees;
use App\Models\Submission;
use Carbon\Carbon;

class RequirementsTrackerController extends Controller
{
    public function index()
    {
        // Kunin lahat ng requirement titles para sa filter dropdown
        $requirementTitles = Requirement::select('title')
        ->distinct()
        ->orderBy('title')
        ->get()
        ->map(fn($r) => (object)[
            'id'    => $r->title,   // use title as the filter value
            'title' => $r->title,
        ]);
 
        // Kunin lahat ng unique offices ng mga employees
        $offices = employees::select('OFFICE')
            ->distinct()
            ->whereNotNull('OFFICE')
            ->where('OFFICE', '!=', '')
            ->orderBy('OFFICE')
            ->pluck('OFFICE');
 
        // Kunin lahat ng program codes na may requirements
        $programCodes = Requirement::select('program_code')->distinct()->pluck('program_code');
 
        return view('monitoring.requirements-tracker.index', compact('requirementTitles', 'offices', 'programCodes'));
    }
 
    public function getData(Request $request)
    {
        // Base query: lahat ng participants na may enrollment sa isang batch
        // at hindi absent sa attendance
        $query = Participant::query()
            ->with([
                'batch.program',
                'employee',
                'submissions.requirement',
            ])
            ->where('attendance', '!=', 'Absent') // huwag isama ang absent
            ->whereHas('employee'); // kailangan may employee record
 
        // Filter: empcode / pangalan ng empleyado
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('FIRSTNAME', 'like', "%{$search}%")
                  ->orWhere('LASTNAME', 'like', "%{$search}%")
                  ->orWhere('EMPCODE', 'like', "%{$search}%");
            });
        }
 
        // Filter: office ng empleyado
        if ($request->filled('office')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('OFFICE', $request->office);
            });
        }
 
        // Filter: requirement title (requirement_id)
        if ($request->filled('requirement_id')) {
            $requirementTitle = $request->requirement_id; // now holds the title string
            $query->whereHas('batch', function ($q) use ($requirementTitle) {
                $q->whereHas('program', function ($q2) use ($requirementTitle) {
                    $q2->whereHas('requirements', function ($q3) use ($requirementTitle) {
                        $q3->where('title', $requirementTitle);
                    });
                });
            });
        }
 
        // Filter: submission status (submitted / not_submitted)
        if ($request->filled('submission_status') && $request->filled('requirement_id')) {
            $requirementTitle = $request->requirement_id; // it's actually a title string

            // Resolve the title to actual requirement IDs first
            $requirementIds = Requirement::where('title', $requirementTitle)->pluck('id');

            if ($request->submission_status === 'submitted') {
                $query->whereHas('submissions', function ($q) use ($requirementIds) {
                    $q->whereIn('requirement_id', $requirementIds);
                });
            } elseif ($request->submission_status === 'not_submitted') {
                $query->whereDoesntHave('submissions', function ($q) use ($requirementIds) {
                    $q->whereIn('requirement_id', $requirementIds);
                });
            }
        }
 
        // Filter: date range (batay sa date_end ng batch)
        if ($request->filled('date_from')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('date_end', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('date_end', '<=', $request->date_to);
            });
        }
 
        $participants = $query->get();
 
        // I-build ang rows ng data
        $rows = [];
 
        foreach ($participants as $participant) {
            $batch = $participant->batch;
            if (!$batch || !$batch->program) continue;
 
            $employee = $participant->employee;
            if (!$employee) continue;
 
            // Kunin lahat ng requirements ng program
            $programRequirements = Requirement::where('program_code', $batch->program_code)->get();
 
            // Kung may specific requirement filter, gamitin lang ito
            if ($request->filled('requirement_id')) {
                $programRequirements = $programRequirements->where('title', $request->requirement_id);
            }
 
            foreach ($programRequirements as $requirement) {
                // Kunin ang submission ng participant para sa requirement na ito
                $submission = $participant->submissions
                    ->where('requirement_id', $requirement->id)
                    ->first();
 
                // Kalkulahin ang due date
                $dueDate  = $requirement->getDueDateForBatch($batch);
                $isOverdue = $dueDate && Carbon::now()->gt($dueDate) && !$submission;
 
                // Submission status filter (para sa walang specific requirement filter)
                if ($request->filled('submission_status') && !$request->filled('requirement_id')) {
                    if ($request->submission_status === 'submitted' && !$submission) continue;
                    if ($request->submission_status === 'not_submitted' && $submission) continue;
                }
 
                $rows[] = [
                    'participant_id'   => $participant->id,
                    'empcode'          => $employee->EMPCODE,
                    'fullname'         => trim($employee->LASTNAME . ', ' . $employee->FIRSTNAME . ' ' . $employee->MI),
                    'office'           => $employee->OFFICE,
                    'division'         => $employee->{'OFFICE/DIVISION'},
                    'position'         => $employee->POSITION,
                    'program_code'     => $batch->program_code,
                    'program_title'    => $batch->program->title ?? '-',
                    'program_id'    => $batch->program->id,
                    'batch'            => $batch->batch,
                    'batch_date_start' => $batch->date_start,
                    'batch_date_end'   => $batch->date_end,
                    'requirement_id'   => $requirement->id,
                    'requirement_title'=> $requirement->title,
                    'required'         => $requirement->required,
                    'due_date'         => $dueDate ? $dueDate->toDateString() : null,
                    'is_overdue'       => $isOverdue,
                    'submitted'        => $submission ? true : false,
                    'submission_status'=> $submission ? $submission->status : null,
                    'submitted_at'     => $submission ? optional($submission->submitted_at)->format('Y-m-d') : null,
                    'submission_file'  => $submission ? $submission->file_path : null,
                    'submission_notes' => $submission ? $submission->notes : null,
                ];
            }
        }
 
        // Overdue filter
        if ($request->filled('overdue') && $request->overdue === '1') {
            $rows = array_filter($rows, fn($r) => $r['is_overdue']);
            $rows = array_values($rows);
        }
 
        // Sort: overdue muna, tapos not submitted, tapos submitted; alphabetical within group
        usort($rows, function ($a, $b) {
            if ($b['is_overdue'] !== $a['is_overdue']) return $b['is_overdue'] <=> $a['is_overdue'];
            if ($b['submitted'] !== $a['submitted']) return $a['submitted'] <=> $b['submitted'];
            return strcmp($a['fullname'], $b['fullname']);
        });
 
        // Summary (computed from ALL rows before slicing)
        $summary = [
            'total'         => count($rows),
            'submitted'     => count(array_filter($rows, fn($r) => $r['submitted'])),
            'not_submitted' => count(array_filter($rows, fn($r) => !$r['submitted'])),
            'overdue'       => count(array_filter($rows, fn($r) => $r['is_overdue'])),
        ];
 
        // ── PAGINATION ──────────────────────────────────────────────────────────
        $perPage  = max(1, (int) $request->input('per_page', 25));
        $page     = max(1, (int) $request->input('page', 1));
        $total    = count($rows);
        $lastPage = $total > 0 ? (int) ceil($total / $perPage) : 1;
        $page     = min($page, $lastPage); // clamp so we never go past last page
        $offset   = ($page - 1) * $perPage;
 
        $pagedRows = array_slice($rows, $offset, $perPage);
 
        $pagination = [
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => $lastPage,
            'from'         => $total > 0 ? $offset + 1 : 0,
            'to'           => min($offset + $perPage, $total),
        ];
        // ────────────────────────────────────────────────────────────────────────
 
        return response()->json([
            'rows'       => $pagedRows, // current page only
            'all_rows'   => $rows,      // full set for CSV export
            'summary'    => $summary,
            'pagination' => $pagination,
        ]);
    }
}
