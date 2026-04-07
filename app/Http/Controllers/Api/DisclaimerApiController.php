<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disclaimer;
use Illuminate\Http\Request;

class DisclaimerApiController extends Controller
{
    public function index() {   
        $disclaimer = Disclaimer::where('is_active', 1)->first();
        return response()->json([

            'disclaimer' => $disclaimer ? [
                'sub_title' => $disclaimer->sub_title,
                'main_title' => $disclaimer->main_title,
                'description' => $disclaimer->description,
            ] : null,
        ]);
    }
}
