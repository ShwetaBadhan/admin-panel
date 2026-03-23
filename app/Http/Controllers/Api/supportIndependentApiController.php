<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndependentAbout;
use App\Models\SupportApplySection;
use App\Models\IndependentAccommodation;
use App\Models\AccommodationGallery;
use App\Models\AccomodationFaq;


class supportIndependentApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {

        $IndependentAbout = IndependentAbout::first();
        $SupportApplySection = SupportApplySection::first();
        $IndependentAccommodation = IndependentAccommodation::where('status', 'active')->get();
        $AccommodationGallery = AccommodationGallery::where('status', 'active')->get();
        $AccomodationFaq = AccomodationFaq::where('status', 'active')->get();


        return response()->json([


            'independent_about' => $IndependentAbout ? [

                'title' => $IndependentAbout->main_title,
                'description' => $IndependentAbout->description_1,
                'image' => $this->image($IndependentAbout->image),
            ] : null,
            'support_apply' => $SupportApplySection ? [

                'title' => $SupportApplySection->main_title,
                'description' => $SupportApplySection->description_1,
                'image' => $this->image($SupportApplySection->image),
            ] : null,
            'support_services' => $IndependentAccommodation->map(function ($service) {
                return [
                    'id' => $service->id, 
                    'title' => $service->title,
                    'slug' => $service->slug,
                    'description' => $service->description,
                    'overview' => $service->overview,
                    'image' => $this->image($service->image),
                ];
            }),

            'accommodation_gallery' => $AccommodationGallery->map(function ($service) {
                return [
                    'title' => $service->title,
                    'service_id' => array_map('intval', $service->service_id),
                    'image' => $this->image($service->image),
                ];
            }),

            'accommodation_faq' => $AccomodationFaq->map(function ($service) {
                return [
                    'question' => $service->question,
                    'answer' => $service->answer,
                    'service_id' => array_map('intval', $service->service_id),
                ];
            }),



        ]);
    }


}