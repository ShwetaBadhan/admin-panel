<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnerAbout;

class PartnerAboutController extends Controller
{
    //
    public function index()
    {
        $PartnersAbout = PartnerAbout::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );


        return view('admin.pages.admin-partner-about', compact('PartnersAbout'));
    }
    public function update(Request $request)
    {
        $PartnersAbout = PartnerAbout::firstOrFail();

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
            if ($PartnersAbout->image && Storage::disk('public')->exists($PartnersAbout->image)) {
                Storage::disk('public')->delete($PartnersAbout->image);
            }

            $imagePath = $request->file('image')->store('partner-about', 'public');
            $data['image'] = $imagePath;

            // Log for debugging
            // Log::info('Main image uploaded: ' . $imagePath);
        }



        $PartnersAbout->update($data);

        return back()->with('success', 'Partner About section updated successfully!');
    }
}
