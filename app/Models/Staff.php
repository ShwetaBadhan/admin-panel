<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'fullname',
        'image',
        'status',
        'designation',
        'tag',
        'facebook',
        'instagram',
        'linkedin',
        'twitter',

    ];
}
