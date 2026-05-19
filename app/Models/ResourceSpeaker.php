<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceSpeaker extends Model
{
    protected $fillable = [
        'program_code',
        'name',
        'position',
        'organization',
        'email',
        'phone',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_code', 'program_code');
    }
}
