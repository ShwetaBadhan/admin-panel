<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeCareCommunity;
use Illuminate\Support\Facades\Storage;
class HomeCareCommunityController extends Controller
{
    //
    public function index()
    {
        $HomeCareCommunityCard = HomeCareCommunity::latest()->get();
        return view('admin.pages.home-care-community', compact('HomeCareCommunityCard'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        HomeCareCommunity::create($validated);

        return back()->with('success', 'Section card added successfully!');
    }
    public function update(Request $request, HomeCareCommunity $card)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $card->update($validated);

        return back()->with('success', 'Section Card updated successfully!');
    }
    public function destroy(HomeCareCommunity $HomeCareCommunityCard)
    {
        if ($HomeCareCommunityCard->image) {
            Storage::disk('public')->delete($HomeCareCommunityCard->image);
        }
        $HomeCareCommunityCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
