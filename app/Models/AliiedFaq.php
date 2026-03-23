<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AliiedFaq extends Model
{
    //
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
