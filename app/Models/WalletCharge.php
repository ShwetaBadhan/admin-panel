<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletCharge extends Model
{
    protected $fillable = [
        'wallet_id', 'charge_type', 'charge_mode', 
        'charge_value', 'min_charge', 'max_charge', 'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'charge_value' => 'decimal:2',
        'min_charge' => 'decimal:2',
        'max_charge' => 'decimal:2',
    ];
    
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}