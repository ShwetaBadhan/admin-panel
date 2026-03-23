<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'author', 'description', 'image',
        'published_at', 'category_ids', 'status' , 'benefits' , 'quote'
    ];

    protected $casts = [
        'category_ids' => 'array',
        'published_at' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($blog) {
            $blog->slug = Str::slug($blog->title);

            $original = $blog->slug;
            $count = 1;
            while (static::where('slug', $blog->slug)->where('id', '!=', $blog->id)->exists()) {
                $blog->slug = $original . '-' . $count++;
            }
        });
    }

    // THIS IS THE FIX – Real Eloquent Relationship
    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_blog_category', 'blog_id', 'blog_category_id');
    }
}