<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subparameter extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'parameter_id',
        'test_ids',
        'price',
        'image',
        'description',
        'status'
    ];

    protected $casts = [
        'parameter_id' => 'array',
        'test_ids'     => 'array',
        'price'        => 'decimal:2'
    ];

    // Auto generate slug
    protected static function booted()
    {
        static::saving(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }

    // ACCESSORS — PERFECT
    public function getParametersAttribute()
    {
        return Parameter::whereIn('id', $this->parameter_id ?? [])->get();
    }

    public function getTestsAttribute()
    {
        return Test::whereIn('id', $this->test_ids ?? [])->get();
    }

    // SCOPES — VERY USEFUL
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // EXTRA HELPER: Check if subparameter has a specific parameter
    public function hasParameter($parameterId)
    {
        return is_array($this->parameter_id) && in_array($parameterId, $this->parameter_id);
    }

    // EXTRA HELPER: Check if subparameter has a specific test
    public function hasTest($testId)
    {
        return is_array($this->test_ids) && in_array($testId, $this->test_ids);
    }
}