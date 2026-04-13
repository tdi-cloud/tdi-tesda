<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $batch = Batch::all();
        return response()->json($batch);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'batch'      => 'required|string|max:255',
        'status'     => 'required|string',
        'modality'   => 'required|string',
        'venue'      => 'required|string',
        'date_start' => 'required|date',        // use date instead of string
        'date_end'   => 'required|date|after_or_equal:date_start', // must be after start
        'time_start' => 'required|date_format:H:i',
        'time_end'   => 'required|date_format:H:i|after:time_start', // must be after start
        'days'       => 'required|integer|min:1',
        'hours'      => 'required|integer|min:1', // added min:1
        ]);

        $submission = Batch::create($request->all());

        return response()->json([
            "status"  => "success",
            "message" => "Successfully added batch",
            'data'    => $submission->fresh(),
        ]);
        
      

    }

    public function getBatches($code){

        $batches = Batch::where('program_code', $code)
            ->with([
                'participants' => function ($query) {
                    $query->orderBy('sort_order', 'asc')
                        ->with('employee', 'justification');
                }
            ])
            ->orderBy('date_start', 'asc')
            ->get();

        return response()->json([
            'data' => $batches
        ]);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Batch $batch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        return response()->json([
            'data' => [$batch] 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)

    {
        $batch = Batch::findOrFail($id);
        $batch->update($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
