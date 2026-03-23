<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SupportCoordinationService;

class SupportCoordinationServiceController extends Controller
{
    //
    public function index()
    {
        $SupportCoordinationServiceCard = SupportCoordinationService::latest()->get();
        return view('admin.pages.support-coordination-service', compact('SupportCoordinationServiceCard'));
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
            $data['image'] = $request->file('image')->store('SupportCoordinationService', 'public');
        }


        SupportCoordinationService::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, SupportCoordinationService $SupportCoordinationServiceCard)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($SupportCoordinationServiceCard->image) {
                Storage::disk('public')->delete($SupportCoordinationServiceCard->image);
            }
            $data['image'] = $request->file('photo')->store('SupportCoordinationService', 'public');
        }



        $SupportCoordinationServiceCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(SupportCoordinationService $SupportCoordinationServiceCard)
    {
        if ($SupportCoordinationServiceCard->image) {
            Storage::disk('public')->delete($SupportCoordinationServiceCard->image);
        }
        $SupportCoordinationServiceCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
