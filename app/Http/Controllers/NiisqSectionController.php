<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NiisqSection;
class NiisqSectionController extends Controller
{
    // /function for index
    public function index()
    {
        $NiisqSections = NiisqSection::first();

        if (!$NiisqSections) {
            $NiisqSections = NiisqSection::create([
                'about_content' => 'Continuity Care is th best  for elder care',
                'service_title'=>'Best services we serve',
                'service_about'=>'this is about the services',
                'eligibility'=>'These are the following eligible persons ',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.niisq-page', compact('NiisqSections'));
    }

    public function update(Request $request)
    {
        $NiisqSections = NiisqSection::firstOrFail();

        $request->validate([
            'about_content' => 'required|string',
            'service_title' => 'required|string|max:255',
            'service_about' => 'required|string',
            'eligibility' => 'required|string|max:450',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'image_1' => 'nullable|image|max:2048',
            'image_2' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'about_content' => $request->about_content,
            'service_title' => $request->service_title,
            'service_about' => $request->service_about,
            'eligibility' => $request->eligibility,
            'points' => array_values(array_filter($request->points ?? [])),
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image_1')) {
            if ($NiisqSections->image_1) {
                Storage::disk('public')->delete($NiisqSections->image_1);
            }
            $data['image_1'] = $request->file('image_1')->store('niisq', 'public');
        }
        if ($request->hasFile('image_2')) {
            if ($NiisqSections->image_2) {
                Storage::disk('public')->delete($NiisqSections->image_2);
            }
            $data['image_2'] = $request->file('image_2')->store('niisq2', 'public');
        }

        $NiisqSections->update($data);

        return back()->with('success', 'NIISQ  Updated Successfully');
    }
}
