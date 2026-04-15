<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SpillingPreference extends Model
{
    protected $table = 'spilling_preferences';
    
    protected $fillable = ['mlm_user_id', 'preference'];
    
    protected $casts = [
        'preference' => 'string', // 'LEFT', 'RIGHT', 'HOLDING_TANK'
    ];

    // 🔗 MLM User
    public function mlmUser(): BelongsTo
    {
        return $this->belongsTo(MlmUser::class, 'mlm_user_id');
    }
}
