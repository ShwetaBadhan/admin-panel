<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityActivity;
use Illuminate\Support\Facades\Storage;
class CommunityActivityController extends Controller
{
    //
     public function index()
    {
        $CommunityActivitys = CommunityActivity::first();

        if (!$CommunityActivitys) {
            $CommunityActivitys = CommunityActivity::create([
                'main_title' => 'Aged Care Benefit',
                'side_title' => 'Side Title',
                'sub_title' => 'Sub Title',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.community-activity', compact('CommunityActivitys'));
    }

    public function update(Request $request)
    {
        $CommunityActivitys = CommunityActivity::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'side_title' => 'required|string|max:200',
            'sub_title' => 'required|string|max:200',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'description_1' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'side_title' => $request->side_title,
            'sub_title' => $request->sub_title,
            'points' => array_values(array_filter($request->points ?? [])),
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($CommunityActivitys->image) {
                Storage::disk('public')->delete($CommunityActivitys->image);
            }
            $data['image'] = $request->file('image')->store('community-activity', 'public');
        }

        $CommunityActivitys->update($data);

        return back()->with('success', 'Benefit Section Updated Successfully');
    }
}
