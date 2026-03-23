<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\NiisqSection;

class NiisqApiController extends Controller
{

    private function image($path)
    {
        return $path ? asset('storage/' . $path) : null;
    }
    public function index()
    {
        return response()->json([
            'niisq' => [
                'service_title' => NiisqSection::first()?->service_title,
                'service_about' => NiisqSection::first()?->service_about,
                'about_content' => NiisqSection::first()?->about_content,
                'eligibility' => NiisqSection::first()?->eligibility,
                'image_1' => $this->image(NiisqSection::first()?->image_1),
                'image_2' => $this->image(NiisqSection::first()?->image_2),
                'points' => NiisqSection::first()?->points,

            ],

        ]);
    }
}