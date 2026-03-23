<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunitySupport;
use Illuminate\Support\Facades\Storage;
class CommunitySupportController extends Controller
{
    //

    public function index()
    {
        $CommunitySupportCard = CommunitySupport::latest()->get();
        return view('admin.pages.community-support', compact('CommunitySupportCard'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'main_title' => 'required|string|max:255',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('CommunitySupport', 'public');
        }

        CommunitySupport::create($validated);

        return back()->with('success', 'Support card added successfully!');
    }


     public function update(Request $request, CommunitySupport $card)
    {
        $data = $request->validate([
            'main_title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

      
        $data['points'] = array_values(
            array_filter($request->input('points', []))
        );

        if ($request->hasFile('image')) {
            if ($card->image) {
                Storage::disk('public')->delete($card->image);
            }
            $data['image'] = $request->file('image')->store('CommunitySupport', 'public');
        }

        $card->update($data);

        return back()->with('success', 'Support Card updated successfully!');
    }

    public function destroy(CommunitySupport $card)
    {
        if ($card->image) {
            Storage::disk('public')->delete($card->image);
        }

        $card->delete();

        return back()->with('success', 'Support Card deleted successfully!');
    }
}

