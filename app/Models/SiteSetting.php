<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    protected $fillable = ['popup_image', 'ads_image', 'popup_enabled', 'ads_enabled'];

    public static function getSettings()
    {
        return self::find(1) ?? self::create(['id' => 1]);
    }
}