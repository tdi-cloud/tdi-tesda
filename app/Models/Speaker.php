<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    //
    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'batch_speakers')
            ->withPivot('role', 'topic')
            ->withTimestamps();
    }
}
