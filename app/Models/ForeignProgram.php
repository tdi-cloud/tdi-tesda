<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_title',
        'program_start',
        'program_end',
        'slots',
        'modality',
        'online_start',
        'online_end',
        'inperson_start',
        'inperson_end',
        'organizing_sponsor',
        'status',
        'submission_date',
        'embassy_deadline',   // ← new
        'interview_date',
        'invited_agencies',
        'attached_agency',
    ];

    protected $casts = [
        'program_start'    => 'date',
        'program_end'      => 'date',
        'online_start'     => 'date',
        'online_end'       => 'date',
        'inperson_start'   => 'date',
        'inperson_end'     => 'date',
        'submission_date'  => 'date',
        'embassy_deadline' => 'date',   // ← new
        'interview_date'   => 'date',
    ];

    public function participants()
    {
        return $this->hasMany(ForeignParticipant::class);
    }

    public static function statusOptions(): array
    {
        return [
            'for_dissemination'    => 'For Dissemination',
            'waiting_for_nominees' => 'Waiting for Nominees',
            'for_interview'        => 'For Interview',
            'for_endorsement'      => 'For Endorsement',
            'no_nominee'           => 'No Nominee',
            'waiting_for_result'   => 'Waiting for Result',
            'ongoing'              => 'Ongoing',
            'concluded'            => 'Concluded',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }
}