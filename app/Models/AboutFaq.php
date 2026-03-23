<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutFaq extends Model
{
    //
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
