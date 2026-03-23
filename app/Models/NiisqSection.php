<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiisqSection extends Model
{
   
    protected $fillable = [
        'about_content',
        'service_title',
        'service_about',
        'eligibility',
        'points',
        'image_1',
        'image_2',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
