<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    //

    protected $fillable = [
        'batch_id',
        'empcode',
        'attendance',
        'hours',
        'requirements',
        'added_by',
        'sort_order',
    ]; 

    public function batch(){
        return $this->belongsTo(Batch::class, 'batch_id','id');
    }

    public function employee(){
        return $this->belongsTo(employees::class,'empcode','EMPCODE');
    }

    public function justification()
    {
        return $this->hasOne(AbsentJustification::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'participant_id');
    }

    

}
