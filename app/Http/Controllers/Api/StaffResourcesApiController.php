<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\StaffResource;

class StaffResourcesApiController extends Controller
{
    public function index()
    {
        $staffPolicies = StaffResource::where('status', 'active')->get();
        return response()->json([
            'staff_resources' => $staffPolicies,
        ]);
    }
}