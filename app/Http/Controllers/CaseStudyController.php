<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CaseStudy;

class CaseStudyController extends Controller
{
    //
    public function index()
    {
        $CaseStudy = CaseStudy::latest()->get();
        return view('admin.pages.case-study', compact('CaseStudy'));
    }

    public function store(request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('CaseStudy', 'public');
        }
        // for pdf 
       


        CaseStudy::create($data);

        return back()->with('success', 'Case Study  added successfully!');
    }
    public function update(Request $request, CaseStudy $CaseStudy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        //  ONLY FILLABLE DATA
        $data = $request->only([
            'title',
            'description',
            'status'
        ]);

        // IMAGE
        if ($request->hasFile('image')) {
            if ($CaseStudy->image) {
                Storage::disk('public')->delete($CaseStudy->image);
            }
            $data['image'] = $request->file('image')->store('StaffPolicy', 'public');
        }

       

        $CaseStudy->update($data);

        return back()->with('success', 'Section updated successfully!');
    }

    public function destroy(CaseStudy $card)
    {
        if ($card->image) {
            Storage::disk('public')->delete($card->image);
        }
        if ($card->pdf) {
            Storage::disk('public')->delete($card->pdf);
        }
        $card->delete();
        return back()->with('success', 'Section deleted successfully!');
    }
}
