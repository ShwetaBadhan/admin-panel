<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class IndependentAccommodation extends Model
{
     protected $fillable = [
        'title',
        'sub_title',
        'description',
        'overview',
        'image',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($item) {
            $item->slug = Str::slug($item->title);
        });
    }

    // Optional scopes (safe to keep)
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
