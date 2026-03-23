<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SeoPage;
class SeoPageController extends Controller
{
    //
    // to fetch data 

    public function index()
    {
        //  dd("INDEX IS RUNNING");
        $seoPages = SeoPage::all();
        return view('admin.pages.admin-seo-page', compact('seoPages'));
    }
    public function view($id)
    {
        return response()->json(SeoPage::findOrFail($id));
    }


    // to add or store data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'page' => 'required|string|max:255',
            'is_active' => 'required|string|max:20',

        ]);



        // Store in DB
        SeoPage::create([
            'page' => $request->page,
            'is_active' => $request->is_active,

        ]);

        return redirect()->back()->with('success', 'SEO Page Added Successfully');
    }
    public function update(Request $request)
    {
        $seoPages = SeoPage::find($request->id);

        $seoPages->page = $request->page;
        $seoPages->is_active = $request->is_active;




        $seoPages->save();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $seoPages = SeoPage::findOrFail($id);



        $seoPages->delete();

        return response()->json(['success' => true]);
    }
}
