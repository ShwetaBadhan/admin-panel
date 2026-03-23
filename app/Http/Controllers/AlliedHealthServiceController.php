<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AlliedHealthService;
use Illuminate\Support\Facades\Storage;
class AlliedHealthServiceController extends Controller
{
    //
       public function index()
    {
        $AlliedHealthServiceCard = AlliedHealthService::latest()->get();
        return view('admin.pages.allied-health-service', compact('AlliedHealthServiceCard'));
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
            $data['image'] = $request->file('image')->store('AlliedHealthService', 'public');
        }


        AlliedHealthService::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, AlliedHealthService $AlliedHealthServiceCard)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($AlliedHealthServiceCard->image) {
                Storage::disk('public')->delete($AlliedHealthServiceCard->image);
            }
            $data['image'] = $request->file('photo')->store('AlliedHealthService', 'public');
        }



        $AlliedHealthServiceCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(AlliedHealthService $AlliedHealthServiceCard)
    {
        if ($AlliedHealthServiceCard->image) {
            Storage::disk('public')->delete($AlliedHealthServiceCard->image);
        }
        $AlliedHealthServiceCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
