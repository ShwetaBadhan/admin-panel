<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingPolicy;

class ShippingPolicyApiController extends Controller
{
    public function index()
    {   
        $shippingPolicy = ShippingPolicy::where('is_active', 1)->first();
        return response()->json([

            'shippingPolicy' => $shippingPolicy ? [
                'sub_title' => $shippingPolicy->sub_title,
                'main_title' => $shippingPolicy->main_title,
                'description' => $shippingPolicy->description,
            ] : null,
        ]);
    }
}
