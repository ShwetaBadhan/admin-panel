<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgedBenefit;
use Illuminate\Support\Facades\Storage;

class AgedBenefitController extends Controller
{
    //
    public function index()
    {
        $AgedBenefits = AgedBenefit::first();

        if (!$AgedBenefits) {
            $AgedBenefits = AgedBenefit::create([
                'main_title' => 'Aged Care Benefit',
                'side_title' => 'Side Title',
                'sub_title' => 'Sub Title',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.aged-benefit', compact('AgedBenefits'));
    }

    public function update(Request $request)
    {
        $AgedBenefits = AgedBenefit::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'side_title' => 'required|string|max:200',
            'sub_title' => 'required|string|max:200',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'description_1' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'side_title' => $request->side_title,
            'sub_title' => $request->sub_title,
            'points' => array_values(array_filter($request->points ?? [])),
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($AgedBenefits->image) {
                Storage::disk('public')->delete($AgedBenefits->image);
            }
            $data['image'] = $request->file('image')->store('aged-benefit', 'public');
        }

        $AgedBenefits->update($data);

        return back()->with('success', 'Benefit Section Updated Successfully');
    }
}
