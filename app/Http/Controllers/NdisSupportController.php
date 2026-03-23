<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NdisSupport;
class NdisSupportController extends Controller
{
    //
    public function index()
    {
        $NdisSupports = NdisSupport::first();

        if (!$NdisSupports) {
            $NdisSupports = NdisSupport::create([
                'main_title' => 'NDIS Service',
                'description_1' => 'Continuity Care is a dedicated provider...',
                'points' => [],
                'is_active' => true
            ]);
        }

        return view('admin.pages.ndis-support-section', compact('NdisSupports'));
    }

    public function update(Request $request)
    {
        $NdisSupports = NdisSupport::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'description_1' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = [
            'main_title' => $request->main_title,
            'points' => array_values(array_filter($request->points ?? [])),
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('image')) {
            if ($NdisSupports->image) {
                Storage::disk('public')->delete($NdisSupports->image);
            }
            $data['image'] = $request->file('image')->store('admin-act', 'public');
        }

        $NdisSupports->update($data);

        return back()->with('success', 'Support Section Updated Successfully');
    }

}
