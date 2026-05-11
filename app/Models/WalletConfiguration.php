<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletConfiguration extends Model
{
    protected $fillable = [
        'wallet_id', 'payout_schedule', 'payout_execution_day',
        'refund_window_days', 'min_withdraw_amount', 'max_payouts_per_batch',
        'withdraw_cooldown_days', 'start_window', 'end_window',
        'auto_payout', 'processing_fee_percent', 'processing_fee_fixed'
    ];
    
    protected $casts = [
        'auto_payout' => 'boolean',
        'refund_window_days' => 'integer',
        'min_withdraw_amount' => 'decimal:2',
        'processing_fee_percent' => 'decimal:2',
        'processing_fee_fixed' => 'decimal:2',
    ];
    
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}