<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutCare;
use Illuminate\Support\Facades\Storage;

class AboutCareController extends Controller
{
    //
    public function index()
    {
        $AboutCares = AboutCare::firstOrCreate(
            [],
            [
                'sub_title' => 'About Us',
                'main_title' => 'Empowering Independence Through Compassionate Care and Seamless Navigation',
                'description_1' => 'Continuity Care is a dedicated provider of disability and independent living services, offering comprehensive care coordination and navigation. Everything we do is focused on bridging the gap between clinical requirements and daily life, ensuring people with disabilities can live with dignity and independence.With a deep commitment to integrated support at our core, Continuity Care is dedicated to empowering individuals through expert guidance. We believe everyone deserves a seamless care experience that celebrates their choices and supports their long-term goals.',
                'description_2' => 'Key Points',
                'feature_1_title' => 'Advanced Technology, Accurate Results',
                'is_active' => true
            ]
        );


        return view('admin.pages.admin-about-care', compact('AboutCares'));
    }
    public function update(Request $request)
    {
        $AboutCares = AboutCare::firstOrFail();

        $request->validate([
            'sub_title' => 'required|string|max:100',
            'main_title' => 'required|string|max:200',
            'description_1' => 'required|string',
            'description_2' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'image_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'feature_1_title' => 'required|string|max:100',
            'is_active' => 'boolean'
        ]);

        // Data
        $data = [
            'sub_title' => $request->sub_title,
            'main_title' => $request->main_title,
            'description_1' => $request->description_1,
            'description_2' => $request->description_2,
            'feature_1_title' => $request->feature_1_title,
            'is_active' => $request->is_active ?? 1,
        ];

        // Main image
        if ($request->hasFile('image')) {
            if ($AboutCares->image && Storage::disk('public')->exists($AboutCares->image)) {
                Storage::disk('public')->delete($AboutCares->image);
            }
            $data['image'] = $request->file('image')->store('admin-cares', 'public');
        }

        // Secondary image
        if ($request->hasFile('image_2')) {
            if ($AboutCares->image_2 && Storage::disk('public')->exists($AboutCares->image_2)) {
                Storage::disk('public')->delete($AboutCares->image_2);
            }
            $data['image_2'] = $request->file('image_2')->store('admin-cares', 'public');
        }

        // Icon 1
        if ($request->hasFile('icon_1')) {
            if ($AboutCares->icon_1 && Storage::disk('public')->exists($AboutCares->icon_1)) {
                Storage::disk('public')->delete($AboutCares->icon_1);
            }
            $data['icon_1'] = $request->file('icon_1')->store('admin-cares/icons', 'public');
        }

        $AboutCares->update($data);


        return back()->with('success', 'About Section Updated Successfully');
    }
}
