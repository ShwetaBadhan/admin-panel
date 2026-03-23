<?php

namespace App\Http\Controllers;

use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderImageController extends Controller
{
    // Display listing (Read)
    public function index()
    {
        $sliderImages = SliderImage::all();
        return view('admin.pages.sliderimage', compact('sliderImages'));
    }

    

    // Store new record (Create)
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $path = $request->file('image')->store('slider-images', 'public');

        SliderImage::create([
            'image' => $path,
            'status' => $request->status,
        ]);

        return redirect()->route('sliderimage.index')->with('success', 'Slider image added successfully.');
    }


    // Update record (Update)
    public function update(Request $request, SliderImage $sliderImage)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($sliderImage->image);
            $path = $request->file('image')->store('slider-images', 'public');
            $sliderImage->image = $path;
        }

        $sliderImage->status = $request->status;
        $sliderImage->save();

        return redirect()->route('sliderimage.index')->with('success', 'Slider image updated successfully.');
    }

    // Delete record (Delete)
    public function destroy(SliderImage $sliderImage)
    {
        Storage::disk('public')->delete($sliderImage->image);
        $sliderImage->delete();

        return redirect()->route('sliderimage.index')->with('success', 'Slider image deleted successfully.');
    }
}