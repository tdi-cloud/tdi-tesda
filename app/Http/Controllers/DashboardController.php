<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function generateTPMRPdf(Request $request)
    {
        $query = DB::table('participants')
                ->join('employees', 'participants.empcode', '=', 'employees.EMPCODE')
                ->join('batches', 'participants.batch_id', '=', 'batches.id')
                ->join('programs', 'batches.program_code', '=', 'programs.program_code')
                ->select(
                    // 'employees.EMPCODE',
                    'employees.LASTNAME',
                    'employees.FIRSTNAME',
                    'employees.MI',
                    'employees.POSITION',
                    DB::raw('`employees`.`OFFICE/DIVISION` as office'),
                    'programs.title as program_title',
                    // 'batches.batch',
                    'batches.date_start',
                    'batches.date_end',
                    'participants.attendance'
                )
                ->where('participants.attendance', '!=', 'Absent');

            // REGION FILTER
            if ($request->region) {
                $query->where('employees.REGION', $request->region);
            }

            // MONTH FILTER
            if ($request->filter === 'monthly') {

                if (empty($request->month) || empty($request->year)) {
                    return response()->json([
                        'message' => 'Month and Year are required for monthly filter.'
                    ], 422);
                }

                $query->whereMonth('batches.date_start', (int) $request->month)
                    ->whereYear('batches.date_start', (int) $request->year);
            }

            // YEAR FILTER
            if ($request->filter === 'annual' && is_numeric($request->year)) {
                $query->whereYear('batches.date_start', (int) $request->year);
            }

            $reports = $query->get();

            $preparedBy = [
                'name' => $request->prepared_name ,
                'position' => $request->prepared_position,
                'prepared_date'=> $request->prepared_date
            ];

            $notedBy = [
                'name' => $request->noted_name,
                'position' => $request->noted_position,
                'noted_date'=> $request->noted_date
            ];


            $regions = [
                '' => 'All Regions',
                'CO' => 'Central Office',
                'NCR' => 'NCR',
                'R1' => 'Region I',
                'R2' => 'Region II',
                'R3' => 'Region III',
                'R4A' => 'Region IV-A',
                'R4B' => 'Region IV-B',
                'R5' => 'Region V',
                'R6' => 'Region VI',
                'NIR' => 'NIR',
                'R7' => 'Region VII',
                'R8' => 'Region VIII',
                'R9' => 'Region IX',
                'R10' => 'Region X',
                'R11' => 'Region XI',
                'R12' => 'Region XII',
                'CAR' => 'CAR',
                'CARAGA' => 'CARAGA',
            ];

            $regionLabel = $regions[$request->region] ?? 'All Regions';

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfGenerate.tpmr', [
                'reports' => $reports,
                'preparedBy' => $preparedBy,
                'notedBy' => $notedBy,
                'filter' => $request->filter,
                'month' => $request->month,
                'year' => $request->year,
                'region' => $regionLabel,
            ]);

            

            return $pdf->download('TPMR-Report.pdf');
            
        }

    public function getUserProgramCount()
    {
        $empcode = Auth::user()->empcode;

        $count = DB::table('participants')
            ->join('batches', 'participants.batch_id', '=', 'batches.id')
        ->join('programs', 'batches.program_code', '=', 'programs.program_code')
            ->where('participants.empcode', $empcode)
            ->distinct('programs.program_code')
            ->count('programs.program_code');

        return response()->json([
            'program_count' => $count
        ]);
    }

    public function getTrainingStats8hrs(Request $request)
    {
        $region = $request->region;
        $statuses = $request->plant_status;

        /*
        |---------------------------------------
        | TOTAL EMPLOYEES
        |---------------------------------------
        */
        $totalEmployees = DB::table('employees')
            ->when($region && $region !== 'ALL', function ($q) use ($region) {
                $q->where('REGION', $region);
            })
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('PLANTILLA STATUS', $statuses);
            })
            ->count();

        /*
        |---------------------------------------
        | TRAINED EMPLOYEES
        | - not absent
        | - batch hours >= 8
        |---------------------------------------
        */
        $trainedEmployees = DB::table('employees')
            ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
            ->join('batches', 'participants.batch_id', '=', 'batches.id')
            ->when($region && $region !== 'ALL', function ($q) use ($region) {
                $q->where('employees.REGION', $region);
            })
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('employees.PLANTILLA STATUS', $statuses);
            })
            ->where('participants.attendance', '!=', 'Absent')
            ->where('batches.hours', '>=', 8)
            ->distinct('employees.EMPCODE')
            ->count('employees.EMPCODE');

        /*
        |---------------------------------------
        | NOT TRAINED
        |---------------------------------------
        */
        $notTrained = $totalEmployees - $trainedEmployees;

        /*
        |---------------------------------------
        | PERCENTAGES
        |---------------------------------------
        */
        $trainedPercentage = $totalEmployees > 0
            ? round(($trainedEmployees / $totalEmployees) * 100, 2)
            : 0;

        $notTrainedPercentage = $totalEmployees > 0
            ? round(($notTrained / $totalEmployees) * 100, 2)
            : 0;

        return response()->json([
            'total' => $totalEmployees,
            'trained' => $trainedEmployees,
            'not_trained' => $notTrained,
            'trained_percentage' => $trainedPercentage,
            'not_trained_percentage' => $notTrainedPercentage,
            'statuses' => $statuses,
            'region' => $region,
        ]);
    }

    public function getTrainingStats8hrsBars(Request $request)
    {
        $selected = $request->region;
        $statuses = $request->plant_status;

        /*
        |---------------------------------------
        | REGION MAPPING (frontend → DB)
        |---------------------------------------
        */
        $regionMap = [
            'CO' => 'CO',
            'NCR' => 'NCR',
            'R1' => 'R1',
            'R2' => 'R2',
            'R3' => 'R3',
            'R4A' => 'R4A',
            'R4B' => 'R4B',
            'R5' => 'R5',
            'NIR' => 'NIR',
            'R6' => 'R6',
            'R7' => 'R7',
            'R8' => 'R8',
            'R9' => 'R9',
            'R10' => 'R10',
            'R11' => 'R11',
            'R12' => 'R12',
            'CAR' => 'CAR',
            'CARAGA' => 'CARAGA',
        ];

        /*
        |---------------------------------------
        | FULL REGION LIST (for chart consistency)
        |---------------------------------------
        */
        $allRegions = [
            'CO',
            'NCR',
            'R1',
            'R2',
            'R3',
            'R4A',
            'R4B',
            'R5',
            'NIR',
            'R6',
            'R7',
            'R8',
            'R9',
            'R10',
            'R11',
            'R12',
            'CAR',
            'CARAGA',
        ];

        /*
        |---------------------------------------
        | DETERMINE SELECTED REGION (if any)
        |---------------------------------------
        */
        $selectedRegionName = null;

        if ($selected !== 'ALL' && isset($regionMap[$selected])) {
            $selectedRegionName = $regionMap[$selected];
        }

        $trained = [];
        $notTrained = [];

        /*
        |---------------------------------------
        | LOOP ALL REGIONS (IMPORTANT)
        |---------------------------------------
        */
        foreach ($allRegions as $region) {

            // TOTAL EMPLOYEES
            $total = DB::table('employees')
                ->where('REGION', $region)
                ->when($statuses && count($statuses), function ($q) use ($statuses) {
                    $q->whereIn('PLANTILLA STATUS', $statuses);
                })
                ->count();

            // TRAINED EMPLOYEES
            $trainedCount = DB::table('employees')
                ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
                ->join('batches', 'participants.batch_id', '=', 'batches.id')
                ->where('employees.REGION', $region)
                ->when($statuses && count($statuses), function ($q) use ($statuses) {
                    $q->whereIn('employees.PLANTILLA STATUS', $statuses);
                })
                ->where('participants.attendance', '!=', 'Absent')
                ->where('batches.hours', '>=', 8)
                ->distinct('employees.EMPCODE')
                ->count('employees.EMPCODE');

            /*
            |---------------------------------------
            | FILTER LOGIC (THIS IS THE KEY)
            |---------------------------------------
            */
            if ($selected !== 'ALL') {
                if ($region !== $selectedRegionName) {
                    $trained[] = 0;
                    $notTrained[] = 0;
                    continue;
                }
            }

            $trained[] = $trainedCount;
            $notTrained[] = max($total - $trainedCount, 0);
        }

        /*
        |---------------------------------------
        | RESPONSE
        |---------------------------------------
        */
        return response()->json([
            'regions' => $allRegions,
            'trained' => $trained,
            'not_trained' => $notTrained,
            'selected' => $selected
        ]);
    }


    public function getTrainingStats40hrs(Request $request)
    {
        $region     = $request->region;
        $statuses   = $request->plant_status;
        $sgCondition = $request->sg_condition;  // '=', '>', '>=', '<', '<='
        $sgValue     = $request->sg_value;      // numeric value

        // whitelist allowed operators to prevent SQL injection
        $allowedOperators = ['=', '>', '>=', '<', '<='];
        $sgOperator = in_array($sgCondition, $allowedOperators) ? $sgCondition : null;

        /*
        |---------------------------------------
        | TOTAL EMPLOYEES
        |---------------------------------------
        */
        $totalEmployees = DB::table('employees')
            ->when($region && $region !== 'ALL', function ($q) use ($region) {
                $q->where('REGION', $region);
            })
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('PLANTILLA STATUS', $statuses);
            })
            ->when($sgOperator && $sgValue !== null && $sgValue !== '', function ($q) use ($sgOperator, $sgValue) {
                $q->where('SG', $sgOperator, (int) $sgValue);   // ← SG filter
            })
            ->count();

        /*
        |---------------------------------------
        | TRAINED EMPLOYEES
        |---------------------------------------
        */
        $trainedEmployees = DB::table('employees')
            ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
            ->join('batches', 'participants.batch_id', '=', 'batches.id')
            ->join('programs', 'batches.program_code', '=', 'programs.program_code')
            ->when($region && $region !== 'ALL', function ($q) use ($region) {
                $q->where('employees.REGION', $region);
            })
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('employees.PLANTILLA STATUS', $statuses);
            })
            ->when($sgOperator && $sgValue !== null && $sgValue !== '', function ($q) use ($sgOperator, $sgValue) {
                $q->where('employees.SG', $sgOperator, (int) $sgValue);   // ← SG filter
            })
            ->where('participants.attendance', '!=', 'Absent')
            ->where('batches.hours', '>=', 40)
            ->where('programs.type', 'SUPERVISORY/MANAGERIAL')
            ->distinct('employees.EMPCODE')
            ->count('employees.EMPCODE');

        /*
        |---------------------------------------
        | NOT TRAINED
        |---------------------------------------
        */
        $notTrained = $totalEmployees - $trainedEmployees;

        $trainedPercentage = $totalEmployees > 0
            ? round(($trainedEmployees / $totalEmployees) * 100, 2)
            : 0;

        $notTrainedPercentage = $totalEmployees > 0
            ? round(($notTrained / $totalEmployees) * 100, 2)
            : 0;

        return response()->json([
            'total'                  => $totalEmployees,
            'trained'                => $trainedEmployees,
            'not_trained'            => $notTrained,
            'trained_percentage'     => $trainedPercentage,
            'not_trained_percentage' => $notTrainedPercentage,
            'statuses'               => $statuses,
            'region'                 => $region,
            'sg_condition'           => $sgCondition,
            'sg_value'               => $sgValue,
        ]);
    }

    public function getTrainingStats40hrsBars(Request $request)
    {
        $selected = $request->region;
        $statuses = $request->plant_status;

        // ✅ NEW: SG filter
        $sgCondition = $request->sg_condition ?? '>=';
        $sgValue = $request->sg_value;

        /*
        |---------------------------------------
        | REGION MAPPING
        |---------------------------------------
        */
        $regionMap = [
            'CO' => 'CO',
            'NCR' => 'NCR',
            'R1' => 'R1',
            'R2' => 'R2',
            'R3' => 'R3',
            'R4A' => 'R4A',
            'R4B' => 'R4B',
            'R5' => 'R5',
            'NIR' => 'NIR',
            'R6' => 'R6',
            'R7' => 'R7',
            'R8' => 'R8',
            'R9' => 'R9',
            'R10' => 'R10',
            'R11' => 'R11',
            'R12' => 'R12',
            'CAR' => 'CAR',
            'CARAGA' => 'CARAGA',
        ];

        /*
        |---------------------------------------
        | ALL REGIONS
        |---------------------------------------
        */
        $allRegions = [
            'CO','NCR','R1','R2','R3','R4A','R4B','R5',
            'NIR','R6','R7','R8','R9','R10','R11','R12',
            'CAR','CARAGA',
        ];

        /*
        |---------------------------------------
        | SELECTED REGION
        |---------------------------------------
        */
        $selectedRegionName = null;

        if ($selected !== 'ALL' && isset($regionMap[$selected])) {
            $selectedRegionName = $regionMap[$selected];
        }

        $trained = [];
        $notTrained = [];

        /*
        |---------------------------------------
        | LOOP REGIONS
        |---------------------------------------
        */
        foreach ($allRegions as $region) {

            /*
            |---------------------------------------
            | TOTAL EMPLOYEES
            |---------------------------------------
            */
            $total = DB::table('employees')
                ->where('REGION', $region)
                ->when($statuses && count($statuses), function ($q) use ($statuses) {
                    $q->whereIn('PLANTILLA STATUS', $statuses);
                })
                ->when($sgValue !== null, function ($q) use ($sgCondition, $sgValue) {
                    $q->where('SG', $sgCondition, $sgValue);
                })
                ->count();

            /*
            |---------------------------------------
            | TRAINED EMPLOYEES (≥ 40 hrs)
            |---------------------------------------
            */
            $trainedCount = DB::table('employees')
                ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
                ->join('batches', 'participants.batch_id', '=', 'batches.id')
                ->where('employees.REGION', $region)
                ->when($statuses && count($statuses), function ($q) use ($statuses) {
                    $q->whereIn('employees.PLANTILLA STATUS', $statuses);
                })
                ->when($sgValue !== null, function ($q) use ($sgCondition, $sgValue) {
                    $q->where('employees.SG', $sgCondition, $sgValue);
                })
                ->where('participants.attendance', '!=', 'Absent')
                ->where('batches.hours', '>=', 40) // ✅ CHANGED TO 40
                ->distinct('employees.EMPCODE')
                ->count('employees.EMPCODE');

            /*
            |---------------------------------------
            | FILTER REGION DISPLAY
            |---------------------------------------
            */
            if ($selected !== 'ALL') {
                if ($region !== $selectedRegionName) {
                    $trained[] = 0;
                    $notTrained[] = 0;
                    continue;
                }
            }

            $trained[] = $trainedCount;
            $notTrained[] = max($total - $trainedCount, 0);
        }

        /*
        |---------------------------------------
        | RESPONSE
        |---------------------------------------
        */
        return response()->json([
            'regions' => $allRegions,
            'trained' => $trained,
            'not_trained' => $notTrained,
            'selected' => $selected
        ]);
    }



}
