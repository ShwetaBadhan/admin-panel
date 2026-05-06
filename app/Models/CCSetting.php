<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CCSetting extends Model
{
    protected $table = 'cc_settings';
    protected $fillable = ['value', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    // Helper to quickly fetch active conversion rate
    public static function getActiveRate()
{
    $setting = static::where('is_active', true)->first();
    return $setting ? $setting->value : 60.00; // fallback to default
}
}
