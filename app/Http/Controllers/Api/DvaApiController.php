<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\DvaSection;

class DvaApiController extends Controller{
    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {
        return response()->json([
            'dva' => [
                'service_title' => DvaSection::first()?->service_title,
                'service_about' => DvaSection::first()?->service_about,
                'about_content' => DvaSection::first()?->about_content,
                'eligibility' => DvaSection::first()?->eligibility,
                'image_1' => $this->image(DvaSection::first()?->image_1),
                'image_2' => $this->image(DvaSection::first()?->image_2),
                'points' => DvaSection::first()?->points,

            ],

        ]);
    }
}