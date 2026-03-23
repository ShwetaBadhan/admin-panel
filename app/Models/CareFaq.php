<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareFaq extends Model
{
    //
     protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
