<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlliedHealthAbout;
use App\Models\AlliedHealthSupport;
use App\Models\AlliedHealthService;
use App\Models\AliiedFaq;
use App\Models\AlliedHealthJourney;



class alliedHealthApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $AlliedHealthAbout = AlliedHealthAbout::first();
        $AlliedHealthSupport = AlliedHealthSupport::first();
        $AlliedHealthJourney = AlliedHealthJourney::first();
        $AlliedHealthService = AlliedHealthService::where('status', 'active')->get();
        $AliiedFaq = AliiedFaq::where('status','active')->get();


        return response()->json([


            'allied_about' => $AlliedHealthAbout ? [

                'title' => $AlliedHealthAbout->main_title,
                'description' => $AlliedHealthAbout->description_1,
                'image' => $this->image($AlliedHealthAbout->image),
            ] : null,
            'allied_support' => $AlliedHealthSupport ? [

                'title' => $AlliedHealthSupport->main_title,
                'description' => $AlliedHealthSupport->description_1,
                'image' => $this->image($AlliedHealthSupport->image),
            ] : null,
            'allied_journey' => $AlliedHealthJourney ? [

                'title' => $AlliedHealthJourney->main_title,
                'description' => $AlliedHealthJourney->description_1,
                'image' => $this->image($AlliedHealthJourney->image),
            ] : null,

            'allied_services' => $AlliedHealthService->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'description' => $service->description,
                    'image' => $this->image($service->image),
                ];
            }),
           
            'faqs' => $AliiedFaq->map(function ($service) {
                return [
                    'question' => $service->question,
                    'answer' => $service->answer,

                ];
            }),


        ]);
    }


}