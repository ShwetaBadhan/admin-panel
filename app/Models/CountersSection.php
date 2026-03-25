<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountersSection extends Model
{
    protected $fillable = [
        'counters',
        'background_image',
        'background_color',
        'is_active',
    ];

    protected $casts = [
        'counters' => 'array',
        'is_active' => 'boolean',
    ];
}
