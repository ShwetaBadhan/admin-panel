<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accessibility;
class AccessibilityApiController extends Controller
{
     public function index()
    {   
        $accessibility = Accessibility::where('is_active', 1)->first();
        return response()->json([

            'accessibility' => $accessibility ? [

                'sub_title' => $accessibility->sub_title,
                'main_title' => $accessibility->main_title,
                'description' => $accessibility->description,
            ] : null,
        ]);
    }
}
