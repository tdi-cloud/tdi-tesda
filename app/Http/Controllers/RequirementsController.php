<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RequirementsController extends Controller
{
    public function create(Request $request)
    {
        $request->merge([
            'title' => strtoupper($request->title)
        ]);

        $request->validate([
            "title" => [
                "required",
                Rule::unique('requirements')->where(fn($q) => 
                    $q->where('program_code', $request->program_code)
                ),
            ],
            "program_code" => "required",
            "description" => "nullable",
        ]);

        $rules = [
            'TREAP' => ['day' => 5, 'month' => 0],
            'REAP'  => ['day' => 15, 'month' => 0],
            'TDOR'  => ['day' => 0, 'month' => 6],
        ];

        $title = $request->title;

        $submission = Requirement::create([
            "title" => $title,
            "program_code" => $request->program_code,
            "description" => $request->description,
            "required" => $request->required,
            "day_due" => $rules[$title]['day'] + 0 ?? 5,
            "month_due" => $rules[$title]['month'] + 0 ?? 0,
        ]);

        return response()->json([
            "status"  => true,
            "message" => "Successfully Posted",
            'data'    => $submission->fresh(),
        ]);
    }
    


    public function getRequirements($program)
    {
        $requirements = Requirement::with('batches')
            ->where('program_code', $program)
            
            ->get();

        return response()->json([
            'data' => $requirements
        ]);
    }

    public function destroy($id)
    {
        $requirement = Requirement::findOrFail($id);
        $requirement->delete();

        return response()->json([
            'status' => true,
            'message' => 'Deleted'
            ]);
    }

    public function getRequirementsView($program_code, $participant_id)
    {
        $requirements = Requirement::with(['batches', 'submissions' => function ($q) use ($participant_id) {
            $q->where('participant_id', $participant_id);
        }])
        ->where('program_code', $program_code)
        ->get();

        return response()->json($requirements);
    }
}
