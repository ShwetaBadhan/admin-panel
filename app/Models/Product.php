<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// ✅ Add this if using soft deletes
// use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    // use SoftDeletes; // ✅ Uncomment if using soft deletes
    
    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'short_description',
        'description',
        'uses',
        'directions_for_use',
        'cautions',
        'primary_benefits',
        'ingredients',
        'price',
        'discount_price',
        'cc_points',
        'size',
        'brand',
        'stock',
        'in_stock',
        'status',
        'featured',
        'images', 
        'slug',// ✅ Make sure this is here
    ];
    
    // ✅ Add this casts array - MOST IMPORTANT FIX
    protected $casts = [
        'images' => 'array',  // ✅ This converts JSON ↔ Array automatically
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'cc_points' => 'integer',
        'stock' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
        'in_stock' => 'boolean',
    ];
    
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    
    // ✅ Helper to get first image safely
    public function getFirstImageAttribute()
    {
        $images = $this->images ?? [];
        return $images[0] ?? null;
    }
}