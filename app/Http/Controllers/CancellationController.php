<?php

namespace App\Http\Controllers;
use App\Models\CancellationPolicy;

use Illuminate\Http\Request;

class CancellationController extends Controller
{
    public function index()
    {
        $CancellationContent = CancellationPolicy::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );

        return view('admin.pages.admin-cancellation-policy', compact('CancellationContent'));
    }

     public function update(Request $request)
    {
        $CancellationContent = CancellationPolicy::firstOrFail();

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

        $CancellationContent->update($data);

        return back()->with('success', 'cancellation content updated successfully!');
    }
}
