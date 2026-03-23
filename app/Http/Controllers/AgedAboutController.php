<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AgedAbout;
class AgedAboutController extends Controller
{
    //
    public function index()
    {
        $AgedAbouts = AgedAbout::first();

        if (!$AgedAbouts) {
            $AgedAbouts = AgedAbout::create([
                'main_title' => 'Aged Care About',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.aged-about-section', compact('AgedAbouts'));
    }

    public function update(Request $request)
    {
        $AgedAbouts = AgedAbout::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'description_1' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'points' => array_values(array_filter($request->points ?? [])),
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($AgedAbouts->image) {
                Storage::disk('public')->delete($AgedAbouts->image);
            }
            $data['image'] = $request->file('image')->store('admin-act', 'public');
        }

        $AgedAbouts->update($data);

        return back()->with('success', 'About Section Updated Successfully');
    }

}
