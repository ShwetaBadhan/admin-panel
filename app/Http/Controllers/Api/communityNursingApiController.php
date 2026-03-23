<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityAbout;
use App\Models\CommunityService;
use App\Models\CommunityActivity;
use App\Models\CommunityNursingFaq;



class communityNursingApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $communityAbout = CommunityAbout::first();
        $CommunityService = CommunityService::where('status', 'active')->get();
        $CommunityActivity = CommunityActivity::first();
        $CommunityFaq = CommunityNursingFaq::where('status','active')->get();


        return response()->json([


            'community_about' => $communityAbout ? [

                'title' => $communityAbout->main_title,
                'description' => $communityAbout->description_1,
                'points' => $communityAbout->points,
                'image' => $this->image($communityAbout->image),
            ] : null,

            'community_services' => $CommunityService->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'description' => $service->description,
                    'image' => $this->image($service->image),
                ];
            }),
            'community_activity' => $CommunityActivity ? [

                'title' => $CommunityActivity->main_title,
                'sub_title' => $CommunityActivity->sub_title,
                'side_title' => $CommunityActivity->side_title,
                'description' => $CommunityActivity->description_1,
                'points' => $CommunityActivity->points,
            ] : null,
            'faqs' => $CommunityFaq->map(function ($service) {
                return [
                    'question' => $service->question,
                    'answer' => $service->answer,

                ];
            }),


        ]);
    }


}