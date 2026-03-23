<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccreditationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title1', 'icon1', 'title2', 'icon2', 'title3', 'icon3', 'title4', 'icon4'
    ];
}