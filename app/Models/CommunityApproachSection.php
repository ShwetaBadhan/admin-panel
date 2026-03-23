<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityApproachSection extends Model
{
    //
    protected $fillable = [
        'main_title',
        'side_title',
        'sub_title',
        'description_1',
        'points',
        'points_2',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
        'points_2' => 'array',
    ];
}
