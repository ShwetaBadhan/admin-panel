<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // TO FTECH IN FRONTEND
    public function frontendGallery()
    {
        $gallery = Gallery::where('status', 'active')->get();
        return view('website.pages.welcome', compact('gallery'));
    }

    public function index()
    {
        $gallery = Gallery::latest()->get();
        return view('admin.pages.gallery', compact('gallery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:5048',
            'caption' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        Gallery::create([
            'image' => $path,
            'caption' => $request->caption,
            'status' => $request->status
        ]);

        return back()->with('success', 'Image added to gallery!');
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5048',
            'caption' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'caption' => $request->caption,
            'status' => $request->status
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return back()->with('success', 'Image updated successfully!');
    }

    public function destroy(Gallery $gallery)
    {
        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Image deleted permanently!');
    }
}