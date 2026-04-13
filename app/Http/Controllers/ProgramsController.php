<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\CoverPage;
use App\Models\Program;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

    public function getAll(Request $request){
        $search = $request->query('search');

        $programs = Program::with(['coverPages','batches' => function ($query) {
            $query->withCount('participants');
        }])
        ->when($search, function($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                ->orWhere('program_code', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
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

    public function showDetails($id){
        $myprogram = Program::find($id);
        $cover = CoverPage::where('program_id', $id)->first();
        return view('monitoring.prog-info', compact('myprogram','cover'));
    }



 
    public function getProgramsCount(){
        $totalPrograms = Program::count();
        return response()->json($totalPrograms);
    }
    
    public function getTesdaOrders($id){
        $myprogram = Program::find($id);
        return view('monitoring.tesda-orders', compact('myprogram'));
    }
  

 

   

}
