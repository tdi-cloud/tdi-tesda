<?php

namespace App\Http\Controllers;

use App\Models\Participant;
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
    $region      = $request->region;
    $statuses    = $request->plant_status;
    $officeFilter = $request->office_filter;

    $allRegions = [
        'CO','NCR','R1','R2','R3','R4A','R4B','R5',
        'NIR','R6','R7','R8','R9','R10','R11','R12',
        'CAR','CARAGA',
    ];

    /*
    |---------------------------------------
    | TOTAL EMPLOYEES
    |---------------------------------------
    */
    $totalEmployees = DB::table('employees')
        ->when($region && $region !== 'ALL', function ($q) use ($region) {
            $q->where('REGION', $region);
        })
        ->when(!empty($statuses), function ($q) use ($statuses) {
            $q->whereIn('PLANTILLA STATUS', $statuses);
        })
        ->when($officeFilter === 'OPCR', function ($q) {
            $q->where(function ($query) {
                $query->where('OFFICE/DIVISION', 'LIKE', 'CO-%')
                    ->orWhere('OFFICE/DIVISION', 'LIKE', '%ROD%')
                    ->orWhere('OFFICE/DIVISION', 'LIKE', '%ORD%')
                    ->orWhere('OFFICE/DIVISION', 'LIKE', '%PO-%')
                    ->orWhere('OFFICE/DIVISION', 'LIKE', '%DO%')
                    ->orWhere('OFFICE/DIVISION', 'LIKE', '%FASD%');
            });
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
        ->when($region && $region !== 'ALL', function ($q) use ($region) {
            $q->where('employees.REGION', $region);
        })
        ->when(!empty($statuses), function ($q) use ($statuses) {
            $q->whereIn('employees.PLANTILLA STATUS', $statuses);
        })
        ->when($officeFilter === 'OPCR', function ($q) {
            $q->where(function ($query) {
                $query->where('employees.OFFICE/DIVISION', 'LIKE', 'CO-%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ROD%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ORD%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%PO-%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%DO%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%FASD%');
            });
        })
        ->where('participants.attendance', '!=', 'Absent')
        ->where('batches.hours', '>=', 8)
        ->distinct()
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

    /*
    |---------------------------------------
    | REGIONAL BREAKDOWN (always all regions)
    |---------------------------------------
    */
    $regionsBreakdown = [];

    foreach ($allRegions as $reg) {

        // If a specific region is selected, zero out all other regions
        if ($region && $region !== 'ALL' && $reg !== $region) {
            $regionsBreakdown[] = [
                'total'       => 0,
                'trained'     => 0,
                'not_trained' => 0,
            ];
            continue;
        }

        $regTotal = DB::table('employees')
            ->where('REGION', $reg)
            ->when(!empty($statuses), function ($q) use ($statuses) {
                $q->whereIn('PLANTILLA STATUS', $statuses);
            })
            ->when($officeFilter === 'OPCR', function ($q) {
                $q->where(function ($query) {
                    $query->where('OFFICE/DIVISION', 'LIKE', 'CO-%')
                        ->orWhere('OFFICE/DIVISION', 'LIKE', '%ROD%')
                        ->orWhere('OFFICE/DIVISION', 'LIKE', '%ORD%')
                        ->orWhere('OFFICE/DIVISION', 'LIKE', '%PO-%')
                        ->orWhere('OFFICE/DIVISION', 'LIKE', '%DO%')
                        ->orWhere('OFFICE/DIVISION', 'LIKE', '%FASD%');
                });
            })
            ->count();

        $regTrained = DB::table('employees')
            ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
            ->join('batches', 'participants.batch_id', '=', 'batches.id')
            ->where('employees.REGION', $reg)
            ->when(!empty($statuses), function ($q) use ($statuses) {
                $q->whereIn('employees.PLANTILLA STATUS', $statuses);
            })
            ->when($officeFilter === 'OPCR', function ($q) {
                $q->where(function ($query) {
                    $query->where('employees.OFFICE/DIVISION', 'LIKE', 'CO-%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ROD%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ORD%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%PO-%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%DO%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%FASD%');
                });
            })
            ->where('participants.attendance', '!=', 'Absent')
            ->where('batches.hours', '>=', 8)
            ->distinct()
            ->count('employees.EMPCODE');

        $regionsBreakdown[] = [
            'total'       => $regTotal,
            'trained'     => $regTrained,
            'not_trained' => $regTotal - $regTrained,
        ];
    }

    $regionsTrained    = array_values(array_map(fn($v) => $v['trained'],     $regionsBreakdown));
    $regionsNotTrained = array_values(array_map(fn($v) => $v['not_trained'], $regionsBreakdown));

    return response()->json([
        'total'                  => $totalEmployees,
        'trained'                => $trainedEmployees,
        'not_trained'            => $notTrained,
        'trained_percentage'     => $trainedPercentage,
        'not_trained_percentage' => $notTrainedPercentage,
        'statuses'               => $statuses,
        'region'                 => $region,
        'office_filter'          => $officeFilter,
        'regions'                => $allRegions,
        'regions_trained'        => $regionsTrained,
        'regions_not_trained'    => $regionsNotTrained,
    ]);
}

   


public function getTrainingStats40hrs(Request $request)
{
    $region      = $request->region;
    $statuses    = $request->plant_status;
    $sgCondition = $request->sg_condition;
    $sgValue     = $request->sg_value;
    $officeFilter = $request->office_filter;

    $allowedOperators = ['=', '>', '>=', '<', '<='];
    $sgOperator = in_array($sgCondition, $allowedOperators) ? $sgCondition : null;

    $allRegions = [
        'CO','NCR','R1','R2','R3','R4A','R4B','R5',
        'NIR','R6','R7','R8','R9','R10','R11','R12',
        'CAR','CARAGA',
    ];

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
        $q->where('SG', $sgOperator, (int) $sgValue);
    })
    ->when($officeFilter === 'OPCR', function ($q) {
        $q->where(function ($query) {
            $query->where('OFFICE/DIVISION', 'LIKE', 'CO-%')
                ->orWhere('OFFICE/DIVISION', 'LIKE', '%ROD%')
                ->orWhere('OFFICE/DIVISION', 'LIKE', '%ORD%')
                ->orWhere('OFFICE/DIVISION', 'LIKE', '%PO-%')
                ->orWhere('OFFICE/DIVISION', 'LIKE', '%DO%')
                ->orWhere('OFFICE/DIVISION', 'LIKE', '%FASD%');
        });
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
            $q->where('employees.SG', $sgOperator, (int) $sgValue);
        })
        ->when($officeFilter === 'OPCR', function ($q) {
            $q->where(function ($query) {
                $query->where('employees.OFFICE/DIVISION', 'LIKE', 'CO-%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ROD%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ORD%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%PO-%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%DO%')
                    ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%FASD%');
            });
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

    /*
    |---------------------------------------
    | REGIONAL BREAKDOWN (always all regions)
    |---------------------------------------
    */
    $regionsBreakdown = [];

    foreach ($allRegions as $reg) {

        // If a specific region is selected, zero out all other regions
        if ($region && $region !== 'ALL' && $reg !== $region) {
            $regionsBreakdown[] = [
                'total'       => 0,
                'trained'     => 0,
                'not_trained' => 0,
            ];
            continue;
        }

        $regTotal = DB::table('employees')
            ->where('REGION', $reg)
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('PLANTILLA STATUS', $statuses);
            })
            ->when($sgOperator && $sgValue !== null && $sgValue !== '', function ($q) use ($sgOperator, $sgValue) {
                $q->where('SG', $sgOperator, (int) $sgValue);
            })
            ->count();

        $regTrained = DB::table('employees')
            ->join('participants', 'employees.EMPCODE', '=', 'participants.empcode')
            ->join('batches', 'participants.batch_id', '=', 'batches.id')
            ->join('programs', 'batches.program_code', '=', 'programs.program_code')
            ->where('employees.REGION', $reg)
            ->when($statuses && count($statuses), function ($q) use ($statuses) {
                $q->whereIn('employees.PLANTILLA STATUS', $statuses);
            })
            ->when($sgOperator && $sgValue !== null && $sgValue !== '', function ($q) use ($sgOperator, $sgValue) {
                $q->where('employees.SG', $sgOperator, (int) $sgValue);
            })
            ->when($officeFilter === 'OPCR', function ($q) {
                $q->where(function ($query) {
                    $query->where('employees.OFFICE/DIVISION', 'LIKE', 'CO-%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ROD%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ORD%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%PO-%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%DO%')
                        ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%FASD%');
                });
            })
            ->where('participants.attendance', '!=', 'Absent')
            ->where('batches.hours', '>=', 40)
            ->where('programs.type', 'SUPERVISORY/MANAGERIAL')
            ->distinct('employees.EMPCODE')
            ->count('employees.EMPCODE');

        $regionsBreakdown[] = [
            'total'       => $regTotal,
            'trained'     => $regTrained,
            'not_trained' => $regTotal - $regTrained,
        ];
    }

    $regionsTrained    = array_values(array_map(fn($v) => $v['trained'],     $regionsBreakdown));
    $regionsNotTrained = array_values(array_map(fn($v) => $v['not_trained'], $regionsBreakdown));

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
        'office_filter'          => $officeFilter,
        'regions'                => $allRegions,
        'regions_trained'        => $regionsTrained,
        'regions_not_trained'    => $regionsNotTrained,
    ]);
}


public function treapDashboard(Request $request)
{
    $officeFilter = $request->office_filter ?? 'ALL';
    $regionFilter = $request->region ?? 'ALL';
    $plantStatus  = $request->plant_status ?? []; // ✅ ADDED

    $regions = [
        'CO','NCR','R1','R2','R3','R4A','R4B','R5',
        'NIR','R6','R7','R8','R9','R10','R11','R12',
        'CAR','CARAGA'
    ];

    /**
     * BASE QUERY
     */
    $base = Participant::query()
        ->where('participants.attendance', '!=', 'Absent')
        ->join('batches', 'participants.batch_id', '=', 'batches.id')
        ->join('requirements', 'batches.program_code', '=', 'requirements.program_code')
        ->join('employees', 'participants.empcode', '=', 'employees.EMPCODE')
        ->where('requirements.title', 'TREAP');

    /**
     * PLANTILLA STATUS FILTER (✅ NEW)
     */
    if (!empty($plantStatus)) {
        $base->whereIn('employees.PLANTILLA STATUS', $plantStatus);
    }

    /**
     * OPCR FILTER
     */
    if ($officeFilter === 'OPCR') {
        $base->where(function ($q) {
            $q->where('employees.OFFICE/DIVISION', 'LIKE', 'CO-%')
              ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ROD%')
              ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%ORD%')
              ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%PO-%')
              ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%DO%')
              ->orWhere('employees.OFFICE/DIVISION', 'LIKE', '%FASD%');
        });
    }

    /**
     * REGION FILTER
     */
    if ($regionFilter !== 'ALL') {
        $base->where('employees.REGION', $regionFilter);
    }

    /**
     * DUE DATE FILTER (SQL equivalent of your PHP logic)
     */
    $base->whereRaw("
        CASE 
            WHEN requirements.day_due > 0 THEN 
                DATE_ADD(batches.date_end, INTERVAL requirements.day_due DAY)
            WHEN requirements.month_due > 0 THEN 
                DATE_ADD(batches.date_end, INTERVAL requirements.month_due MONTH)
            ELSE NULL
        END <= NOW()
    ");

    /**
     * WITH / WITHOUT SUBMISSION PER REGION
     */
    $raw = (clone $base)
        ->leftJoin('submissions', function ($join) {
            $join->on('submissions.participant_id', '=', 'participants.id')
                 ->on('submissions.requirement_id', '=', 'requirements.id');
        })
        ->select(
            'employees.REGION',
            DB::raw("COUNT(CASE WHEN submissions.id IS NOT NULL THEN 1 END) as with_submission"),
            DB::raw("COUNT(CASE WHEN submissions.id IS NULL THEN 1 END) as without_submission")
        )
        ->groupBy('employees.REGION')
        ->get()
        ->keyBy('REGION');

    /**
     * FORMAT FOR APEX CHART (FIXED ORDER)
     */
    $with = [];
    $without = [];

    foreach ($regions as $r) {
        $with[] = $raw[$r]->with_submission ?? 0;
        $without[] = $raw[$r]->without_submission ?? 0;
    }

    /**
     * TOTALS
     */
    $totalWith = array_sum($with);
    $totalWithout = array_sum($without);

    $total = $totalWith + $totalWithout;

    $percentage = $total > 0
        ? round(($totalWith / $total) * 100, 2)
        : 0;

    /**
     * RESPONSE
     */
    return response()->json([
        'region_chart' => [
            'xaxis' => $regions,
            'series' => [
                [
                    'name' => 'With Submission',
                    'data' => $with
                ],
                [
                    'name' => 'No Submission',
                    'data' => $without
                ]
            ]
        ],

        'radial_chart' => [
            'series' => [$percentage]
        ],

        'totals' => [
            'with' => $totalWith,
            'without' => $totalWithout,
            'percentage' => $percentage
        ]
    ]);
}



}
