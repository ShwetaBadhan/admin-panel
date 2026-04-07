<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GrievanceRedressal;

class GrievanceApiController extends Controller
{
   public function index()
    {   
        $grievanceRedressal = GrievanceRedressal::where('is_active', 1)->first();
        return response()->json([

            'grievanceRedressal' => $grievanceRedressal ? [
                'sub_title' => $grievanceRedressal->sub_title,
                'main_title' => $grievanceRedressal->main_title,
                'description' => $grievanceRedressal->description,
            ] : null,
        ]);
    }
}
