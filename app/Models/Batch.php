<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    //
    protected $table = 'batches';
    
    protected $fillable = [
        'program_code','batch', 'status', 'modality', 'venue',
        'date_start', 'date_end', 'time_start',
        'time_end', 'days', 'hours',
    ];

    public function program(){
        return $this->belongsTo(Program::class, 'program_code', 'program_code');
    }

    public function participants(){
        return $this->hasMany(Participant::class,'batch_id','id');
    }

    public function requirements(){
        return $this->hasMany(Requirement::class,'program_code','program_code');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'batch_speakers')
            ->withPivot('role', 'topic')
            ->withTimestamps();
    }

}
