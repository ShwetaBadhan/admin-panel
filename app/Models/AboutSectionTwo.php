<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSectionTwo extends Model
{
    protected $guarded = [];

    public static function getSection()
    {
        return static::firstOrCreate([]);
    }
}