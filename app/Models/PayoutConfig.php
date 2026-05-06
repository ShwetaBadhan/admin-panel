<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutConfig extends Model
{
    protected $fillable = ['products_for_payout', 'cc_per_product', 'cc_to_currency_rate', 'is_active'];
    
    protected $casts = [
        'cc_to_currency_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    
    public static function current(): self
    {
        return static::where('is_active', true)->latest()->firstOrFail();
    }
    
    public function getThresholdCC(): int
    {
        return $this->products_for_payout * $this->cc_per_product; // 40 * 20 = 800 CC
    }
    
    public function ccToCurrency(float $cc): float
    {
        return round($cc * $this->cc_to_currency_rate, 2);
    }
}