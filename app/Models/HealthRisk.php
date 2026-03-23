<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HealthRisk extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'icon',
        'parameter_id',  // This will now be JSON array
        'status',
        'description'
    ];

    protected $casts = [
        'parameter_id' => 'array',  // THIS IS CRITICAL
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            } else {
                $model->slug = Str::slug($model->slug);
            }

            // Ensure unique slug
            $originalSlug = $model->slug;
            $count = 1;
            while (static::where('slug', $model->slug)->where('id', '!=', $model->id)->exists()) {
                $model->slug = $originalSlug . '-' . $count++;
            }
        });
    }

    // Optional: Get related parameters easily
    public function parameters()
    {
        return Parameter::whereIn('id', $this->parameter_id ?? [])->get();
    }
}