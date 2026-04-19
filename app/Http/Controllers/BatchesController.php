<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchesController extends Controller
{
    
    public function index()
    {
       
        $batch = Batch::all();
        return response()->json($batch);
    }


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

    public function edit($id)
    {
        $batch = Batch::findOrFail($id);

        return response()->json([
            'data' => [$batch] 
        ]);
    }

   
    public function update(Request $request, $id)

    {
        $batch = Batch::findOrFail($id);
        $batch->update($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }

    public function events()
    {
        $batches = Batch::with(['program.coverPages'])->get();

        $events = $batches->map(function ($batch) {
            return [
                'title' => $batch->program->title,
                'start' => $batch->date_start,
                'end' => $batch->date_end,
                
                'extendedProps' => [
                    'batch' => $batch->batch,
                    'status' => $batch->status,
                    'program_code' => $batch->program_code,
                    'program_title' => $batch->program->title ?? 'N/A',
                    'cover_image' => optional($batch->program->coverPages->first())->image
                    ? asset('/storage/'.$batch->program->coverPages->first()->image)
                    : null,
                    'venue' => $batch->venue,
                    'status' => $batch->status,
                    'modality' => $batch->modality,
                    'hours' => $batch->hours,
                    'days' => $batch->days,
                    'id' => $batch->program->id,
                ]
            ];
        });

        return response()->json($events);
    }

    public function trendData()
    {
        $batches = Batch::withCount('participants') // 👈 important
            ->orderBy('date_start')
            ->get();

        $data = $batches->map(function ($batch) {
            return [
                'x' => $batch->date_start,
                'y' => (int) $batch->participants_count, // 👈 participant count
                'batch' => $batch->batch,
                'program_title' => $batch->program->title ?? 'No Program',
                'date_end' => $batch->date_end,
            ];
        });

        return response()->json($data);
    }

    
}
