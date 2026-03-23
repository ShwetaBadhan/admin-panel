<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityPlanning;
use Illuminate\Support\Facades\Storage;
class CommunityPlanningController extends Controller
{
    //
    public function index()
    {
        $CommunityPlannings = CommunityPlanning::first();

        if (!$CommunityPlannings) {
            $CommunityPlannings = CommunityPlanning::create([
                'main_title' => 'Aged Care About',
                'sub_title' => 'sub title',
                'note' => 'this is to inform you...',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.community-planning-section', compact('CommunityPlannings'));
    }

    public function update(Request $request)
    {
        $CommunityPlannings = CommunityPlanning::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'sub_title' => 'required|string|max:355',
            'note' => 'required|string|max:355',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'description_1' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'sub_title' => $request->sub_title,
            'note' => $request->sub_title,
            'points' => array_values(array_filter($request->points ?? [])),
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($CommunityPlannings->image) {
                Storage::disk('public')->delete($CommunityPlannings->image);
            }
            $data['image'] = $request->file('image')->store('communityplanning', 'public');
        }

        $CommunityPlannings->update($data);

        return back()->with('success', 'Planning Section Updated Successfully');
    }
}
