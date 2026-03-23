<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityBenefit;
use Illuminate\Support\Facades\Storage;
class CommunityBenefitController extends Controller
{
    //
    public function index()
    {
        $section = CommunityBenefit::firstOrCreate(
            ['id' => 1],
            [
                'sub_title' => 'How we works?',
                'main_title' => 'Seamless Care, Step-by-Step.',
                'side_title' => 'Seamless Care, Step-by-Step.We believe in fostering an environment that promotes independence and respects',
                'small_card_1_title' => 'Advanced Technology',
                'small_card_1_main_title' => 'Description',
                'small_card_2_title' => 'Expert Team',
                'small_card_2_main_title' => 'Description',
                'small_card_3_title' => 'Fast Results',
                'small_card_3_main_title' => 'Description',
                'small_card_4_title' => 'Trusted by Thousands',
                'small_card_4_main_title' => 'Description',
                'is_active' => true,
            ]
        );

        return view('admin.pages.community-benefits', compact('section'));
    }

    public function update(Request $request, CommunityBenefit $section)
    {
        $validated = $request->validate([
            'sub_title' => 'required|string|max:255',
            'main_title' => 'required|string|max:255',
            'side_title' => 'required|string|max:255',
            'small_card_1_title' => 'required|string|max:255',
            'small_card_1_main_title' => 'required|string|max:255',
            'small_card_2_title' => 'required|string|max:255',
            'small_card_2_main_title' => 'required|string|max:255',
            'small_card_3_title' => 'required|string|max:255',
            'small_card_3_main_title' => 'required|string|max:255',
            'small_card_4_title' => 'required|string|max:255',
            'small_card_4_main_title' => 'required|string|max:255',
            'small_card_1_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'small_card_2_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'small_card_3_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'small_card_4_image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_active' => 'sometimes|in:1,0,on,off',
        ]);

        
        // Handle Small Cards Images
        for ($i = 1; $i <= 4; $i++) {
            $field = "small_card_{$i}_image";
            if ($request->hasFile($field)) {
                if ($section->$field) {
                    Storage::disk('public')->delete($section->$field);
                }
                $validated[$field] = $request->file($field)->store('CommunityBenefits', 'public');
            }
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $section->update($validated);

        return back()->with('success', 'Benefit section updated successfully!');
    }
}
