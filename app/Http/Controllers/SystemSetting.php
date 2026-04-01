<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSetting extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail();
        return view('admin.pages.system-setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrFail();

        $request->validate([
            'black_logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'white_logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'backend_logo'    => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon'         => 'nullable|image|mimes:png,ico,jpg,icon|max:512',
            'helpdesk_number' => 'nullable|string|max:20',
            'cover_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // ✅ Already validated
        ]);

        // Handle logo uploads (black_logo, white_logo, backend_logo, favicon)
        $logoFields = ['black_logo', 'white_logo', 'backend_logo', 'favicon'];

        foreach ($logoFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                if ($setting->$field) {
                    Storage::delete('public/' . $setting->$field);
                }
                // Save new one
                $path = $request->file($field)->store('logos', 'public');
                $setting->$field = $path;
            }
        }

        // ✅ Handle cover_image upload (store in separate folder)
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($setting->cover_image) {
                Storage::delete('public/' . $setting->cover_image);
            }
            // Save new cover image
            $path = $request->file('cover_image')->store('covers', 'public');
            $setting->cover_image = $path;
        }

        // Update helpdesk number
        if ($request->filled('helpdesk_number')) {
            $setting->helpdesk_number = $request->helpdesk_number;
        }

        $setting->save();

        // ✅ Clear cache if you're caching settings
        cache()->forget('site_settings');

        return back()->with('success', 'System settings updated successfully!');
    }
}