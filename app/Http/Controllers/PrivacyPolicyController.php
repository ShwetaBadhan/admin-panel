<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
    //
    public function index()
    {
        $PrivacyPolicyContent = PrivacyPolicy::firstOrCreate(
            [],
            [
                'sub_title' => 'Corporate Wellness',
                'main_title' => 'Precision. Care. Confidence — The Edge in Diagnostics.',
                'description' => 'At Continuity Care, we are committed to delivering accurate, reliable, and timely diagnostic results to help doctors and patients make informed health decisions.',
                'is_active' => true
            ]
        );


        return view('admin.pages.admin-privacy-policy', compact('PrivacyPolicyContent'));
    }
    public function update(Request $request)
    {
        $PrivacyPolicyContent = PrivacyPolicy::firstOrFail();

        $request->validate([
            'sub_title' => 'required|string|max:100',
            'main_title' => 'required|string|max:200',
            'description' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $data = $request->only([
            'sub_title',
            'main_title',
            'description',
            'is_active'
        ]);



        $PrivacyPolicyContent->update($data);

        return back()->with('success', 'Privacy Policy Content updated successfully!');
    }
}
