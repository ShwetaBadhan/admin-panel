<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use App\Models\SliderImage;
use App\Models\AboutCare;
use App\Models\ActAbout;
use App\Models\HowWork;
use App\Models\WhyChooseSection;
use App\Models\Abouttwo;
use App\Models\CaseStudy;
use App\Models\ServicesSection;
use App\Models\Testimonials;
use App\Models\Blog;
use App\Models\Partner;


class HomeApiController extends Controller
{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }

    public function index()
    {
        $howWork = HowWork::first();
        $service = ServicesSection::first();
        $testimonials = Testimonials::where('status', 'active')->get();
        $caseStudiesQuery = CaseStudy::where(function ($q) {
            $q->where('status', 'active')
                ->orWhereNull('status')
                ->orWhere('status', '');
        })
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        if ($caseStudiesQuery->isEmpty()) {
            $caseStudies = collect([
                [
                    'title' => 'Empowering Independence',
                    'description' => 'Supporting individuals to manage daily life with confidence through coordinated care and guidance.',
                    'image' => asset('images/1.jpg'),
                ],
                [
                    'title' => 'Care That Connects',
                    'description' => 'Bridging healthcare and home support to create safe, reliable, and person-centred solutions.',
                    'image' => asset('images/1.jpg'),
                ],
                [
                    'title' => 'Living With Confidence',
                    'description' => 'Helping participants regain control, dignity, and independence in everyday living.',
                    'image' => asset('images/1.jpg'),
                ],
            ]);
        } else {
            $caseStudies = $caseStudiesQuery->map(function ($item) {
                return [
                    'title' => $item->title ?? 'Case Study',
                    'description' => $item->description ?? 'Details will be updated soon.',
                    'image' => $item->image
                        ? asset('storage/' . $item->image)
                        : asset('images/1.jpg'),
                ];
            });
        }
        $blogs = Blog::where('status', 'active')->get();
        return response()->json([


            'slider' => SliderImage::where('status', 'active')
                ->get()
                ->map(fn($item) => [
                    'image' => asset('storage/' . $item->image),
                ]),
            'partner' => Partner::where('status', 'active')
                ->get()
                ->map(fn($item) => [
                    'image' => asset('storage/' . $item->image),
                ]),


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



            'about_act' => [
                'main_title' => ActAbout::first()?->main_title,
                'description_1' => ActAbout::first()?->description_1,
                'image' => $this->image(ActAbout::first()?->image),
            ],



            'how_we_work' => $howWork ? [
                'sub_title' => $howWork->sub_title,
                'main_title' => $howWork->main_title,
                'side_title' => $howWork->side_title,
                'side_image' => $this->image($howWork->side_image),

                'cards' => [
                    [
                        'title' => $howWork->small_card_1_title,
                        'description' => $howWork->small_card_1_main_title,
                        'image' => $this->image($howWork->small_card_1_image),
                    ],
                    [
                        'title' => $howWork->small_card_2_title,
                        'description' => $howWork->small_card_2_main_title,
                        'image' => $this->image($howWork->small_card_2_image),
                    ],
                    [
                        'title' => $howWork->small_card_3_title,
                        'description' => $howWork->small_card_3_main_title,
                        'image' => $this->image($howWork->small_card_3_image),
                    ],
                    [
                        'title' => $howWork->small_card_4_title,
                        'description' => $howWork->small_card_4_main_title,
                        'image' => $this->image($howWork->small_card_4_image),
                    ],
                ]
            ] : null,




            'why_choose_us' => WhyChooseSection::where('status', 'active')
                ->get()
                ->map(fn($item) => [
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => $this->image($item->image),
                ]),



            'about_two' => [
                'main_title' => AboutTwo::first()?->main_title,
                'description' => AboutTwo::first()?->description_1,
                'image' => $this->image(AboutTwo::first()?->image),
            ],



            'case_study' => $caseStudies,



            'service' => $service ? [
                'sub_title' => $service->subtitle,
                'main_title' => $service->main_heading,

                'cards' => !empty($service->service_items) ? array_map(function ($item) {
                    return [
                        'title' => $item['title'] ?? '',
                        'description' => $item['description'] ?? '',
                        'image' => $this->image($item['image'] ?? null),
                    ];
                }, $service->service_items) : [],

                // Active Item Details (Center Content)
                'active_item' => [

                    'title' => $service->active_item_title,
                    'description' => $service->active_item_description,
                    'read_more_link' => $service->read_more_link,
                ],
                'icon' => $this->image($service->icon),
                // Main Image (Right Side Circle)
                'main_image' => $this->image($service->image),

            ] : null,



            'testimonials' => $testimonials->map(fn($item) => [
                'name' => $item->name,
                'designation' => $item->designation,
                'message' => $item->message,
                'quote' => $item->quote,
                'image' => $this->image($item->image),
                'photo' => $this->image($item->photo),
            ]),
            'blogs' => $blogs,

        ]);
    }
}
