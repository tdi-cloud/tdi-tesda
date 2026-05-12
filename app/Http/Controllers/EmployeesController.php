<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeesController extends Controller
{
    public function employees(Request $request)
    {
        $query = employees::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('FIRSTNAME', 'LIKE', "%{$request->search}%")
                ->orWhere('LASTNAME', 'LIKE', "%{$request->search}%")
                ->orWhere('EMPCODE', 'LIKE', "%{$request->search}%")
                ->orWhere('OFFICE/DIVISION', 'LIKE', "%{$request->search}%");
            });
        }

        // Department filter
        if ($request->dept) {
            $query->where('OFFICE/DIVISION', $request->dept);
        }

        // Status filter
        if ($request->status && $request->status !== 'all') {
            $query->where('PLANTILLA STATUS', $request->status);
        }

        if ($request->region) {
            $query->where('REGION', $request->region);
        }

        $perPage = $request->per_page ?? 9;

        $employees = $query->paginate($perPage);

        return response()->json($employees);
    }

    public function getEmployeeTrainings(Request $request)
    {
        $region = $request->region;
        $types = $request->types; // array from checkbox

        // Base query
        $employees = \App\Models\employees::query();

        // Filter by region
        if ($region !== 'ALL') {
            $employees->where('REGION', $region);
        }

        // Filter by types (checkboxes)
        if (!empty($types)) {
            $employees->whereIn('PLANTILLA STATUS', $types);
        }

        // Clone queries to avoid conflict
        $totalEmployees = (clone $employees)->count();

        // With Trainings (exists in participants)
        $withTrainings = (clone $employees)
        ->whereExists(function ($query) {
            $query->selectRaw('1')
                ->from('participants')
                ->whereColumn('participants.empcode', 'employees.EMPCODE')
                ->where('participants.attendance', '!=', 'Absent');
        })
        ->count();

        // No Trainings
        $noTrainings = $totalEmployees - $withTrainings;

        return response()->json([
            'total' => $totalEmployees,
            'with_training' => $withTrainings,
            'no_training' => $noTrainings,
            'region' =>  $region
        ]);
    }


    public function employeesList(){
        $employees = \App\Models\employees::limit(500)->get();
        return response()->json($employees);
    }

    public function searchSelect(Request $request)
    {
        $q = $request->q;

        $employees = employees::where('EMPCODE', 'like', "%{$q}%")
            ->orWhere('FIRSTNAME', 'like', "%{$q}%")
            ->orWhere('LASTNAME', 'like', "%{$q}%")
            ->limit(20)
            ->get();

        return response()->json($employees);
    }
    
    public function view($empcode)
    {
        $participants = Participant::with([
        'batch.program',
        'submissions.requirement',
        'employee',
        ])
        ->where('empcode', $empcode)
        ->get();

        // ===== METRICS =====
        $totalPrograms = $participants->count();

        $completed = $participants->where('attendance', 'completed')->count();

        $totalHours = $participants->sum('hours');

        $totalRequirements = 0;
        $submitted = 0;

        foreach ($participants as $p) {
            $reqCount = DB::table('requirements')
                ->where('program_code', $p->batch->program_code)
                ->count();

            $totalRequirements += $reqCount;
            $submitted += $p->submissions->count();
        }

        $submissionRate = $totalRequirements > 0
            ? round(($submitted / $totalRequirements) * 100, 1)
            : 0;

        $completionRate = $totalPrograms > 0
            ? round(($completed / $totalPrograms) * 100, 1)
            : 0;

        $rating = round(
            ($completionRate * 0.4) +
            ($submissionRate * 0.3) +
            (min($totalHours / 40 * 100, 100) * 0.2) +
            (90 * 0.1),
            1
        );

        return response()->json([
            'summary' => [
                'totalPrograms' => $totalPrograms,
                'completed' => $completed,
                'hours' => $totalHours,
                'submissionRate' => $submissionRate,
                'rating' => $rating,
            ],
            'programs' => $participants
        ]);

    }
}
