<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NdisSupport extends Model
{
    //
    protected $fillable = [
        'main_title',
        'description_1',
        'points',
        'image',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
