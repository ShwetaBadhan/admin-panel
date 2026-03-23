<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\AgedAbout;
use App\Models\AgedBenefit;
use App\Models\AgedService;

class AgedcareApiController extends Controller
{


    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {
        // services
        $AgedServices = AgedService::first();

        return response()->json([
            'aged_about' => [
                'main_title' => AgedAbout::first()?->main_title,
                'description_1' => AgedAbout::first()?->description_1,
                'image' => $this->image(AgedAbout::first()?->image),
                'points' => AgedAbout::first()?->points,
            ],
            'aged_benefit' => [
                'main_title' => AgedBenefit::first()?->main_title,
                'sub_title' => AgedBenefit::first()?->sub_title,
                'side_title' => AgedBenefit::first()?->side_title,
                'description_1' => AgedBenefit::first()?->description_1,
                'image' => $this->image(AgedBenefit::first()?->image),
                'points' => AgedBenefit::first()?->points,
            ],

            'Aged_service' => $AgedServices ? [
                'sub_title' => $AgedServices->sub_title,
                'main_title' => $AgedServices->main_title,

                'cards' => [
                    [
                        'title' => $AgedServices->small_card_1_title,
                        'description' => $AgedServices->small_card_1_content,
                       
                    ],
                    [
                        'title' => $AgedServices->small_card_2_title,
                        'description' => $AgedServices->small_card_2_content,
                    ],
                    [
                        'title' => $AgedServices->small_card_3_title,
                        'description' => $AgedServices->small_card_3_content,
                    ],
                   
                ]
            ] : null,
        ]);
    }
}