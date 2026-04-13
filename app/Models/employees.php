<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    //


    public function user(){
        return $this->hasOne(User::class,"EMPCODE","empcode");
    }

    public function participant(){
        return $this->hasOne(Participant::class,"EMPCODE","empcode");
    }
}
