<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CommunityNursingAct;
class CommunityNursingActController extends Controller
{
    //
    public function index()
    {
        $CommunityNursingActs = CommunityNursingAct::first();

        if (!$CommunityNursingActs) {
            $CommunityNursingActs = CommunityNursingAct::create([
                'main_title' => 'Continuity Care is th best  for elder care',
                'sub_title' => 'Enter sub title',
                'side_title' => 'Enter side title',
                'description_1' => 'Best services we serve',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.community-nursing-act', compact('CommunityNursingActs'));
    }

    public function update(Request $request)
    {
        $CommunityNursingActs = CommunityNursingAct::firstOrFail();

        $request->validate([
            'main_title' => 'required|string',
            'sub_title' => 'required|string',
            'side_title' => 'required|string',
            'description_1' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'sub_title' => $request->sub_title,
            'side_title' => $request->side_title,
            'description_1' => $request->description_1,
            'points' => array_values(array_filter($request->points ?? [])),
            'is_active' => $request->is_active ?? 1,
        ];

        

        $CommunityNursingActs->update($data);

        return back()->with('success', 'Act Section  Updated Successfully');
    }
}
