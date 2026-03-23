<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CareCoordinationAbout;
use App\Models\CareFaq;


class careCoordinationApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $careCoordinationAbout = CareCoordinationAbout::first();
        $faqs = CareFaq::where('status', 'active')->get();


        return response()->json([


            'coordination_about' => $careCoordinationAbout ? [

                'title' => $careCoordinationAbout->main_title,
                'description' => $careCoordinationAbout->description_1,
                'overview' => $careCoordinationAbout->description_2,
                'image' => $this->image($careCoordinationAbout->image),
            ] : null,

            'faqs' => $faqs->map(function ($service) {
                return [
                    'question' => $service->question,
                    'answer' => $service->answer,
                    
                ];
            }),

        ]);
    }


}