<?php

namespace App\Http\Controllers;
use App\Models\GrievanceRedressal;
use Illuminate\Http\Request;

class GrivanceRedressalController extends Controller
{
     public function index()
    {
        $GrievanceContent = GrievanceRedressal::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );

        return view('admin.pages.admin-grievance-redressal', compact('GrievanceContent'));
    }

    public function update(Request $request)
    {
        $GrievanceContent = GrievanceRedressal::firstOrFail();

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

        $GrievanceContent->update($data);

        return back()->with('success', 'Grievance Redressal content updated successfully!');
    }
}