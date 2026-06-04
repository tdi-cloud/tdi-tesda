<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCompetency extends Model
{
    protected $table = 'program_competency';

    protected $fillable = [
        'program_id',
        'domain',
        'competency',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

}
