<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanManagementFaq extends Model
{
    //
    protected $fillable = [
        'question',
        'answer',
        'status'
    ];
}
