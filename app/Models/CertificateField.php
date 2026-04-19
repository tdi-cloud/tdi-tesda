<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateField extends Model
{
    protected $fillable = [
        'template_id',
        'field_name',
        'x',
        'y',
        'font_size',
        'font_weight',
        'text_align'
    ];

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }
}
