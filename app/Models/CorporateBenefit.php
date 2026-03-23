<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateBenefit extends Model
{
    //
    protected $fillable = [
        'sub_title',
        'main_title',
        'description',
        'image',
        'is_active'
    ];
}
