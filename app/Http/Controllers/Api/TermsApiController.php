<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\TermsCondition;

class TermsApiController extends Controller
{
    public function index()
    {   
        $termsConditions = TermsCondition::where('is_active', 1)->first();
        return response()->json([

            'terms_conditions' => $termsConditions ? [

                'sub_title' => $termsConditions->sub_title,
                'main_title' => $termsConditions->main_title,
                'description' => $termsConditions->description,
            ] : null,
        ]);
    }
}