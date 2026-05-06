<?php

namespace App\Http\Controllers\MLM;
use App\Http\Controllers\Controller;
use App\Models\CCSetting;
use Illuminate\Http\Request;

class CCSettingController extends Controller
{
    /**
     * Single page for everything - View, Update, Activate/Deactivate, Delete
     */
    public function index()
    {
        // Auto-create default if doesn't exist
        $setting = CCSetting::first();
        
        if (!$setting) {
            $setting = CCSetting::create([
                'value' => 60.00,
                'is_active' => true,
            ]);
        }

        return view('admin.pages.mlm.cc-points-settings', compact('setting'));
    }

    /**
     * Update the setting (value + status)
     */
    public function update(Request $request, CCSetting $ccSetting)
    {
        $validated = $request->validate([
            'value' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $ccSetting->update($validated);
        
        return back()->with('success', 'CC Point settings updated successfully.');
    }

    /**
     * Delete the setting (will auto-create default on next visit)
     */
    public function destroy(CCSetting $ccSetting)
    {
        $ccSetting->delete();
        return back()->with('success', 'CC Point setting deleted. Default will be restored.');
    }
}