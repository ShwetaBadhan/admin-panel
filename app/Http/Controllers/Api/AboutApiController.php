<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AboutCare;
use App\Models\Value;
use App\Models\Faq;
use App\Models\WhyChooseSection;



class AboutApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }

    public function index()
    {
        $values = Value::first();
        $faqs = Faq::where('status', 'active')->get();
        return response()->json([
            'about_us' => [
                'sub_title' => AboutCare::first()?->sub_title,
                'main_title' => AboutCare::first()?->main_title,
                'description_1' => AboutCare::first()?->description_1,
                'description_2' => AboutCare::first()?->description_2,
                'feature_1_title' => AboutCare::first()?->feature_1_title,
                'image' => $this->image(AboutCare::first()?->image),
                'image_2' => $this->image(AboutCare::first()?->image_2),
                'icon_1' => $this->image(AboutCare::first()?->icon_1),
            ],
            'why_choose_us' => WhyChooseSection::where('status', 'active')
                ->get()
                ->map(fn($item) => [
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => $this->image($item->image),
                ]),

            'about_values' => $values ? [
                'sub_title' => $values->sub_title,
                'main_title' => $values->main_title,

                'cards' => [
                    [
                        'title' => $values->small_card_1_title,
                        'description' => $values->small_card_1_main_title,
                        'image' => $this->image($values->small_card_1_image),
                    ],
                    [
                        'title' => $values->small_card_2_title,
                        'description' => $values->small_card_2_main_title,
                        'image' => $this->image($values->small_card_2_image),
                    ],
                    [
                        'title' => $values->small_card_3_title,
                        'description' => $values->small_card_3_main_title,
                        'image' => $this->image($values->small_card_3_image),
                    ],
                   
                ]
            ] : null,
            'faqs' => $faqs->map(fn($item) => [
                'question' => $item->question,
                'answer' => $item->answer,
               
            ]),

        ]);
    }
}