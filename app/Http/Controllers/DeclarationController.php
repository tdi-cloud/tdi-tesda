<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\employees;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DeclarationController extends Controller
{
    /**
     * AJAX: Check if batch has any participants with Complete attendance.
     */
    public function checkCompleters(Batch $batch)
    {
        $hasCompleters = Participant::where('batch_id', $batch->id)
            ->where('attendance', 'Complete')
            ->exists();

        return response()->json([
            'has_completers' => $hasCompleters,
        ]);
    }

    /**
     * AJAX: Search employees for signatory autocomplete.
     */
    public function searchEmployee(Request $request)
    {
        $query = $request->get('q', '');

        $employees = employees::where(function ($q) use ($query) {
                $q->where('FIRSTNAME', 'LIKE', "%{$query}%")
                  ->orWhere('LASTNAME', 'LIKE', "%{$query}%")
                  ->orWhere('EMPCODE', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'EMPCODE', 'FIRSTNAME', 'LASTNAME', 'MI', 'POSITION', 'OFFICE']);

        return response()->json($employees->map(function ($emp) {
            $mi       = ($emp->MI && strtolower(trim($emp->MI)) !== 'n/a' && trim($emp->MI) !== '')
                        ? trim($emp->MI)
                        : '';
            $fullname = trim($emp->FIRSTNAME . ($mi ? ' ' . $mi : '') . ' ' . $emp->LASTNAME);

            return [
                'id'       => $emp->id,
                'label'    => $fullname,
                'fullname' => $fullname,
                'position' => $emp->POSITION,
                'office'   => $emp['OFFICE/DIVISION'] ?? '',
            ];
        }));
    }

    /**
     * Stream the Declaration of Completers PDF.
     */
    public function generatePdf(Request $request, Batch $batch)
    {
        // Validate signatory inputs
        $request->validate([
            'signatory_name'     => 'required|string|max:255',
            'signatory_position' => 'required|string|max:255',
        ]);

        // Load batch with program
        $batch->load('program');

        // Abort if program is not found
        if (!$batch->program) {
            abort(404, 'Program not found for this batch.');
        }

        // Get only Complete participants with employee data
        $participants = Participant::where('batch_id', $batch->id)
            ->where('attendance', 'Complete')
            ->get()
            ->map(function ($p) {
                $employee    = employees::where('EMPCODE', $p->empcode)->first();
                $p->employee = $employee;
                return $p;
            })
            ->filter(fn($p) => $p->employee !== null)
            ->values();

        if ($participants->isEmpty()) {
            abort(403, 'No participants with Complete attendance.');
        }

        // Determine "personnel" vs "personnel and officials"
        // Safely strip non-numeric chars from SG (e.g. "SG-24", "N/A")
        $hasOfficials = $participants->contains(function ($p) {
            $sg = preg_replace('/[^0-9]/', '', $p->employee->SG ?? '');
            return $sg !== '' && (int) $sg >= 24;
        });
        $personnelLabel = $hasOfficials ? 'personnel and officials' : 'personnel';

        // Format dates safely
        try {
            $dateStart = \Carbon\Carbon::parse($batch->date_start)->format('F j, Y');
            $dateEnd   = \Carbon\Carbon::parse($batch->date_end)->format('F j, Y');
        } catch (\Exception $e) {
            $dateStart = $batch->date_start;
            $dateEnd   = $batch->date_end;
        }

        try {
            $start = \Carbon\Carbon::parse($batch->date_start);
            $end   = \Carbon\Carbon::parse($batch->date_end);
        } catch (\Exception $e) {
            $start = null;
            $end   = null;
        }

        if (!$start || !$end) {
            $dateRange = $batch->date_start . ' – ' . $batch->date_end;
        } elseif ($start->equalTo($end)) {
            // Same day
            $dateRange = $start->format('F j, Y');
        } elseif ($start->month === $end->month && $start->year === $end->year) {
            // Same month and year — e.g. "05-06 February 2026"
            $dateRange = $start->format('d') . '-' . $end->format('d') . ' ' . $end->format('F Y');
        } else {
            // Different months — full date both sides
            $dateRange = $start->format('F j, Y') . ' – ' . $end->format('F j, Y');
        }

        $data = [
            'batch'             => $batch,
            'program'           => $batch->program,
            'participants'      => $participants,
            'personnelLabel'    => $personnelLabel,
            'dateRange'         => $dateRange,
            'signatoryName'     => strtoupper($request->signatory_name),
            'signatoryPosition' => $request->signatory_position,
        ];

        $pdf = Pdf::loadView('pdfGenerate.declaration_of_completers', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'Arial',
                'dpi'                  => 150,
                'defaultMediaType'     => 'print',
            ]);

        return $pdf->stream("Declaration_of_Completers_{$batch->batch}.pdf");
    }
}