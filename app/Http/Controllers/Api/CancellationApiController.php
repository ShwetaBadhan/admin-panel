<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CancellationPolicy;
class CancellationApiController extends Controller
{
     public function index()
    {   
        $cancellationPolicy = CancellationPolicy::where('is_active', 1)->first();
        return response()->json([

            'cancellationPolicy' => $cancellationPolicy ? [
                'sub_title' => $cancellationPolicy->sub_title,
                'main_title' => $cancellationPolicy->main_title,
                'description' => $cancellationPolicy->description,
            ] : null,
        ]);
    }
}
