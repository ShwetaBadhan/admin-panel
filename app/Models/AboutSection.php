<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_title',
        'main_title',
        'description_1',
        'description_2',
        'image',
        'icon_1',
        'icon_2',
        'feature_1_title',
        'feature_1_description',
        'feature_2_title',
        'feature_2_description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }
}