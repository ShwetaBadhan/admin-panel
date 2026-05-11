<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name', 'code', 'currency_code', 'eligibility', 'type', 
        'is_active', 'min_balance', 'max_balance', 'description'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'min_balance' => 'decimal:2',
        'max_balance' => 'decimal:2',
    ];
    
    public function configuration()
    {
        return $this->hasOne(WalletConfiguration::class);
    }
    
    public function charges()
    {
        return $this->hasMany(WalletCharge::class);
    }
    
    public function balances()
    {
        return $this->hasMany(WalletBalance::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}