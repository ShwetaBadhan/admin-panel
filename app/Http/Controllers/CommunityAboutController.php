<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityAbout;
use Illuminate\Support\Facades\Storage;
class CommunityAboutController extends Controller
{
    //
    public function index()
    {
        $CommunityAbouts = CommunityAbout::first();

        if (!$CommunityAbouts) {
            $CommunityAbouts = CommunityAbout::create([
                'main_title' => 'Continuity Care is th best  for elder care',
                'description_1' => 'Best services we serve',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.community-about', compact('CommunityAbouts'));
    }

    public function update(Request $request)
    {
        $CommunityAbouts = CommunityAbout::firstOrFail();

        $request->validate([
            'main_title' => 'required|string',
            'description_1' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'description_1' => $request->description_1,
            'points' => array_values(array_filter($request->points ?? [])),
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($CommunityAbouts->image) {
                Storage::disk('public')->delete($CommunityAbouts->image);
            }
            $data['image'] = $request->file('image')->store('community', 'public');
        }


        $CommunityAbouts->update($data);

        return back()->with('success', 'About  Updated Successfully');
    }
}
