<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityService;
use Illuminate\Support\Facades\Storage;
class CommunityServiceController extends Controller
{
    //
    public function index()
    {
        $CommunityServiceCard = CommunityService::latest()->get();
        return view('admin.pages.community-service', compact('CommunityServiceCard'));
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
            $data['image'] = $request->file('image')->store('CommunityService', 'public');
        }


        CommunityService::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, CommunityService $CommunityServiceCard)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['title', 'description', 'status']);

        if ($request->hasFile('image')) {
            if ($CommunityServiceCard->image) {
                Storage::disk('public')->delete($CommunityServiceCard->image);
            }
            $data['image'] = $request->file('image')->store('CommunityService', 'public');
        }

        $CommunityServiceCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }


    public function destroy(CommunityService $CommunityServiceCard)
    {
        if ($CommunityServiceCard->image) {
            Storage::disk('public')->delete($CommunityServiceCard->image);
        }
        $CommunityServiceCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
