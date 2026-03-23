<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\JsonResponse;

class TeamApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'our_leaders' => Staff::where('tag', 'Leaders')
                ->where('status', 1)
                ->get(),

            'executive_board' => Staff::where('tag', 'Executive Board')
                ->where('status', 1)
                ->get(),

            'management_team' => Staff::where('tag', 'Management Team')
                ->where('status', 1)
                ->get(),
            'medical_advisory_board' => Staff::where('tag', 'Medical Advisory Board')
                ->where('status', 1)
                ->get(),

            'others' => Staff::where('tag', 'Others')
                ->where('status', 1)
                ->get(),
        ]);

    }
}
