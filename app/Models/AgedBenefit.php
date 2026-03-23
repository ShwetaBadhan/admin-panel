<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgedBenefit extends Model
{
    //
    protected $fillable = [
        'main_title',
        'side_title',
        'sub_title',
        'description_1',
        'points',
        'image',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
