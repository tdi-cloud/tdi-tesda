<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\TESDAOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TESDAOrderController extends Controller
{
    public function store(Request $request){
        $data = $request->validate([
        'program_code' => ['required','string','exists:programs,program_code'],
        'subject' => ['required','string','max:255'],
        'series' => ['nullable','string','max:50'],
        'date_issued' => ['nullable','string'],
        'effectivity' => ['nullable','string','max:255'],
        'supersedes' => ['nullable','string'],
        'body' => ['required','string'],
        'with_employees' => ['nullable','boolean'],
        'with_batch' => ['nullable','boolean'],
        'closure' => ['required','string'],
        'signatory_name' => ['required','string','max:255'],
        'signatory_position' => ['required','string','max:255'],
    ]);

    $data['date_issued'] = $data['date_issued'] ?: ' ';
    $data['effectivity'] = $data['effectivity'] ?: 'As indicated';
    $data['supersedes'] = $data['supersedes'] ?: ' ';

    $order = \App\Models\TesdaOrder::create($data);
    $order->load('program');

    return response()->json([
        'success' => true,
        'message' => 'Created successfully',
        'data' => $order
    ]);
    }


    public function TESDAOrder($id)
    {
        $program = TESDAOrder::with([
            'program',
            'batches.participants'
        ])->findOrFail($id);
      

        $pdf = Pdf::loadView('pdfGenerate.tesda-order', [
            'program' => $program
        ])->setPaper('a4', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $dompdf->render();

        $canvas = $dompdf->getCanvas();

        $canvas->page_script(function (
            $pageNumber,
            $pageCount,
            $canvas,
            $fontMetrics
        ) {
            $font = $fontMetrics->get_font("Helvetica", "bold");
            $size = 12;

            $text = "Page $pageNumber of $pageCount page/s";

            $canvas->text(389, 61, $text, $font, $size);
        });

        return $pdf->stream('tesda-order.pdf');
    }

    public function show($program_code)
    {
        
        $orders = TesdaOrder::where('program_code', $program_code)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function destroy($id){
        $order = TesdaOrder::findOrFail($id);
        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'TESDA Order deleted successfully'
        ]);
    }
}
