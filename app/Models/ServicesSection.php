<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicesSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle',
        'main_heading',
        'service_items',
        'icon',
        'active_item_title',
        'active_item_description',
        'read_more_link',
        'image',
        'is_active',
    ];

    /**
     * Cast attributes to native types.
     * Important: This automatically converts the JSON column to a PHP Array
     */
    protected $casts = [
        'service_items' => 'array',
        'is_active' => 'boolean',
    ];
}