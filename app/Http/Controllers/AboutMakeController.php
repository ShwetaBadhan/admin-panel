<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutMake;
class AboutMakeController extends Controller
{
    //
    public function index()
    {
        $AboutMakes = AboutMake::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );


        return view('admin.pages.admin-makes-different', compact('AboutMakes'));
    }
    public function update(Request $request)
    {
        $AboutMakes = AboutMake::firstOrFail();

        $request->validate([
            'sub_title' => 'required|string|max:100',
            'main_title' => 'required|string|max:200',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->only([
            'sub_title',
            'main_title',
            'description',
            'is_active'
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($AboutMakes->image && Storage::disk('public')->exists($AboutMakes->image)) {
                Storage::disk('public')->delete($AboutMakes->image);
            }

            $imagePath = $request->file('image')->store('admin-makes', 'public');
            $data['image'] = $imagePath;

            // Log for debugging
            // Log::info('Main image uploaded: ' . $imagePath);
        }



        $AboutMakes->update($data);

        return back()->with('success', 'About What make us Different section updated successfully!');
    }
}
