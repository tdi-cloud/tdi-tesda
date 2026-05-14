<?php

namespace App\Http\Controllers;

use App\Models\RegionalReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Regionalreportcontroller extends Controller
{
    /**
     * GET /api/training-submissions
     * Returns all reports (optionally filtered by year).
     */
    public function index(Request $request)
    {
        $year = $request->query('year');
 
        $query = RegionalReport::query()->orderByDesc('submitted_at');
 
        if ($year) {
            $query->where('year', $year);
        }
 
        $reports = $query->get()->map(fn($r) => $this->format($r));
 
        return response()->json($reports);
    }
 
    /**
     * POST /api/training-submissions
     * Accepts multipart/form-data with a PDF file.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'region'         => 'required|string|max:50',
            'month'          => 'required|string|max:20',
            'year'           => 'required|integer|min:2000|max:2100',
            'notes'          => 'nullable|string|max:1000',
            'pdf'            => 'required|file|mimes:pdf|max:2097152', // 2 GB max
            // 'pdf'            => 'required|file|mimes:pdf|max:10240', // 10 MB max
        ]);
 
        // Prevent duplicate submissions for the same region + month + year
        $exists = RegionalReport::where('region', $validated['region'])
            ->where('month',  $validated['month'])
            ->where('year',   $validated['year'])
            ->exists();
 
        if ($exists) {
            return response()->json([
                'message' => 'A report for this region and month already exists.',
            ], 422);
        }
 
        // Store the uploaded PDF
        $file     = $request->file('pdf');
        $fileName = $file->getClientOriginalName();
        // $filePath = $file->store('regional_reports', 'public'); // storage/app/public/regional_reports
        $filePath = $file->storeAs('regional_reports', $fileName, 'public');
 
        $report = RegionalReport::create([
            'region'       => $validated['region'],
            'month'        => $validated['month'],
            'year'         => $validated['year'],
            'file_name'    => $fileName,
            'file_path'    => $filePath,
            'submitted_at' => now(),
            'notes'        => $validated['notes'] ?? null,
            'added_by'     => Auth::check() ? Auth::user()->empcode : 'unknown',
        ]);
 
        return response()->json($this->format($report), 201);
    }
 
    /**
     * DELETE /api/training-submissions/{id}
     */
    public function destroy($id)
    {
        $report = RegionalReport::findOrFail($id);
 
        // Delete the physical file
        if ($report->file_path && Storage::disk('public')->exists($report->file_path)) {
            Storage::disk('public')->delete($report->file_path);
        }
 
        $report->delete();
 
        return response()->json(['message' => 'Report deleted successfully.']);
    }
 
    /**
     * Normalize a RegionalReport into the shape the frontend expects.
     */
    private function format(RegionalReport $r): array
    {
        return [
            'id'           => $r->id,
            'region'       => $r->region,
            'month'        => $r->month,
            'year'         => $r->year,
            'file_name'    => $r->file_name,
            'file_path'    => $r->file_path,
            'file_size'    => $r->file_path && Storage::disk('public')->exists($r->file_path)
                                ? Storage::disk('public')->size($r->file_path)
                                : 0,
            'submitted_at' => $r->submitted_at?->toISOString(),
            'notes'        => $r->notes,
            'added_by'     => $r->added_by,
            // The frontend still references employee_count; set a default so it doesn't break
            'employee_count' => 0,
        ];
    }
}
