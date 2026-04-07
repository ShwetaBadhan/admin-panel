<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceRedressal extends Model
{
    protected $fillable = [
        'sub_title',
        'main_title',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean', // Ensures proper type casting
    ];
}
