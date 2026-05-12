<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = [
        'template_name',
        'background_image',
        'signature_image',
        'elements'
    ];

    protected $casts = [
        'elements' => 'array'
    ];
}
