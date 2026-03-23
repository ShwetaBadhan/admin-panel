<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Parameter extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'overview',
        'icon',
        'price',
        'status',
        'detail_id'           // ← this is your JSON column
    ];

    protected $casts = [
        'detail_id' => 'array',
        'price'     => 'decimal:2'
    ];

    protected static function booted()
    {
        static::creating(function ($parameter) {
            $parameter->slug = $parameter->slug ?? Str::slug($parameter->title);
        });

        static::updating(function ($parameter) {
            if ($parameter->isDirty('title')) {
                $parameter->slug = Str::slug($parameter->title);
            }
        });
    }

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