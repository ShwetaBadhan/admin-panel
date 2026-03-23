<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings'; // Make sure it points to correct table

    protected $fillable = [
        'company_name', 'email', 'location', 'phone1', 'phone2', 'about', 'social_links',
        'black_logo', 'white_logo', 'backend_logo', 'favicon', 'helpdesk_number'
    ];

    protected $casts = [
        'social_links' => 'array'
    ];
}