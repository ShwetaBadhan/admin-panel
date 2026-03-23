<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    //
    // use SoftDeletes;
    protected $fillable = [
        'name',
        'icon',
        'parameters',
        'status'
    ];
    protected $casts = [
        'parameters' => 'array',
        'created_at' => 'datetime',
    ];
}
