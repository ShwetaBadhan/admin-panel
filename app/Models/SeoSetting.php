<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    //
    protected $fillable = [
        'title',
        'page',
        'description',
        'keywords'
    ];
}
