<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanManagementAbout extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'image',
        'status'
    ];
}
