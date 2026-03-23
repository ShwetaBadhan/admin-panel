<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\IndependentAccommodation;
class IndependentAccommodationController extends Controller
{
    public function index()
    {
        $IndependentAccommodations = IndependentAccommodation::latest()->get();
        return view('admin.pages.independent-accommodation', compact('IndependentAccommodations'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:independent_accommodations,title',
            'sub_title' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('independent-accommodations', 'public');
        }


        IndependentAccommodation::create($validated);

        return back()->with('success', ' Accommodation created successfully!');
    }

    public function update(Request $request, IndependentAccommodation $card)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:independent_accommodations,title,' . $card->id,
            'sub_title' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($card->image && Storage::disk('public')->exists($card->image)) {
                Storage::disk('public')->delete($card->image);
            }

            $validated['image'] = $request->file('image')
                ->store('independent-accommodations', 'public');
        }


        $card->update($validated);

        return back()->with('success', ' Accommodation updated successfully!');
    }

    public function destroy(IndependentAccommodation $card)
    {
        if ($card->image && Storage::disk('public')->exists($card->image)) {
            Storage::disk('public')->delete($card->image);
        }

        $card->delete();

        return back()->with('success', ' Accommodation deleted successfully!');
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) === 0) {
            return response()->json([
                'error' => true,
                'message' => 'No IDs received'
            ]);
        }

        IndependentAccommodation::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
}
