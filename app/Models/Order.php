<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    
    protected $fillable = [
        'user_id',
        'package_id',
        'order_date',
        'total_amount',
        'total_cc_points',
        'status',
        'order_type',
        'refund_policy',
        'payment_mode',
        'note',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_cc_points' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(MlmUser::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}