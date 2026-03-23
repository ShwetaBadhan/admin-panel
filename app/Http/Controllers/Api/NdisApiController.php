<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

// ndis sections
use App\Models\NdisAbout;
use App\Models\NdisService;
use App\Models\NdisSupport;

class NdisApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }

    public function index()
    {
        return response()->json([
            'ndis_about' => [
                'main_title' => NdisAbout::first()?->main_title,
                'description' => NdisAbout::first()?->description_1,
                'image' => $this->image(NdisAbout::first()?->image),
            ],
            'ndis_service' => [
                'main_title' => NdisService::first()?->main_title,
                'sub_title' => NdisService::first()?->sub_title,
                'description_1' => NdisService::first()?->description_1,
                'image' => $this->image(NdisService::first()?->image),
            ],
            'ndis_support' => [
                'main_title' => NdisSupport::first()?->main_title,
                'description_1' => NdisSupport::first()?->description_1,
                'image' => $this->image(NdisSupport::first()?->image),
                'points'=> NdisSupport::first()?->points,
            ],

        ]);
    }
}