<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    //
    protected $guarded = [];

    public function batches(){
        return $this->hasMany(Batch::class, 'program_code', 'program_code');
    }

    public function coverPages(){
        return $this->hasMany(CoverPage::class);
    }

    public function tesdaOrders(){
        return $this->hasMany(TESDAOrder::class, 'program_code', 'program_code');
    }
}
