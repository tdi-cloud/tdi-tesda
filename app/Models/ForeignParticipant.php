<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForeignParticipant extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'foreign_program_id',
        'name',
        'sex',
        'position',
        'agency',
        'contact_no',
        'email',
        'status',
    ];
 
    public function program()
    {
        return $this->belongsTo(ForeignProgram::class, 'foreign_program_id');
    }
 
    public static function statusOptions(): array
    {
        return [
            'endorsed'       => 'Endorsed',
            'waiting_result' => 'Waiting Result',
            'not_endorsed'   => 'Not Endorsed',
            'accepted'       => 'Accepted',
            'regret'         => 'Regret',
            'cancelled'      => 'Cancelled',
        ];
    }
 
    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status] ?? $this->status;
    }
}
