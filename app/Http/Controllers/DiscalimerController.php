<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disclaimer;

class DiscalimerController extends Controller
{
   public function index()
    {
        $DisclaimerContent = Disclaimer::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );

        return view('admin.pages.admin-disclaimer', compact('DisclaimerContent'));
    }

    public function update(Request $request)
    {
        $DisclaimerContent = Disclaimer::firstOrFail();

        $request->validate([
            'sub_title'   => 'required|string|max:100',
            'main_title'  => 'required|string|max:200',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean', // Fixed: handle checkbox properly
        ]);

        $data = $request->only([
            'sub_title',
            'main_title', 
            'description',
        ]);

        // Handle checkbox: if not present, set to false
        $data['is_active'] = $request->boolean('is_active');

        $DisclaimerContent->update($data);

        return back()->with('success', 'Disclaimer content updated successfully!');
    }
}
