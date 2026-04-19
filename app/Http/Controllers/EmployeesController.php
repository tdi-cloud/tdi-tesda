<?php

namespace App\Http\Controllers;

use App\Models\employees;
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
                ->whereColumn('participants.empcode', 'employees.EMPCODE');
        })
        ->count();

        // No Trainings
        $noTrainings = $totalEmployees - $withTrainings;

        return response()->json([
            'total' => $totalEmployees,
            'with_training' => $withTrainings,
            'no_training' => $noTrainings,
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
            ->limit(20)
            ->get();

        return response()->json($employees);
    }
}
