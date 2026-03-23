<?php

namespace App\Http\Controllers;

use App\Models\Testimonials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonials::latest()->get();
        return view('admin.pages.testimonials', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'quote' => 'required|string|max:255',
            'photo'=>'required|image|mimes:png,jpg,jpeg,webp|max:255'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('testimonials', 'public');
        }
        // for photo
        if($request->hasFile('photo')){
            $data['photo']= $request->file('photo')->store('testimonials','public');
        }

        Testimonials::create($data);

        return back()->with('success', 'Testimonial added successfully!');
    }

    public function update(Request $request, Testimonials $testimonial)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'quote'=>'required|string|max:555',
            'photo'=>'required|image|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $data['image'] = $request->file('photo')->store('testimonials', 'public');
        }

        if($request->hasFile('profile')){
            if($testimonial->profile){
                Storage::disk('photo')->delete($testimonial->profile);
            }
            $data['photo'] = $request->file('profile')->store('testimonials','public');
        }

        $testimonial->update($data);

        return back()->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonials $testimonial)
    {
        if ($testimonial->image) {
            Storage::disk('public')->delete($testimonial->image);
        }
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted successfully!');
    }
}