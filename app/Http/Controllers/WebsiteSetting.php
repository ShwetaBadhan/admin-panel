<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class WebsiteSetting extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail();
        return view('admin.pages.website-setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrFail();

        $validated = $request->validate([
            'company_name' => 'sometimes|required|string|max:255',
            'email'        => 'sometimes|required|email',
            'location'     => 'sometimes|required|string|max:255',
            'phone1'       => 'sometimes|required|string|max:20',
            'phone2'       => 'nullable|string|max:20',
            'about'        => 'nullable|string',
            'facebook'     => 'nullable|url',
            'instagram'    => 'nullable|url',
            'linkedin'     => 'nullable|url',
            'twitter'      => 'nullable|url',
        ]);

        $message = "Settings updated successfully!";

        // Detect which form was submitted
        if ($request->hasAny(['company_name', 'email', 'location', 'phone1', 'phone2', 'about'])) {
            $message = "General settings saved successfully!";
        }

        if ($request->hasAny(['facebook', 'instagram', 'linkedin', 'twitter'])) {
            $current = $setting->social_links ?? [];
            $validated['social_links'] = [
                'facebook'  => $request->facebook ?? $current['facebook'] ?? '',
                'instagram' => $request->instagram ?? $current['instagram'] ?? '',
                'linkedin'  => $request->linkedin ?? $current['linkedin'] ?? '',
                'twitter'   => $request->twitter ?? $current['twitter'] ?? '',
            ];
            $message = "Social media links updated successfully!";
        }

        $setting->update($validated);

        return back()->with('success', $message);
    }
}