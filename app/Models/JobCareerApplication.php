<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCareerApplication extends Model
{
    protected $table = 'job_career_applications';

    protected $fillable = [
        'job_id',
        'job_title',
        'fullname',
        'email',
        'phone',
        'address',
        'details',
        'resume',
    ];
}
