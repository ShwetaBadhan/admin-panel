<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayoutBalance extends Model
{
    protected $fillable = [
    'mlm_user_id',
    'available_balance',
    'locked_balance',
    'total_earned',
    'total_withdrawn',
    'cc_balance',
    'left_cc',        // ✅ Add this
    'right_cc',       // ✅ Add this
    'total_matched_cc', // ✅ Add this
    'is_payout_eligible',
];

protected $casts = [
    'available_balance' => 'decimal:2',
    'locked_balance' => 'decimal:2',
    'total_earned' => 'decimal:2',
    'total_withdrawn' => 'decimal:2',
    'cc_balance' => 'decimal:2',
    'left_cc' => 'decimal:2',    // ✅ Add this
    'right_cc' => 'decimal:2',   // ✅ Add this
    'total_matched_cc' => 'decimal:2', // ✅ Add this
    'is_payout_eligible' => 'boolean',
];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'mlm_user_id');
    }
    
    public function getWithdrawableBalance(): float
    {
        return $this->is_payout_eligible ? $this->available_balance : 0;
    }
    
    public function getTotalBalance(): float
    {
        return $this->available_balance + $this->locked_balance;
    }
}