<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhyChooseSection;
use Illuminate\Support\Facades\Storage;

class WhyChooseSectionController extends Controller
{
    //
    public function index()
    {
        $whyChooseCards = WhyChooseSection::latest()->get();
        return view('admin.pages.why-choose-section', compact('whyChooseCards'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('WhyChooseSection', 'public');
        }
        

        WhyChooseSection::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, WhyChooseSection $whyChooseCards)
    {
        $request->validate([
           'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($whyChooseCards->image) {
                Storage::disk('public')->delete($whyChooseCards->image);
            }
            $data['image'] = $request->file('photo')->store('WhyChooseSection', 'public');
        }

     

        $whyChooseCards->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(WhyChooseSection $whyChooseCards)
    {
        if ($whyChooseCards->image) {
            Storage::disk('public')->delete($whyChooseCards->image);
        }
        $whyChooseCards->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
