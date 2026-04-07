<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SystemApiController extends Controller
{
   public function index()
{   
    $settings = Setting::first();
    
    if (!$settings) {
        return response()->json(['systemSetting' => null]);
    }
    
    // ✅ Helper: Convert relative paths to full URLs
    $makeUrl = fn($path) => $path ? (filter_var($path, FILTER_VALIDATE_URL) ? $path : asset($path)) : null;
    
    return response()->json([
        'systemSetting' => [
            'company_name' => $settings->company_name,
            'about' => $settings->about,
            'phone1' => $settings->phone1,
            'phone2' => $settings->phone2,
            'helpdesk_number' => $settings->helpdesk_number,
            'email' => $settings->email,
            'location' => $settings->location,
            
            // ✅ Convert image paths to full URLs
            'black_logo' => $makeUrl($settings->black_logo),
            'white_logo' => $makeUrl($settings->white_logo),
            'backend_logo' => $makeUrl($settings->backend_logo),
            'favicon' => $makeUrl($settings->favicon),
            'cover_image' => $makeUrl($settings->cover_image),
            
            'social_links' => $settings->social_links,
        ],
    ]);
}
}