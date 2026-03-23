<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->slug = Str::slug($category->name);

            // Ensure unique slug
            $original = $category->slug;
            $count = 1;
            while (static::where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
                $category->slug = $original . '-' . $count++;
            }
        });
    }
}