<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCoordinationFaq extends Model
{
    //
     protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
