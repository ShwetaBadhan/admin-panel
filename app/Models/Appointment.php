<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'choosedoctor',
        'selectdepartment',
        'appointmentdate',
        'message',
        'ip',
    ];
}
