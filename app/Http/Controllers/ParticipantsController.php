<?php

namespace App\Http\Controllers;

use App\Models\AbsentJustification;
use App\Models\employees;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $exists = Participant::where('empcode', $code)->exists();
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
            'skipped'  => $skipped->count() > 0
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

    

    
}
