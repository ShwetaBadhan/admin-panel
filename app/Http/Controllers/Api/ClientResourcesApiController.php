<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\ClientResource;

class ClientResourcesApiController extends Controller{
    public function index(){
        $clientPolicies = ClientResource::where('status','active')->get();
        return response()->json([
            'client_resources'=> $clientPolicies,
        ]);
    }
}