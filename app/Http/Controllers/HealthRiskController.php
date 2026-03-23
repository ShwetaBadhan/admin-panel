<?php

namespace App\Http\Controllers;

use App\Models\HealthRisk;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HealthRiskController extends Controller
{

    // function for search
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $healthRisks = \App\Models\HealthRisk::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->get()
            ->map(function ($healthRisks) {
                return [
                    'id' => $healthRisks->id,
                    'title' => $healthRisks->title,
                    'status' => $healthRisks->status,
                   
                   
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $healthRisks
        ]);
    }
    /**
     * Display listing of health risks
     */
    public function index()
    {
        $healthRisks = HealthRisk::latest()->get();
        return view('admin.pages.health-risks', compact('healthRisks'));
    }

    /**
     * Store new health risk
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'parameter_id' => 'required|array',
            'parameter_id.*' => 'exists:parameters,id',
            'icon' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('health-risks', 'public');
        }

        // Save parameter_id as JSON array
        $data['parameter_id'] = $request->parameter_id;

        HealthRisk::create($data);

        return back()->with('success', 'Health Risk created successfully!');
    }

    /**
     * Update existing health risk
     */
    public function update(Request $request, HealthRisk $healthRisk)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'parameter_id' => 'required|array',
            'parameter_id.*' => 'exists:parameters,id',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($healthRisk->icon && Storage::disk('public')->exists($healthRisk->icon)) {
                Storage::disk('public')->delete($healthRisk->icon);
            }
            $data['icon'] = $request->file('icon')->store('health-risks', 'public');
        }

        $data['parameter_id'] = $request->parameter_id;

        $healthRisk->update($data);

        return back()->with('success', 'Health Risk updated successfully!');
    }

    /**
     * Delete health risk
     */
    public function destroy(HealthRisk $healthRisk)
    {
        if ($healthRisk->icon && Storage::disk('public')->exists($healthRisk->icon)) {
            Storage::disk('public')->delete($healthRisk->icon);
        }

        $healthRisk->delete();

        return back()->with('success', 'Health Risk deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        HealthRisk::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}