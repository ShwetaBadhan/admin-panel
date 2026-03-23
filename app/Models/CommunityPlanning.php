<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityPlanning extends Model
{
    //
    protected $fillable = [
        'main_title',
        'sub_title',
        'note',
        'description_1',
        'points',
        'image',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
