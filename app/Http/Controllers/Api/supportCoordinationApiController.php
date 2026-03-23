<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportCoordinationAbout;
use App\Models\SupportCoordinationPlan;
use App\Models\SupportCoordinationService;
use App\Models\SupportCoordinationBenefit;
use App\Models\SupportCoordinationFaq;



class supportCoordinationApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $SupportCoordinationAbout = SupportCoordinationAbout::first();
        $SupportCoordinationPlan = SupportCoordinationPlan::where('status', 'active')->get();
        $SupportCoordinationService = SupportCoordinationService::where('status', 'active')->get();
        $SupportCoordinationBenefit = SupportCoordinationBenefit::first();
        $SupportCoordinationFaq = SupportCoordinationFaq::where('status', 'active')->get();


        return response()->json([
            'support_coordination_about' => $SupportCoordinationAbout ? [
                'title' => $SupportCoordinationAbout->main_title,
                'description' => $SupportCoordinationAbout->description_1,
                'image' => $this->image($SupportCoordinationAbout->image),
            ] : null,
            'support_coordination_plan' => $SupportCoordinationPlan->map(function ($plan) {
                return [
                    'id' => $plan->id,
                    'title' => $plan->title,
                    'description' => $plan->description,
                    'image' => $this->image($plan->image),
                ];
            }),
            'support_coordination_service' => $SupportCoordinationService->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'description' => $service->description,
                    'image' => $this->image($service->image),
                ];
            }),

            'support_coordination_benefit' => $SupportCoordinationBenefit ? [
                'title'=> $SupportCoordinationBenefit->main_title,
                'description'=> $SupportCoordinationBenefit->description_1,
                'points'=> $SupportCoordinationBenefit->points,
                'image'=>$this->image($SupportCoordinationBenefit->image),

            ] : null,

            'faqs' => $SupportCoordinationFaq->map(function ($faq) {
                return [
                    'question' => $faq->question,
                    'answer' => $faq->answer,

                ];
            }),






        ]);
    }


}