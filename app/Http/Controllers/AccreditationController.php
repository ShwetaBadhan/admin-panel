<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccreditationSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccreditationController extends Controller
{
    public function index()
    {
        $section = AccreditationSection::first() ?? AccreditationSection::create([]); 
        return view('admin.pages.accreditations', compact('section'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title1' => 'required|string|max:255',
            'icon1' => 'nullable|image|mimes:jpg,png,svg|max:2048',
            'title2' => 'required|string|max:255',
            'icon2' => 'nullable|image|mimes:jpg,png,svg|max:2048',
            'title3' => 'required|string|max:255',
            'icon3' => 'nullable|image|mimes:jpg,png,svg|max:2048',
            'title4' => 'required|string|max:255',
            'icon4' => 'nullable|image|mimes:jpg,png,svg|max:2048',
        ]);

        $section = AccreditationSection::first();

        // Update titles
        $section->update($request->only(['title1', 'title2', 'title3', 'title4']));

        // Handle icon uploads
        foreach (['icon1', 'icon2', 'icon3', 'icon4'] as $iconField) {
            if ($request->hasFile($iconField)) {
                if ($section->{$iconField}) {
                    Storage::delete($section->{$iconField});
                }
                $section->{$iconField} = $request->file($iconField)->store('accreditations', 'public');
            }
        }

        $section->save();

        return back()->with('success', 'Accreditations updated successfully!');
    }
}