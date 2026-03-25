<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CountersSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CountersSectionController extends Controller
{
    public function index()
    {
        $section = CountersSection::first();
        
        if (!$section) {
            // ✅ Create and save immediately
            $section = CountersSection::create([
                'counters' => [
                    ['number' => '3145', 'suffix' => '+', 'label' => 'Organic Products'],
                    ['number' => '100', 'suffix' => '%', 'label' => 'Organic Guaranteed'],
                    ['number' => '160', 'suffix' => '+', 'label' => 'Qualified Farmers'],
                    ['number' => '310', 'suffix' => '+', 'label' => 'Agriculture Firm'],
                ],
                'background_color' => '#2d7a3e',
                'is_active' => true,
            ]);
        }

        return view('admin.pages.counters-section', compact('section'));
    }

    public function update(Request $request, CountersSection $section)
    {
        $request->validate([
            'counters' => 'required|array|min:1',
            'counters.*.number' => 'required|string|max:50',
            'counters.*.suffix' => 'nullable|string|max:10',
            'counters.*.label' => 'required|string|max:255',
            'background_color' => 'nullable|string|max:7',
            'background_image' => 'nullable|image|max:5048',
        ]);

        if ($request->hasFile('background_image')) {
            if ($section->background_image) {
                Storage::disk('public')->delete($section->background_image);
            }
            $section->background_image = $request->file('background_image')->store('counters', 'public');
        }

        $section->update([
            'counters' => $request->counters,
            'background_color' => $request->background_color,
        ]);

        return redirect()->back()->with('success', '✅ Counters section updated successfully!');
    }
}