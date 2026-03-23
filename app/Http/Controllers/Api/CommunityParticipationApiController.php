<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityAbout;
use App\Models\CommunityBenefit;
use App\Models\CommunityApproachSection;
use App\Models\CommunityService;
use App\Models\CommunitySupport;
use App\Models\CommunityActivity;
use App\Models\CommunityPlanning;
use App\Models\CommunityWork;
use App\Models\CommunityEligiblilityFaq;

class CommunityParticipationApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $communityAbout = CommunityAbout::first();
        $communityBenefit = CommunityBenefit::first();
        $communityApproachSection = CommunityApproachSection::first();
        $communityService = CommunityService::where('status', 'active')->get();
        $communitySupport = CommunitySupport::where('status', 'active')->get();
        $communityActivity = CommunityActivity::first();
        $communityPlanning = CommunityPlanning::first();
        $communityWork = CommunityWork::first();
        $communityEligiblilityFaq = CommunityEligiblilityFaq::where('status', 'active')->get();

        return response()->json([


            'community_about' => $communityAbout ? [

                'main_title' => $communityAbout->main_title,
                'description_1' => $communityAbout->description_1,
                'points' => $communityAbout->points,
                'image' => $this->image($communityAbout->image),
            ] : null,

            'communityBenefit' => $communityBenefit ? [
                'sub_title' => $communityBenefit->sub_title,
                'main_title' => $communityBenefit->main_title,
                'side_title' => $communityBenefit->side_title,


                'cards' => [
                    [
                        'title' => $communityBenefit->small_card_1_title,
                        'main_title' => $communityBenefit->small_card_1_main_title,
                        'image' => $this->image($communityBenefit->small_card_1_image),

                    ],
                    [
                        'title' => $communityBenefit->small_card_2_title,
                        'main_title' => $communityBenefit->small_card_2_main_title,
                        'image' => $this->image($communityBenefit->small_card_2_image),
                    ],
                    [
                        'title' => $communityBenefit->small_card_3_title,
                        'main_title' => $communityBenefit->small_card_3_main_title,
                        'image' => $this->image($communityBenefit->small_card_3_image),

                    ],
                    [
                        'title' => $communityBenefit->small_card_4_title,
                        'main_title' => $communityBenefit->small_card_4_main_title,
                        'image' => $this->image($communityBenefit->small_card_4_image),

                    ],

                ]
            ] : null,
            'communityApproachSection' => $communityApproachSection ? [
                'sub_title' => $communityApproachSection->sub_title,
                'main_title' => $communityApproachSection->main_title,
                'side_title' => $communityApproachSection->side_title,
                'description_1' => $communityApproachSection->description_1,
                'points' => $communityApproachSection->points,
                'points_2' => $communityApproachSection->points_2,



            ] : null,
            'communityService' => $communityService->map(function ($service) {
                return [
                    'title' => $service->title,
                    'description' => $service->description,
                    'image' => $this->image($service->image),
                ];
            }),
            'communitySupport' => $communitySupport->map(function ($service) {
                return [
                    'title' => $service->main_title,
                    'points' => $service->points,
                    'image' => $this->image($service->image),
                ];
            }),

            'communityActivity' => $communityActivity ? [
                'sub_title' => $communityActivity->sub_title,
                'main_title' => $communityActivity->main_title,
                'side_title' => $communityActivity->side_title,
                'description_1' => $communityActivity->description_1,
                'points' => $communityActivity->points,
                'image' => $this->image($communityActivity->image),


            ] : null,
            'communityPlanning' => $communityPlanning ? [
                'sub_title' => $communityPlanning->sub_title,
                'main_title' => $communityPlanning->main_title,
                'note' => $communityPlanning->note,
                'description_1' => $communityPlanning->description_1,
                'points' => $communityPlanning->points,
                'image' => $this->image($communityPlanning->image),


            ] : null,
            'communityWork' => $communityWork ? [
                'sub_title' => $communityWork->sub_title,
                'main_title' => $communityWork->main_title,



                'cards' => [
                    [
                        'title' => $communityWork->small_card_1_title,
                        'main_title' => $communityWork->small_card_1_main_title,


                    ],
                    [
                        'title' => $communityWork->small_card_2_title,
                        'main_title' => $communityWork->small_card_2_main_title,

                    ],
                    [
                        'title' => $communityWork->small_card_3_title,
                        'main_title' => $communityWork->small_card_3_main_title,


                    ],
                    [
                        'title' => $communityWork->small_card_4_title,
                        'main_title' => $communityWork->small_card_4_main_title,


                    ],

                ]
            ] : null,
            'communityEligiblilityFaq' => $communityEligiblilityFaq->map(function ($service) {
                return [
                    'question' => $service->question,
                    'answer' => $service->answer,

                ];
            }),

        ]);
    }


}
