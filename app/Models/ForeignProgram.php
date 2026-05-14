<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignProgram extends Model
{
    protected $fillable = [

        'program_title',
        'modality',

        'program_start',
        'program_end',

        'online_start',
        'online_end',

        'inperson_start',
        'inperson_end',

        'slots',
        'organizing_sponsor',
        'country',
        'status_of_program',
        'submission_date',
        'interview_date',
        'invited_agencies',
        'attached_agency',
        'embassy_deadline',

    ];
}
