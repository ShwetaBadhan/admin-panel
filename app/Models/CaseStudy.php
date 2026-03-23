<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'image',
        'status'
    ];
}
