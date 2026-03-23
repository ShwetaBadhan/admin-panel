<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityNursingFaq extends Model
{
    //
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
