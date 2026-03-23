<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AccommodationGallery;
class AccommodationGalleryController extends Controller
{

    public function index()
    {
        $AccommodationGallerys = AccommodationGallery::latest()->get();
        return view('admin.pages.accommodation-gallery', compact('AccommodationGallerys'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:accommodation_galleries,title',
            'status' => 'required|in:active,inactive',
            'service_id' => 'nullable|array',
            'service_id.*' => 'integer|exists:independent_accommodations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);


        // Handle icon upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('accomo-gallery-images', 'public');
        }

        // Save selected test IDs as JSON array
        $validated['service_id'] = $request->input('service_id', []);


        AccommodationGallery::create($validated);

        return back()->with('success', ' Gallery created successfully!');
    }

    public function update(Request $request, AccommodationGallery $card)
    {
        // dd($request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:accommodation_galleries,title,' . $card->id,
            'status' => 'required|in:active,inactive',
            'service_id' => 'nullable|array',
            'service_id.*' => 'integer|exists:independent_accommodations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (
                $card->image &&
                Storage::disk('public')->exists($card->image)
            ) {
                Storage::disk('public')->delete($card->image);
            }

            $validated['image'] = $request->file('image')
                ->store('accomo-gallery-images', 'public');
        }

       
        $validated['service_id'] = $request->input('service_id', []);

        $card->update($validated);

        return back()->with('success', 'Gallery updated successfully!');
    }


    public function destroy(AccommodationGallery $card)
    {
        if ($card->image && Storage::disk('public')->exists($card->image)) {
            Storage::disk('public')->delete($card->image);
        }


        $card->delete();

        return back()->with('success', ' Gallery deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        AccommodationGallery::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
