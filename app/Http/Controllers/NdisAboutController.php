<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NdisAbout;
use Illuminate\Support\Facades\Storage;

class NdisAboutController extends Controller
{
    //
    public function index()
    {
        $NdisAbouts = NdisAbout::firstOrCreate(
            [],
            [

                'main_title' => 'NDIS ABout',
                'description_1' => 'Continuity Care is a dedicated provider of disability and independent living services, offering comprehensive care coordination and navigation. Everything we do is focused on bridging the gap between clinical requirements and daily life, ensuring people with disabilities can live with dignity and independence.With a deep commitment to integrated support at our core, Continuity Care is dedicated to empowering individuals through expert guidance. We believe everyone deserves a seamless care experience that celebrates their choices and supports their long-term goals.',
                'is_active' => true
            ]
        );


        return view('admin.pages.ndis-about-section', compact('NdisAbouts'));
    }
    public function update(Request $request)
    {
        $NdisAbouts = NdisAbout::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'description_1' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean'
        ]);

        // Data
        $data = [
            'main_title' => $request->main_title,
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        // Main image
        if ($request->hasFile('image')) {
            if ($NdisAbouts->image && Storage::disk('public')->exists($NdisAbouts->image)) {
                Storage::disk('public')->delete($NdisAbouts->image);
            }
            $data['image'] = $request->file('image')->store('admin-act', 'public');
        }
        $NdisAbouts->update($data);
        return back()->with('success', ' About Section Updated Successfully');
    }
}
