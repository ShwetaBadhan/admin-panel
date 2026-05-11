<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletBalance extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'balance',
        'locked_balance',
        'total_earned',
        'total_withdrawn',
    ];
    
    protected $casts = [
        'balance' => 'decimal:2',
        'locked_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
    ];
    
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    
    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }
}