<?php

namespace App\Http\Controllers;

use App\Models\ForeignProgram;
use Illuminate\Http\Request;

class ForeignController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([

            'program_title' => 'required',

            'modality' => 'required',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Hybrid Logic
        |--------------------------------------------------------------------------
        */

        if ($request->modality == 'HYBRID') {

            $programStart = $request->online_start;
            $programEnd = $request->inperson_end;

        } else {

            $programStart = $request->program_start;
            $programEnd = $request->program_end;

        }

        ForeignProgram::create([

            'program_title' => $request->program_title,

            'modality' => $request->modality,

            'program_start' => $programStart,
            'program_end' => $programEnd,

            'online_start' => $request->online_start,
            'online_end' => $request->online_end,

            'inperson_start' => $request->inperson_start,
            'inperson_end' => $request->inperson_end,

            'slots' => $request->slots,

            'organizing_sponsor' => $request->organizing_sponsor,

            'country' => $request->country,

            'status_of_program' => $request->status_of_program,

            'submission_date' => $request->submission_date,

            'interview_date' => $request->interview_date,

            'invited_agencies' => $request->invited_agencies,

            'attached_agency' => $request->attached_agency,

            'embassy_deadline' => $request->embassy_deadline,

        ]);

        return back()->with('success', 'Program added successfully.');

    }
}
