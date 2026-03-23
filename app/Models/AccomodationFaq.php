<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccomodationFaq extends Model
{
    //
      protected $fillable = [
        'question',
        'answer',
        'status',
        'service_id',
    ];

    protected $casts = [
        'service_id' => 'array',
    ];
 
    // Optional scopes (keep if you use them elsewhere)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
