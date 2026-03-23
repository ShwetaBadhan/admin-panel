<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SupportCoordinationBenefit;
class SupportCoordinationBenefitController extends Controller
{
    //
    public function index()
    {
        $SupportCoordinationBenefits = SupportCoordinationBenefit::first();

        if (!$SupportCoordinationBenefits) {
            $SupportCoordinationBenefits = SupportCoordinationBenefit::create([
                'main_title' => 'Continuity Care is th best  for elder care',
                'description_1' => 'Best services we serve',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.support-coordination-benefit', compact('SupportCoordinationBenefits'));
    }

    public function update(Request $request)
    {
        $SupportCoordinationBenefits = SupportCoordinationBenefit::firstOrFail();

        $request->validate([
            'main_title' => 'required|string',
            'description_1' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'description_1' => $request->description_1,
            'points' => array_values(array_filter($request->points ?? [])),
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($SupportCoordinationBenefits->image) {
                Storage::disk('public')->delete($SupportCoordinationBenefits->image);
            }
            $data['image'] = $request->file('image')->store('supportbenefit', 'public');
        }
       

        $SupportCoordinationBenefits->update($data);

        return back()->with('success', 'Benefits  Updated Successfully');
    }
}
