<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityNursingAct extends Model
{
    //
     protected $fillable = [
        'main_title',
        'sub_title',
        'side_title',
        'description_1',
        'points',
        'is_active'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
