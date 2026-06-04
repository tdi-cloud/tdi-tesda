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

    public function requirements()
    {
        return $this->hasMany(Requirement::class, 'program_code', 'program_code');
    }

    public function supportingDocuments()
    {
        return $this->hasMany(ProgramSupportingDocument::class);
    }

    public function resourceSpeakers()
    {
        return $this->hasMany(ResourceSpeaker::class, 'program_code', 'program_code');
    }

    public function hasBatches(): bool
    {
        return $this->batches()->exists();
    }

    public function competencies()
    {
        return $this->hasMany(ProgramCompetency::class, 'program_id');
    }
}
