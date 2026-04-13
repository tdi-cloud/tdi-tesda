<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TESDAOrder extends Model
{
    protected $table = 'tesda_orders';
    protected $fillable = ['program_code', 
    'subject',
    'series',
    'date_issued',
    'effectivity',
    'supersedes',
    'body',
    'with_employees',
    'with_batch',
    'closure',
    'signatory_name',
    'signatory_position'
     ];

     public function program(){
        return $this->belongsTo(Program::class, 'program_code', 'program_code');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'program_code', 'program_code');
    }
}