<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AboutSectionController extends Controller
{
    public function index()
    {
        $aboutSection = AboutSection::firstOrCreate(
            [],
            [
                'sub_title' => 'About Us',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description_1' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'description_2' => 'We believe in combining innovation with compassion, offering a wide range of pathology, radiology, and wellness services under one roof.',
                'feature_1_title' => 'Advanced Technology, Accurate Results',
                'feature_1_description' => 'Equipped with state-of-the-art analyzers and automated systems, Continuity Care ensures precise and reliable diagnostic outcomes every time.',
                'feature_2_title' => 'Expert Team, Patient-Focused Care',
                'feature_2_description' => 'Our team of qualified pathologists and technicians work with dedication and empathy to deliver results that support better health decisions and patient well-being.',
                'is_active' => true
            ]
        );

        return view('admin.pages.about-section', compact('aboutSection'));
    }

    public function update(Request $request)
    {
        $aboutSection = AboutSection::firstOrFail();

        $request->validate([
            'sub_title' => 'required|string|max:100',
            'main_title' => 'required|string|max:200',
            'description_1' => 'required|string',
            'description_2' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'icon_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'feature_1_title' => 'required|string|max:100',
            'feature_1_description' => 'required|string',
            'feature_2_title' => 'required|string|max:100',
            'feature_2_description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->only([
            'sub_title',
            'main_title',
            'description_1',
            'description_2',
            'feature_1_title',
            'feature_1_description',
            'feature_2_title',
            'feature_2_description',
            'is_active'
        ]);

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($aboutSection->image && Storage::disk('public')->exists($aboutSection->image)) {
                Storage::disk('public')->delete($aboutSection->image);
            }

            $imagePath = $request->file('image')->store('about-sections', 'public');
            $data['image'] = $imagePath;

            // Log for debugging
            Log::info('Main image uploaded: ' . $imagePath);
        }

        // Handle icon 1 upload
        if ($request->hasFile('icon_1')) {
            if ($aboutSection->icon_1 && Storage::disk('public')->exists($aboutSection->icon_1)) {
                Storage::disk('public')->delete($aboutSection->icon_1);
            }
            $data['icon_1'] = $request->file('icon_1')->store('about-icons', 'public');
        }

        // Handle icon 2 upload
        if ($request->hasFile('icon_2')) {
            if ($aboutSection->icon_2 && Storage::disk('public')->exists($aboutSection->icon_2)) {
                Storage::disk('public')->delete($aboutSection->icon_2);
            }
            $data['icon_2'] = $request->file('icon_2')->store('about-icons', 'public');
        }

        $aboutSection->update($data);

        return back()->with('success', 'About section updated successfully!');
    }
}