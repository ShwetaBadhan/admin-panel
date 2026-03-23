<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityApproachSection;
use illuminate\Support\Facades\Storage;
class CommunityApproachSectionController extends Controller
{
    //
    public function index()
    {
        $CommunityApproachSections = CommunityApproachSection::first();

        if (!$CommunityApproachSections) {
            $CommunityApproachSections = CommunityApproachSection::create([
                'main_title' => 'Aged Care Benefit',
                'side_title' => 'Side Title',
                'sub_title' => 'Sub Title',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'points_2' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.community-approach-section', compact('CommunityApproachSections'));
    }

    public function update(Request $request)
    {
        $CommunityApproachSections = CommunityApproachSection::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'side_title' => 'required|string|max:200',
            'sub_title' => 'required|string|max:200',
            'description_1' => 'required|string',

            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',

            'points_2' => 'nullable|array',
            'points_2.*' => 'nullable|string|max:255',

            'is_active' => 'boolean',
        ]);

        $data = [
            'main_title' => $request->main_title,
            'side_title' => $request->side_title,
            'sub_title' => $request->sub_title,
            'description_1' => $request->description_1,

            // first list
            'points' => array_values(array_filter($request->points ?? [])),

            // second list
            'points_2' => array_values(array_filter($request->points_2 ?? [])),

            'is_active' => $request->is_active ?? 1,
        ];

        $CommunityApproachSections->update($data);

        return back()->with('success', 'Approach Section Updated Successfully');
    }

}
