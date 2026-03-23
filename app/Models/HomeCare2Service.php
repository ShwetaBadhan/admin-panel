<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCare2Service extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'points',
        'status'
    ];
    protected $casts = [
        'points' => 'array',
    ];
}
