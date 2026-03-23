<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteImagesController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::getSettings();
        return view('admin.pages.site-images', compact('setting'));
    }

  public function updatePopup(Request $request)
{
    $setting = SiteSetting::getSettings();

    $request->validate([
        'popup_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5048',
    ]);

    if ($request->hasFile('popup_image')) {
        if ($setting->popup_image) {
            Storage::disk('public')->delete($setting->popup_image);
        }
        $setting->popup_image = $request->file('popup_image')->store('site-images', 'public');
    }

    $setting->popup_enabled = $request->has('popup_enabled');
    $setting->save();

    return back()->with('success', 'Popup image updated successfully!');
}

public function updateAds(Request $request)
{
    $setting = SiteSetting::getSettings();

    $request->validate([
        'ads_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5048',
    ]);

    if ($request->hasFile('ads_image')) {
        if ($setting->ads_image) {
            Storage::disk('public')->delete($setting->ads_image);
        }
        $setting->ads_image = $request->file('ads_image')->store('site-images', 'public');
    }

    $setting->ads_enabled = $request->has('ads_enabled');
    $setting->save();

    return back()->with('success', 'Advertisement banner updated successfully!');
}
}