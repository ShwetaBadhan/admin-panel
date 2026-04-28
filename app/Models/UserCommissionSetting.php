<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class UserCommissionSetting extends Model
{
    protected $fillable = [
        'mlm_user_id',
        'commission_percentage',
        'amount_per_bottle',
        'is_active',
        'activated_at',
        'deactivated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
        'amount_per_bottle' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'mlm_user_id');
    }

    // Helper: auto-set amount based on percentage
    public static function calculateAmountPerBottle(int $percentage): float
    {
        return ($percentage === 20) ? 200.00 : 100.00;
    }
}
