<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeCareService;
use App\Models\HomeCareAbout;
use App\Models\HomeCare2Service;
use App\Models\HomeCareDifference;
use App\Models\HomeCareCommunity;

class HomeServiceApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {
        $whoSupportSection = HomeCareService::first();
        $homeAbout = HomeCareAbout::first();
        $homeServices = HomeCare2Service::where('status', 'active')->get();
        $homeDifferences = HomeCareDifference::first();
        $homeCommunity = HomeCareCommunity::where('status', 'active')->get();

        return response()->json([

            'who_support' => $whoSupportSection ? [
                'sub_title' => $whoSupportSection->sub_title,
                'main_title' => $whoSupportSection->main_title,


                'cards' => [
                    [
                        'title' => $whoSupportSection->small_card_1_title,
                        'description' => $whoSupportSection->small_card_1_main_title,
                        'image' => $this->image($whoSupportSection->small_card_1_image),
                    ],
                    [
                        'title' => $whoSupportSection->small_card_2_title,
                        'description' => $whoSupportSection->small_card_2_main_title,
                        'image' => $this->image($whoSupportSection->small_card_2_image),
                    ],
                    [
                        'title' => $whoSupportSection->small_card_3_title,
                        'description' => $whoSupportSection->small_card_3_main_title,
                        'image' => $this->image($whoSupportSection->small_card_3_image),
                    ],

                ]
            ] : null,
            'home_about' => $homeAbout ? [

                'main_title' => $homeAbout->main_title,
                'description_1' => $homeAbout->description_1,
                'image' => $this->image($homeAbout->image),
            ] : null,
            'home_services' => $homeServices->map(function ($service) {
                return [
                    'title' => $service->title,
                    'description' => $service->description,
                    'points' => $service->points,
                ];
            }),
            'differences' => $homeDifferences ? [
                'sub_title' => $homeDifferences->sub_title,
                'main_title' => $homeDifferences->main_title,
                'description' => $homeDifferences->description,
                'side_image' => $this->image($homeDifferences->side_image),

                'cards' => [
                    [
                        'title' => $homeDifferences->small_card_1_title,
                        'description' => $homeDifferences->small_card_1_main_title,

                    ],
                    [
                        'title' => $homeDifferences->small_card_2_title,
                        'description' => $homeDifferences->small_card_2_main_title,

                    ],
                    [
                        'title' => $homeDifferences->small_card_3_title,
                        'description' => $homeDifferences->small_card_3_main_title,

                    ],

                ]
            ] : null,
            'home_community' => $homeCommunity->map(function ($community): array {
                return [
                    'title' => $community->title,
                    'description' => $community->description,
                   
                ];
            }),

        ]);
    }


}
