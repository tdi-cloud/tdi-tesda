<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverPage extends Model
{
    protected $fillable = ['program_id', 'image'];
    public function program(){
        return $this->belongsTo(Program::class);
    }
}
