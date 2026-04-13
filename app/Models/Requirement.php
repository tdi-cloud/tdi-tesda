<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Batch;

class Requirement extends Model
{
    protected $appends = ['due_date'];

    protected $fillable = [
        'program_code', 'title', 'description', 'required', 'day_due', 'month_due',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class, 'program_code', 'program_code')->orderBy('date_start', 'asc');
    }

    public function getDueDateAttribute()
    {
        if ($this->batches->isEmpty()) {
            return null;
        }

        return $this->batches->map(function ($batch) {
            $date = Carbon::parse($batch->date_end);

            // DAY-BASED
            if ($this->day_due > 0) {
                $count = 0;

                while ($count < $this->day_due) {
                    $date->addDay();

                    if (!$date->isWeekend()) {
                        $count++;
                    }
                }

                return $date->toDateString();
            }

            // MONTH-BASED
            if ($this->month_due > 0) {
                $date->addMonthsNoOverflow($this->month_due);

                while ($date->isWeekend()) {
                    $date->addDay();
                }

                return $date->toDateString();
            }

            return null;
        })->values(); // 👈 important
    }
}