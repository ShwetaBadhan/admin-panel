<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeoSetting;
class SeoSettingController extends Controller
{

    function getSeo()
    {
        $url = request()->path();  // "/" or "about-us" or "services/web-dev"

        // Remove trailing slash and lowercase
        $cleanUrl = strtolower(trim($url, '/'));   // "/" -> "", "about-us" -> "about-us"

        // Get slug (last part)
        $slug = Str::afterLast($cleanUrl, '/');    // same as cleanUrl unless nested route

        // ----------------------------------------------------
        // HOME PAGE ("" means root "/")
        // ----------------------------------------------------
        if ($cleanUrl === '' || $url === '/') {
            $seo = SeoSetting::where('page', 'home')->first();

            return $seo ?: (object) [
                'title' => 'Welcome to Continuity Care',
                'keywords' => '',
                'description' => ''
            ];
        }

        // ----------------------------------------------------
        // 1. Match slug (last part)
        // ----------------------------------------------------
        $seo = SeoSetting::where('page', $slug)->first();

        // ----------------------------------------------------
        // 2. Match full path (example: services/web-dev)
        // ----------------------------------------------------
        if (!$seo) {
            $seo = SeoSetting::where('page', $cleanUrl)->first();
        }

        // ----------------------------------------------------
        // DEFAULT SEO IF NOTHING FOUND
        // ----------------------------------------------------
        return $seo ?: (object) [
            'title' => 'Welcome to Continuity Care',
            'keywords' => '',
            'description' => ''
        ];
    }




    //

    public function index()
    {
        //  dd("INDEX IS RUNNING");
        $seoSettings = SeoSetting::all();
        return view('admin.pages.seo-setting', compact('seoSettings'));
    }

    // for view
    public function view($id)
    {
        $doctor = SeoSetting::findOrFail($id);
        return response()->json($doctor);
    }

    // to add or store data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'page' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'keywords' => 'required|string|max:255',

        ]);



        // Store in DB
        SeoSetting::create([
            'title' => $request->title,
            'page' => $request->page,
            'description' => $request->description,
            'keywords' => $request->keywords,

        ]);

        return redirect()->back()->with('success', 'SEO Setting Added Successfully');
    }
    public function update(Request $request)
    {
        $seoSettings = SeoSetting::find($request->id);

        $seoSettings->title = $request->title;
        $seoSettings->page = $request->page;
        $seoSettings->description = $request->description;
        $seoSettings->keywords = $request->keywords;


        $seoSettings->save();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $seoSettings = SeoSetting::findOrFail($id);



        $seoSettings->delete();

        return response()->json(['success' => true]);
    }

}
