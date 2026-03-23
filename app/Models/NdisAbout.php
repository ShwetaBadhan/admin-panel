<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NdisAbout extends Model
{
    //
    protected $fillable = [
        'main_title',
        'description_1',
        'image',
        'is_active'
    ];
}
