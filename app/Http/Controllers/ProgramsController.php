<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\CoverPage;
use App\Models\Program;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\DB;


class ProgramsController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'competency' => 'required|string',
            'modality' => 'required|string',
            'pax' => 'required|integer|min:1',
            'category' => 'required|string',
            'type' => 'required|string',
            'initiated' => 'required|string',
            'cost' => 'required|string',
            'fund' => 'required|string',
            'origin' => 'required|string',
      
        ]);

        $submission = Program::create($request->all());

        $programCode = 'TDI-' . date('Y') . '-' . str_pad($submission->id, 4, '0', STR_PAD_LEFT);

        $submission->update(['program_code' => $programCode]);

        return response()->json([
            "status"  => "success",
            "message" => "Successfully Posted",
            'data'    => $submission->fresh(),
            'id'      => $submission->id,
        ]);
    }

    public function getAll(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $programs = Program::whereHas('batches', function ($query) use ($status) {

            if ($status) {
                $query->where('status', $status);
            }

        })
        ->with([
            'coverPages',

            'batches' => function ($query) use ($status) {

                // FILTER STATUS
                if ($status) {
                    $query->where('status', $status);
                }

                $query->withCount('participants')
                    ->orderBy('date_start', 'asc');
            }

        ])
        ->withCount('requirements')

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhere('program_code', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");

            });

        })

        ->latest()
        ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $programs,
        ]);
    }

 
    public function show($id)
    {
        //
        $myprogram = Program::find($id);
    
        $batchCount = Batch::where('program_code', $myprogram->program_code)->count();

         $cover = CoverPage::where('program_id', $id)->first();

        return view('monitoring.myprogram', compact('myprogram', 'batchCount', 'cover'));
    }

    public function showRequirement($id){
        $myprogram = Program::find($id);
        $cover = CoverPage::where('program_id', $id)->first();
        return view('monitoring.requirements', compact('myprogram','cover'));
    }

    public function showSubmissions($id){
        $myprogram = Program::find($id);
        $cover = CoverPage::where('program_id', $id)->first();
        return view('monitoring.submissions', compact('myprogram','cover'));
    }
    

    public function showCertficates($id){
        $myprogram = Program::find($id);
        $cover = CoverPage::where('program_id', $id)->first();
        return view('monitoring.certificate', compact('myprogram','cover'));
    }

    public function showDetails($id){
        $myprogram = Program::find($id);
        $cover = CoverPage::where('program_id', $id)->first();
        return view('monitoring.prog-info', compact('myprogram','cover'));
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);

        return response()->json($program);
    }



 
    public function getProgramsCount(){
        $totalPrograms = Program::count();
        return response()->json($totalPrograms);
    }
    
    public function getTesdaOrders($id){
        $myprogram = Program::find($id);
        return view('monitoring.tesda-orders', compact('myprogram'));
    }


    public function myPrograms(Request $request)
    {
        $empcode = auth()->user()->empcode;

        $query = $request->get('q');
        $perPage = $request->get('per_page', 10);

        $programs = Program::with([
            'coverPages',
            'batches' => function ($batchQuery) use ($empcode) {
                $batchQuery->select('*')
                    ->selectRaw('YEAR(date_start) as year')
                    ->with([
                        'participants' => function ($participantQuery) use ($empcode) {
                            $participantQuery->where('empcode', $empcode);
                        }
                    ]);
            }
        ])
        ->whereHas('batches.participants', function ($q) use ($empcode) {
            $q->where('empcode', $empcode);
        });

        // ✅ APPLY SEARCH HERE (Query Builder, not Collection)
        if ($query) {
            $programs->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                ->orWhere('program_code', 'LIKE', "%{$query}%");
            });
        }

        $year = $request->get('year');

        if ($year) {
            $programs->whereHas('batches', function ($q) use ($year) {
                $q->whereYear('date_start', $year);
            });
        }

        $years = DB::table('batches')
        ->join('participants', 'batches.id', '=', 'participants.batch_id')
        ->where('participants.empcode', $empcode)
        ->selectRaw('YEAR(batches.date_start) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

        // ✅ paginate BEFORE get()
        return response()->json([
            'data' => $programs->orderBy('sort_order', 'asc')->paginate($perPage),
            'years' => $years
        ]);
    }
  
    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $program->update([
            'title' => $request->title,
            'description' => $request->description,
            'competency' => $request->competency,
            'modality' => $request->modality,
            'pax' => $request->pax,
            'category' => $request->category,
            'type' => $request->type,
            'initiated' => $request->initiated,
            'provider' => $request->provider,
            'cost' => $request->cost,
            'fund' => $request->fund,
            'origin' => $request->origin,
        ]);

        return response()->json([
            'message' => 'Program updated successfully',
            'program' => $program
        ]);
    }


    public function destroy($id)
    {
        $program = DB::table('programs')->where('id', $id)->first();

        if (!$program) {
            return response()->json([
                'success' => false,
                'message' => 'Program not found.'
            ], 404);
        }

        DB::beginTransaction();

        try {
            // Get related batch IDs
            $batchIds = DB::table('batches')
                ->where('program_code', $program->program_code)
                ->pluck('id');

            // Delete participants
            DB::table('participants')
                ->whereIn('batch_id', $batchIds)
                ->delete();

            // Delete batches
            DB::table('batches')
                ->where('program_code', $program->program_code)
                ->delete();

            // Delete program
            DB::table('programs')->where('id', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Program, batches, and participants deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Delete failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
 

   

}
