<?php

namespace App\Http\Controllers;

use App\Models\Setting;  // ← Use Setting model, not SystemSetting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSetting extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail(); // ← This now works
        return view('admin.pages.system-setting', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrFail();

        $request->validate([
            'black_logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'white_logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'backend_logo'    => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'favicon'         => 'nullable|image|mimes:png,ico,jpg|max:512',
            'helpdesk_number' => 'nullable|string|max:20',
        ]);

        $fields = ['black_logo', 'white_logo', 'backend_logo', 'favicon'];

        foreach ($fields as $field) {
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

        // Update helpdesk number
        if ($request->filled('helpdesk_number')) {
            $setting->helpdesk_number = $request->helpdesk_number;
        }

        $setting->save(); // ← Use save() instead of update() for file fields

        return back()->with('success', 'System settings updated successfully!');
    }
}