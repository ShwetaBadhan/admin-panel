<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AgedService;
class AgedServiceController extends Controller
{
    //
    //
    public function index()
    {
        $section = AgedService::firstOrCreate(
            ['id' => 1],
            [
                'sub_title' => 'Our Services ',
                'main_title' => 'Seamless Care, Step-by-Step.',
                'small_card_1_title' => 'Self Managed',
                'small_card_1_content' => 'Description',
                'small_card_2_title' => 'Fully Managed',
                'small_card_2_content' => 'Description',
                'small_card_3_title' => 'Transition Care',
                'small_card_3_content' => 'Description',
                'is_active' => true,
            ]
        );

        return view('admin.pages.aged-service-section', compact('section'));
    }

    public function update(Request $request, AgedService $section)
    {
        $validated = $request->validate([
            'sub_title' => 'required|string|max:255',
            'main_title' => 'required|string|max:255',
            'small_card_1_title' => 'required|string|max:255',
            'small_card_1_content' => 'required|string|max:500',
            'small_card_2_title' => 'required|string|max:255',
            'small_card_2_content' => 'required|string|max:500',
            'small_card_3_title' => 'required|string|max:255',
            'small_card_3_content' => 'required|string|max:500',
        ]);

        // checkbox handling
        $validated['is_active'] = $request->has('is_active');

        $section->update($validated);

        return back()->with('success', 'Service section updated successfully!');
    }


}
