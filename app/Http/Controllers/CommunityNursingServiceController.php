<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityNursingService;
use Illuminate\Support\Facades\Storage;
class CommunityNursingServiceController extends Controller
{
    //

    public function index()
    {
        $CommunityNursingServiceCard = CommunityNursingService::latest()->get();
        return view('admin.pages.community-nursing-service', compact('CommunityNursingServiceCard'));
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
            $data['image'] = $request->file('image')->store('CommunityNursingService', 'public');
        }


        CommunityNursingService::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, CommunityNursingService $CommunityNursingServiceCard)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($CommunityNursingServiceCard->image) {
                Storage::disk('public')->delete($CommunityNursingServiceCard->image);
            }
            $data['image'] = $request->file('photo')->store('CommunityNursingService', 'public');
        }



        $CommunityNursingServiceCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(CommunityNursingService $CommunityNursingServiceCard)
    {
        if ($CommunityNursingServiceCard->image) {
            Storage::disk('public')->delete($CommunityNursingServiceCard->image);
        }
        $CommunityNursingServiceCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
