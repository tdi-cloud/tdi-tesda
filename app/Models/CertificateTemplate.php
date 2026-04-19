<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = ['name', 'background'];

    public function fields()
    {
        return $this->hasMany(CertificateField::class, 'template_id');
    }
}
