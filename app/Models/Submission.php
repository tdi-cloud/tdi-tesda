<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'participant_id',
        'program_code',
        'batch_id',
        'requirement_id',
        'status',
        'file_path',
        'notes',
        'remarks',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'requirement_id');
    }
    
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
