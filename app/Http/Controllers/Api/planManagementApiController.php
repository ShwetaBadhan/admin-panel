<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanManagementAbout;
use App\Models\PlanManagementBenefit;
use App\Models\PlanManagementFaq;



class planManagementApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $PlanManagementAbout = PlanManagementAbout::where('status', 'active')->get();
        $PlanManagementBenefit = PlanManagementBenefit::first();
        $PlanManagementFaq = PlanManagementFaq::where('status', 'active')->get();


        return response()->json([

            'plan_benefit' => $PlanManagementBenefit ? [
                'sub_title' => $PlanManagementBenefit->sub_title,
                'main_title' => $PlanManagementBenefit->main_title,

                'cards' => [
                    [
                        'title' => $PlanManagementBenefit->small_card_1_title,
                        'description' => $PlanManagementBenefit->small_card_1_main_title,
                        'image' => $this->image($PlanManagementBenefit->small_card_1_image),

                    ],
                    [
                        'title' => $PlanManagementBenefit->small_card_2_title,
                        'description' => $PlanManagementBenefit->small_card_2_main_title,
                        'image' => $this->image($PlanManagementBenefit->small_card_2_image),

                    ],
                    [
                        'title' => $PlanManagementBenefit->small_card_3_title,
                        'description' => $PlanManagementBenefit->small_card_3_main_title,
                        'image' => $this->image($PlanManagementBenefit->small_card_3_image),

                    ],
                    [
                        'title' => $PlanManagementBenefit->small_card_4_title,
                        'description' => $PlanManagementBenefit->small_card_4_main_title,
                        'image' => $this->image($PlanManagementBenefit->small_card_4_image),

                    ],

                ]
            ] : null,
            'plan_about' => $PlanManagementAbout->map(function ($about) {
                return [
                    'id' => $about->id,
                    'title' => $about->title,
                    'description' => $about->description,
                    'image' => $this->image($about->image),
                ];
            }),
            'faqs' => $PlanManagementFaq->map(function ($faq) {
                return [
                    'question'=>$faq->question,
                    'answer'=>$faq->answer,

                ];
            }),






        ]);
    }


}