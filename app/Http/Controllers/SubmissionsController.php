<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\employees;
use App\Models\Participant;
use App\Models\Requirement;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SubmissionsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'program_code' => 'required',
            'batch_id' => 'required|exists:batches,id',
            'requirement_id' => 'required|exists:requirements,id',
            'notes' => 'nullable|string',
            'file' => 'required|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('submissions', $filename, 'public');

        $submission = Submission::create([
            'participant_id' => $request->participant_id,
            'program_code' => $request->program_code,
            'batch_id' => $request->batch_id,
            'requirement_id' => $request->requirement_id,
            'status' => 'Pending',
            'file_path' => $path,
            'notes' => $request->notes ?? '',
            'submitted_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Submitted successfully!',
            'data' => $submission
        ]);
    }
    public function adminStore(Request $request)
    {
        try {
            $request->validate([
                'participant_id' => 'required',
                'batch_id'       => 'required',
                'requirement_id' => 'required',
                'file'           => 'required|file|max:10240',
            ]);

            // Check duplicate first
            $exists = Submission::where('participant_id', $request->participant_id)
                ->where('requirement_id', $request->requirement_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission already exists'
                ], 409);
            }

            // Validate file
            $file = $request->file('file');

            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Uploaded file is invalid or corrupted.'
                ], 422);
            }

            // Store to storage/app/submissions/ (local disk, NOT public)
            $path = $file->store('submissions', 'public');

            if (!$path) {
                return response()->json([
                    'success' => false,
                    'message' => 'File could not be saved to storage.'
                ], 500);
            }

            // Save submission record
            $submission = Submission::create([
                'participant_id' => $request->participant_id,
                'batch_id'       => $request->batch_id,
                'requirement_id' => $request->requirement_id,
                'file_path'      => $path,
                'program_code'   => $request->program_code,
                'status' => 'Pending',
                'submitted_at' => Carbon::now(),
                'notes' => '',
            ]);

            return response()->json([
                'success' => true,
                'data'    => $submission,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Submission $submission)
    {
        try {
            // delete file from public folder
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }

            $submission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Submission deleted successfully.',
                // 'message' => Storage::exists($submission->file_path),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete submission: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function adminDestroy($id)
    {
        $submission = Submission::findOrFail($id);

        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $submission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Submission deleted successfully.'
        ]);
    }

   public function index(Request $request)
    {
        $query = Submission::with([
            'participant.employee',
            'participant.batch',
            'requirement'
        ]);

        // 🔎 SEARCH (employee-aware)
        if ($request->search) {
            $search = $request->search;

            $query->whereHas('participant.employee', function ($q) use ($search) {
                $q->where('EMPCODE', 'like', "%$search%")
                ->orWhere('LASTNAME', 'like', "%$search%")
                ->orWhere('FIRSTNAME', 'like', "%$search%")
                ->orWhere('OFFICE/DIVISION', 'like', "%$search%")
                ->orWhere('POSITION', 'like', "%$search%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->program_code) {
            $query->where('program_code', $request->program_code);
        }


        $data = $query->latest()->paginate(5);

        $year = now()->year;

        $data->getCollection()->transform(function ($sub) use ($year) {

            $req = $sub->requirement;

            if ($req) {
                $dueDate = \Carbon\Carbon::create(
                    $year,
                    $req->month_due,
                    $req->day_due
                );

                $sub->is_overdue = $sub->submitted_at
                    ? \Carbon\Carbon::parse($sub->submitted_at)->gt($dueDate) === false && now()->gt($dueDate)
                    : now()->gt($dueDate);
            } else {
                $sub->is_overdue = false;
            }

            return $sub;
        });

        return response()->json($data);
    }

public function show($id)
{
    return Submission::with([
        'participant.employee',
        'participant.batch',
        'requirement'
    ])->findOrFail($id);
}


public function update(Request $request, $id)
{
    $submission = Submission::findOrFail($id);

    $submission->status = $request->status;
    $submission->remarks = $request->remarks;
    $submission->reviewed_at = now();
    $submission->reviewed_by = Auth::user()?->empcode ?? 'admin';

    $submission->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully'
    ]);
}

public function availableRequirements($participantId)
{
    $participant = Participant::with(['batch', 'submissions'])->findOrFail($participantId);

    // get program_code from batch
    $programCode = $participant->batch->program_code;

    // ONLY requirements for this program
    $allRequirements = Requirement::where('program_code', $programCode)->get();

    // already submitted requirement IDs
    $submittedIds = $participant->submissions
        ->pluck('requirement_id')
        ->toArray();

    // filter out submitted ones
    $available = $allRequirements->whereNotIn('id', $submittedIds)->values();

    return response()->json([
        'data' => $available
    ]);
}


public function missingSubmissions(Request $request, $programCode)
{
    $search = $request->search;
    $batchId = $request->batch_id;
    $requirementId = $request->requirement_id;

    $requirements = Requirement::where('program_code', $programCode)
        ->when($requirementId && $requirementId != 'all', function ($q) use ($requirementId) {
            $q->where('id', $requirementId);
        })
        ->get();

    if ($requirements->isEmpty()) {
        return response()->json([]);
    }

    $participants = Participant::with(['employee', 'batch'])
        ->whereHas('batch', function ($q) use ($programCode, $batchId) {
            $q->where('program_code', $programCode);
            if ($batchId && $batchId !== 'all') {
                $q->where('id', $batchId);
            }
        })
        ->whereRaw('LOWER(attendance) != ?', ['absent'])
        ->when($search, function ($q) use ($search) {
            $q->whereHas('employee', function ($emp) use ($search) {
                $emp->where('FIRSTNAME', 'like', "%{$search}%")
                    ->orWhere('LASTNAME', 'like', "%{$search}%")
                    ->orWhere('EMPCODE', 'like', "%{$search}%");
            });
        })
        ->get();

    $missing = [];

    foreach ($participants as $participant) {

        // Look up the user email by empcode
        $user = User::where('empcode', $participant->empcode)->first();

        foreach ($requirements as $requirement) {

            $exists = Submission::where('participant_id', $participant->id)
                ->where('requirement_id', $requirement->id)
                ->exists();

            if (!$exists) {

                $dueDate = $requirement->getDueDateForBatch($participant->batch);

                $missing[] = [
                    'employee'    => $participant->employee->FIRSTNAME . ' ' .
                                     $participant->employee->LASTNAME,
                    'empcode'     => $participant->empcode,
                    'email'       => $user?->email ?? '',   // ← added
                    'batch'       => $participant->batch->batch,
                    'office'      => $participant->employee['OFFICE/DIVISION'],
                    'requirement' => $requirement->title,
                    'due_date'    => $dueDate?->format('F d, Y'),
                    'is_overdue'  => now()->gt($dueDate),
                ];
            }
        }
    }

    return response()->json($missing);
}

    


}
