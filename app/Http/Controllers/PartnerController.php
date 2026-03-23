<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();
        return view('admin.pages.partners', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'  => 'required|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        $path = $request->file('image')->store('partners', 'public');

        Partner::create([
            'image'  => $path,
            'status' => $request->status
        ]);

        return back()->with('success', 'Partner added successfully!');
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'image'  => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'status' => 'required|in:active,inactive'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($partner->image);
            $partner->image = $request->file('image')->store('partners', 'public');
        }

        $partner->status = $request->status;
        $partner->save();

        return back()->with('success', 'Partner updated successfully!');
    }

    public function destroy(Partner $partner)
    {
        Storage::disk('public')->delete($partner->image);
        $partner->delete();
        return back()->with('success', 'Partner deleted!');
    }
}