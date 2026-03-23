<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutCare extends Model
{
    //
    protected $fillable = [
        'sub_title',
        'main_title',
        'description_1',
        'is_active',
        'image',
        'image_2',
        'icon_1',
        'description_2',
        'feature_1_title'
    ];
}
