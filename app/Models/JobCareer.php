<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobCareer extends Model
{
    protected $table = 'job_careers';

    protected $fillable = [
        'title',
        'department',
        'type',
        'location',
        'description',
        'experience',
        'qualification',
        'salary_range',
        'is_featured',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    // Auto generate slug
    protected static function booted()
    {
        static::saving(function ($job) {
            $job->slug = Str::slug($job->title . '-' . $job->id ?? uniqid());
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}