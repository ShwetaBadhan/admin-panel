<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityWork;
class CommunityWorkController extends Controller
{
    //
    
      public function index()
    {
        $section = CommunityWork::firstOrCreate(
            ['id' => 1],
            [
                'sub_title' => 'How we works?',
                'main_title' => 'Seamless Care, Step-by-Step.',
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

        return view('admin.pages.community-works-section', compact('section'));
    }

    public function update(Request $request, CommunityWork $section)
    {
        $validated = $request->validate([
            'sub_title' => 'required|string|max:255',
            'main_title' => 'required|string|max:255',
            'small_card_1_title'=>'required|string|max:255',
            'small_card_1_main_title'=>'required|string|max:255',
            'small_card_2_title'=>'required|string|max:255',
            'small_card_2_main_title'=>'required|string|max:255',
            'small_card_3_title'=>'required|string|max:255',
            'small_card_3_main_title'=>'required|string|max:255',
            'small_card_4_title'=>'required|string|max:255',
            'small_card_4_main_title'=>'required|string|max:255',
            'is_active' => 'sometimes|in:1,0,on,off',
        ]);

       

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $section->update($validated);

        return back()->with('success', 'How We Work section updated successfully!');
    }
}
