<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutTransaction extends Model
{
    protected $fillable = [
        'mlm_user_id', 'type', 'cc_amount', 'currency_amount', 
        'status', 'description', 'meta',
    ];
    
    protected $casts = [
        'cc_amount' => 'decimal:2',
        'currency_amount' => 'decimal:2',
        'meta' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'mlm_user_id');
    }
}
