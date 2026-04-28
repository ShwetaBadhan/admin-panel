<?php
// app/Models/CcPointSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CcPointSetting extends Model
{
    protected $fillable = [
        'conversion_rate',
        'currency',
        'is_active',
        'description',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Get current active setting (singleton pattern)
    public static function getCurrent(): ?self
    {
        return static::where('is_active', true)->latest()->first();
    }

    // Calculate CC from price: CC = Price / Rate
    public static function calculateCCFromPrice(float $price): float
    {
        $setting = static::getCurrent();
        if (!$setting || $setting->conversion_rate <= 0) {
            return 0;
        }
        return round($price / $setting->conversion_rate, 2);
    }

    // Calculate Price from CC: Price = CC * Rate
    public static function calculatePriceFromCC(float $cc): float
    {
        $setting = static::getCurrent();
        if (!$setting) {
            return 0;
        }
        return round($cc * $setting->conversion_rate, 2);
    }
}