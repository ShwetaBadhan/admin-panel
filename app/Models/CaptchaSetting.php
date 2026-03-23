<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// app/Models/CaptchaSetting.php

class CaptchaSetting extends Model
{
    protected $fillable = [
        'type', 'site_key', 'secret_key', 'domain',
        'cloudflare_active', 'google_active'
    ];

    protected $casts = [
        'cloudflare_active' => 'boolean',
        'google_active'     => 'boolean',
    ];

    // Helper scopes
    public function scopeCloudflare($query)
    {
        return $query->where('type', 'cloudflare')->first();
    }

    public function scopeGoogle($query)
    {
        return $query->where('type', 'google')->first();
    }
}