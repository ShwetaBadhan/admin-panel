<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CareCoordinationAbout;
class CareCoordinationAboutController extends Controller
{
    //
     public function index()
    {
        $CareCoordinationAbouts = CareCoordinationAbout::firstOrCreate(
            [],
            [

                'main_title' => 'Care Coordination About',
                'description_1' => 'Continuity Care is a dedicated provider of disability and independent living services, offering comprehensive care coordination and navigation. Everything we do is focused on bridging the gap between clinical requirements and daily life, ensuring people with disabilities can live with dignity and independence.With a deep commitment to integrated support at our core, Continuity Care is dedicated to empowering individuals through expert guidance. We believe everyone deserves a seamless care experience that celebrates their choices and supports their long-term goals.',
                'description_2' => 'Write overview',
                'is_active' => true
            ]
        );  


        return view('admin.pages.care-coordination-about', compact('CareCoordinationAbouts'));
    }
    public function update(Request $request)
    {
        $CareCoordinationAbouts = CareCoordinationAbout::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'description_1' => 'required|string',
            'description_2' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean'
        ]);

        // Data
        $data = [
            'main_title' => $request->main_title,
            'description_1' => $request->description_1,
            'description_2' => $request->description_2,
            'is_active' => $request->is_active ?? 1,
        ];

        // Main image
        if ($request->hasFile('image')) {
            if ($CareCoordinationAbouts->image && Storage::disk('public')->exists($CareCoordinationAbouts->image)) {
                Storage::disk('public')->delete($CareCoordinationAbouts->image);
            }
            $data['image'] = $request->file('image')->store('care', 'public');
        }
        $CareCoordinationAbouts->update($data);
        return back()->with('success', ' About Section Updated Successfully');
    }
}
