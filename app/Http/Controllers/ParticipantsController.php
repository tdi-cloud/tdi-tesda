<?php

namespace App\Http\Controllers;

use App\Models\AbsentJustification;
use App\Models\employees;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ParticipantsController extends Controller
{
    public function bulkAdd(Request $request){

        $request->validate(['empcodes' => 'required|string']);

        $codes = collect(preg_split('/[\s,;\n]+/', trim($request->empcodes)))
            ->map(fn($c) => trim($c))
            ->filter()
            ->unique()
            ->values();

        $employees = employees::whereIn('EMPCODE', $codes)->get()->keyBy('EMPCODE');

        $inserted = collect();
        $skipped  = collect();
        $notFound = collect();

        $maxOrder = Participant::where('batch_id', $request->batch_id)
            ->max('sort_order') ?? 0;

        foreach ($codes as $code) {
            if (!$employees->has($code)) {
                $notFound->push($code);
                continue;
            }
            $exists = Participant::where('empcode', $code)
            ->where('batch_id', $request->batch_id)
            ->exists();
            if ($exists) {
                $skipped->push($code);
                continue;
            }
            $maxOrder++;
            Participant::create([
                'empcode' => $code,
                'batch_id' => $request->batch_id,
                'attendance' => $request->attendance,
                'hours' => $request->hours,
                'requirements' => $request->requirements,
                'added_by' => $request->added_by,
                'sort_order' => $maxOrder,
                ]);
            $inserted->push($code);
        }

        return response()->json([
            'success'  => $inserted->count() . ' participant(s) added successfully.',
            '
            '  => $skipped->count() > 0
                ? 'Skipped ' . $skipped->count() . ' already existing: ' . $skipped->join(', ')
                : null,
            'notfound' => $notFound->count() > 0
                ? 'Not found in employees: ' . $notFound->join(', ')
                : null,
        ]);

    }


    public function destroy($id){
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    public function clearByBatch($id){
        Participant::where('batch_id', $id)->delete();

         return response()->json([
        'success' => true,
        'message' => 'All participants deleted successfully'
    ]);
    }


    public function saveAttendance(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'status' => 'required|in:Complete,Absent,Pending',
            'hours' => 'nullable|integer|min:0',
            'file' => 'nullable|mimes:pdf|max:2048'
        ]);

        $participant = Participant::findOrFail($request->participant_id);

        // Set attendance
        $participant->attendance = $request->status;

        if ($request->status === 'Complete') {
            $participant->hours = $request->hours;
            
            // ❌ REMOVE JUSTIFICATION FILE FROM PARTICIPANT
            $participant->save();

            // Optional: delete existing justification
            if ($participant->justification) {
                Storage::disk('public')->delete($participant->justification->file_path);
                $participant->justification()->delete();
            }
        }

        if ($request->status === 'Absent') {
            $participant->hours = null;

            $participant->save();

            // ✅ HANDLE FILE IN SEPARATE TABLE
            if ($request->hasFile('file')) {

                // delete old justification if exists
                if ($participant->justification) {
                    Storage::disk('public')->delete($participant->justification->file_path);
                    $participant->justification()->delete();
                }

                $filePath = $request->file('file')->store('attendance_files', 'public');

                AbsentJustification::create([
                    'participant_id' => $participant->id,
                    'file_path' => $filePath
                ]);
            }
        }

        if ($request->status === 'Pending') {
            $participant->hours = null;

            $participant->save();
        }

        return response()->json([
            'success' => true,
            'data' => $participant->load('justification'),
        ]);
    }

    public function setAllHours(Request $request)
    {
        Participant::where('batch_id', $request->batch_id)
        ->where('attendance', '!=', 'Absent')
        ->update([
            'hours' => $request->hours,
            'attendance' => 'Complete'
        ]);

        return response()->json([
            'message' => 'Updated by batch successfully'
        ]);
    }

    public function moveOrder(Request $request, $id)
    {
        $participant = Participant::findOrFail($id);

        $direction = $request->direction;

        $current = $participant->sort_order;

        if ($direction === 'up') {
            $swap = Participant::where('batch_id', $participant->batch_id)
                ->where('sort_order', '<', $current)
                ->orderBy('sort_order', 'desc')
                ->first();
        } else {
            $swap = Participant::where('batch_id', $participant->batch_id)
                ->where('sort_order', '>', $current)
                ->orderBy('sort_order', 'asc')
                ->first();
        }

        if (!$swap) {
            return response()->json(['success' => false, 'message' => 'No swap target']);
        }

        // swap order
        $temp = $participant->sort_order;
        $participant->sort_order = $swap->sort_order;
        $swap->sort_order = $temp;

        $participant->save();
        $swap->save();

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'empcodes' => 'required|array',
            'batch_id' => 'required|exists:batches,id',
        ]);

        $batchId = $request->batch_id;

        // Get existing empcodes for this batch
        $existing = Participant::where('batch_id', $batchId)
            ->whereIn('empcode', $request->empcodes)
            ->pluck('empcode')
            ->toArray();

        // Filter out duplicates
        $newCodes = array_diff($request->empcodes, $existing);

        if (empty($newCodes)) {
            return response()->json([
                'success' => false,
                'message' => 'All selected employees already exist in this batch'
            ]);
        }

        // Get last sort order
        $lastOrder = Participant::where('batch_id', $batchId)->max('sort_order') ?? 0;

        $data = [];

        foreach (array_values($newCodes) as $index => $code) {
            $data[] = [
                'batch_id' => $batchId,
                'empcode' => $code,
                'attendance' => 'Pending',
                'hours' => 0,
                'requirements' => 'required',
                'added_by' => Auth::user()?->empcode ?? 'unknown',
                'sort_order' => $lastOrder + $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Participant::insert($data);

        return response()->json([
            'success' => true,
            'message' => count($data) . ' participant(s) added successfully'
        ]);
    }

    public function show(string $empcode)
    {
        // ── 1. Employee basic info ────────────────────────────────────────────
        $employee = DB::table('employees')
            ->where('EMPCODE', $empcode)
            ->first();
 
        if (! $employee) {
            return response()->json(['error' => 'Employee not found.'], 404);
        }
 
        // ── 2. All participant rows for this employee (across all batches) ────
        $participantRows = DB::table('participants as p')
            ->join('batches as b',   'b.id',           '=', 'p.batch_id')
            ->join('programs as pr', 'pr.program_code', '=', 'b.program_code')
            ->where('p.empcode', $empcode)
            ->select(
                'p.id          as participant_id',
                'p.batch_id',
                'p.attendance',
                'p.hours',
                'p.requirements',
                'b.program_code',
                'b.batch',
                'b.status      as batch_status',
                'b.date_start',
                'b.date_end',
                'b.hours       as batch_hours',
                'pr.title      as program_title',
                'pr.category',
                'pr.modality',
            )
            ->orderByDesc('b.date_start')
            ->get();
 
        // ── 3. Program-level stats ────────────────────────────────────────────
        $programsAttended = $participantRows->count();
 
        $programsCompleted = $participantRows->filter(function ($row) {
            return strtolower($row->batch_status) === 'completed'
                && strtolower($row->attendance) !== 'absent'
                && strtolower($row->attendance) !== 'pending';
        })->count();
 
        $totalHours = $participantRows->sum(function ($row) {
            $attendance  = strtolower($row->attendance ?? '');
            $batchStatus = strtolower($row->batch_status ?? '');

            // Count hours ONLY if completed and not absent/dropped
            if (
                $batchStatus === 'completed' &&
                $attendance !== 'absent' &&
                $attendance !== 'pending'
            ) {
                return $row->hours ?? $row->batch_hours ?? 0;
            }

            return 0;
        });
 
        // ── 4. Collect distinct program codes this employee is in ─────────────
        $participantIds  = $participantRows->pluck('participant_id');
        $programCodes    = $participantRows->pluck('program_code')->unique()->values();
 
        // ── 5. Load ALL requirements for those programs ───────────────────────
        //
        // KEY FIX: we query requirements directly by program_code, NOT via
        // submissions. This means we see every requirement — even those with
        // no submission row yet (which would appear as "pending").
        //
        $allRequirements = DB::table('requirements')
            ->whereIn('program_code', $programCodes)
            ->get()
            ->keyBy('id');   // keyed by requirement id for fast lookup
 
        // ── 6. Load existing submissions for this employee ───────────────────
        //
        // We join back to participants so we can match participant_id correctly.
        //
        $allSubmissions = DB::table('submissions as s')
            ->whereIn('s.participant_id', $participantIds)
            ->select(
                's.id              as submission_id',
                's.participant_id',
                's.program_code',
                's.batch_id',
                's.requirement_id',
                's.status',
                's.file_path',
                's.notes',
                's.remarks',
                's.submitted_at',
                's.reviewed_at',
                's.reviewed_by',
            )
            ->get()
            // Index by "participant_id:requirement_id" for O(1) lookup
            ->keyBy(fn($s) => "{$s->participant_id}:{$s->requirement_id}");
 
        // ── 7. Build enrolled programs list with merged requirements ──────────
        $enrolledPrograms = $participantRows->map(function ($row) use ($allRequirements, $allSubmissions) {
 
            $statusRaw   = strtolower($row->attendance   ?? '');
            $batchStatus = strtolower($row->batch_status ?? '');
 
            if ($statusRaw === 'absent') {
                $uiStatus = 'Absent';
            } elseif ($batchStatus === 'completed') {
                $uiStatus = 'Completed';
            } elseif ($batchStatus === 'upcoming') {
                $uiStatus = 'Upcoming';
            } else {
                $uiStatus = 'Unkown';
            }
 
            // All requirements that belong to this program
            $progRequirements = $allRequirements->filter(
                fn($r) => $r->program_code === $row->program_code
            );
 
            // For each requirement, look up the submission (if any)
            $requirements = $progRequirements->map(function ($req) use ($row, $allSubmissions) {
 
                $key        = "{$row->participant_id}:{$req->id}";
                $submission = $allSubmissions->get($key);
 
                // If there is no submission row, treat as 'Pending'
                // Statuses: Pending | Approved | Revised | Rejected
                $status      = $submission ? $submission->status : 'Pending';
                $isSubmitted = $submission && $status === 'Approved';
 
                return [
                    'requirement_id' => $req->id,
                    'title'          => $req->title,
                    'description'    => $req->description,
                    'required'       => $req->required,      // 'yes' / 'no'
                    'day_due'        => $req->day_due,
                    'month_due'      => $req->month_due,
                    // submission fields (null when no submission row exists)
                    'status'         => $status,
                    'is_submitted'   => $isSubmitted,
                    'submitted_at'   => $submission?->submitted_at,
                    'reviewed_at'    => $submission?->reviewed_at,
                    'reviewed_by'    => $submission?->reviewed_by,
                    'remarks'        => $submission?->remarks,
                    'file_path'      => $submission?->file_path,
                ];
            })->values()->toArray();
 
            $reqTotal     = count($requirements);
            $reqSubmitted = collect($requirements)->where('is_submitted', true)->count();
            $reqPending   = $reqTotal - $reqSubmitted;
 
            return [
                'participant_id' => $row->participant_id,
                'program_code'   => $row->program_code,
                'title'          => $row->program_title,
                'batch'          => $row->batch,
                'date_start'     => $row->date_start,
                'date_end'       => $row->date_end,
                'hours'          => $row->hours ?? $row->batch_hours,
                'status'         => $uiStatus,
                'modality'       => $row->modality,
                'req_total'      => $reqTotal,
                'req_submitted'  => $reqSubmitted,
                'req_pending'    => $reqPending,
                'requirements'   => $requirements,
            ];
        })->values();
 
        // ── 8. Global submission totals (derived from merged requirements) ────
        $allMergedReqs = $enrolledPrograms->flatMap(fn($p) => $p['requirements']);
 
        $submissionsSubmitted = $allMergedReqs->where('status', 'Approved')->count();
        $submissionsTotal     = $allMergedReqs->count();
        $submissionsPending   = $submissionsTotal - $submissionsSubmitted;
        $completionRate       = $submissionsTotal > 0
            ? round(($submissionsSubmitted / $submissionsTotal) * 100)
            : 0;
 
        // ── 9. Average rating ─────────────────────────────────────────────────
        $avgRating = $programsAttended > 0
        ? round(($programsCompleted / $programsAttended) * 100, 2)
        : 0;// wire up a ratings table here when ready
 
        // ── 10. Assemble response ─────────────────────────────────────────────
        $fullName = trim("{$employee->FIRSTNAME} {$employee->MI} {$employee->LASTNAME}");
        $initials = strtoupper(
            substr($employee->FIRSTNAME, 0, 1) . substr($employee->LASTNAME, 0, 1)
        );
 
        return response()->json([
            'employee' => [
                'empcode'   => $employee->EMPCODE,
                'full_name' => $fullName,
                'initials'  => $initials,
                'position'  => $employee->POSITION,
                'division'  => $employee->{'OFFICE/DIVISION'},
                'office'    => $employee->OFFICE,
                'section'   => $employee->SECTION,
                'unit'      => $employee->UNIT,
                'region'    => $employee->REGION,
                'sex'       => $employee->SEX,
                'sg'        => $employee->SG,
                'status'    => $employee->{'PLANTILLA STATUS'},
            ],
            'stats' => [
                'programs_attended'  => $programsAttended,
                'programs_completed' => $programsCompleted,
                'total_hours'        => $totalHours,
                'avg_rating'         => $avgRating,
            ],
            'submissions' => [
                'submitted'       => $submissionsSubmitted,
                'pending'         => $submissionsPending,
                'total'           => $submissionsTotal,
                'completion_rate' => $completionRate,
            ],
            'enrolled_programs' => $enrolledPrograms,
        ]);
    }


}
