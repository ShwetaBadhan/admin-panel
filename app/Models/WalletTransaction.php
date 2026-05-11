<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'user_id',
        'type', // credit, debit, adjustment
        'amount',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
        'meta',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'meta' => 'array',
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