<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AboutSectionTwo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutSectionTwoController extends Controller
{
    public function index()
    {
        $section = AboutSectionTwo::getSection();
        return view('admin.pages.about-section-two', compact('section'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sub_title'     => 'required|string',
            'main_title'    => 'required|string',
            'description_1' => 'required|string',
            'founded_year'  => 'required|string',
            'image_top'     => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'image_bottom'  => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'founded_image' => 'nullable|image|mimes:png,svg,jpg|max:1024',
            'shape_1'       => 'nullable|image|mimes:png,svg|max:1024',
            'shape_2'       => 'nullable|image|mimes:png,svg|max:1024',
        ]);

        $section = AboutSectionTwo::getSection();

        $data = $request->only([
            'sub_title', 'main_title', 'description_1', 'description_2',
            'founded_year', 'is_active'
        ]);

        // Handle file uploads
        $fields = ['image_top', 'image_bottom', 'founded_image', 'shape_1', 'shape_2'];
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                if ($section->$field) {
                    Storage::disk('public')->delete($section->$field);
                }
                $data[$field] = $request->file($field)->store('about-section', 'public');
            }
        }

        $section->update($data);

        return back()->with('success', 'About Section updated successfully!');
    }
}