<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunitySupport extends Model
{
    
     protected $fillable = [
        'main_title',
        'points',
        'image',
        'status'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
