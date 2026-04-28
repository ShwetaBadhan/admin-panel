<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'discount_price',
        'discount_percentage',
        'size',
        'brand',
        'type',
        'is_organic',
        'stock',
        'in_stock',
        'rating',
        'review_count',
        'images',
        'additional_info',
        'status',
        'featured',
         'cc_points',
    ];

    protected $casts = [
        'images' => 'array',
        'additional_info' => 'array',
        'is_organic' => 'boolean',
        'in_stock' => 'boolean',
        'status' => 'boolean',
        'featured' => 'boolean',
         'cc_points' => 'decimal:2',
    ];

    // Auto generate slug
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Calculate discount percentage
    public function setDiscountPercentageAttribute($value)
    {
        if ($this->price && $this->discount_price) {
            $this->attributes['discount_percentage'] = round((($this->price - $this->discount_price) / $this->price) * 100);
        }
    }

    // Category relationship
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Reviews relationship
    // public function reviews()
    // {
    //     return $this->hasMany(ProductReview::class);
    // }

    // Get first image
    public function getFirstImageAttribute()
    {
        return $this->images ? $this->images[0] : null;
    }

    // Check if on sale
    public function getOnSaleAttribute()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }
    // Accessor: Get CC value in ₹
public function getCcValueInRupeesAttribute(): float
{
    return CcPointSetting::calculatePriceFromCC($this->cc_points);
}

// Helper: Auto-calculate CC from discount price (if not set manually)
public function autoCalculateCcPoints(): void
{
    $price = $this->discount_price ?? $this->price;
    $this->cc_points = CcPointSetting::calculateCCFromPrice($price);
}
}
