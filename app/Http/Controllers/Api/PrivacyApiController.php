<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\PrivacyPolicy;

class PrivacyApiController extends Controller
{
    public function index()
    {   
        $privacyPolicy = PrivacyPolicy::where('is_active', 1)->first();
        return response()->json([

            'privacy_policy' => $privacyPolicy ? [

                'sub_title' => $privacyPolicy->sub_title,
                'main_title' => $privacyPolicy->main_title,
                'description' => $privacyPolicy->description,
            ] : null,
        ]);
    }
}