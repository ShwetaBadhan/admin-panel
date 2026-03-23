<?php

namespace App\Http\Controllers;

use App\Models\Subparameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SubparameterController extends Controller
{
    // function for search
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $subparameters = \App\Models\Subparameter::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->get()
            ->map(function ($subparameters) {
                return [
                    'id' => $subparameters->id,
                    'title' => $subparameters->title,
                    'status' => $subparameters->status,
                    
                    'price' => $subparameters->price,
                    
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $subparameters
        ]);
    }
    public function index()
    {
        // Only show active ones (recommended), or remove ->active() to show all
        $subparameters = Subparameter::latest()->get();

        return view('admin.pages.admin-subparameters', compact('subparameters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:subparameters,title',
            'parameter_id' => 'required|array|min:1',
            'parameter_id.*' => 'exists:parameters,id',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'test_ids' => 'nullable|array',
            'test_ids.*' => 'exists:tests,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'title',
            'parameter_id',
            'test_ids',
            'price',
            'description',
            'status'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('subparameters', 'public');
        }

        Subparameter::create($data);

        return back()->with('success', 'Health Package created successfully!');
    }

    public function update(Request $request, Subparameter $subparameter)
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subparameters', 'title')->ignore($subparameter->id),
            ],
            'parameter_id' => 'required|array|min:1',
            'parameter_id.*' => 'exists:parameters,id',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'test_ids' => 'nullable|array',
            'test_ids.*' => 'exists:tests,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'title',
            'parameter_id',
            'test_ids',
            'price',
            'description',
            'status'
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            if ($subparameter->image && Storage::disk('public')->exists($subparameter->image)) {
                Storage::disk('public')->delete($subparameter->image);
            }
            $data['image'] = $request->file('image')->store('subparameters', 'public');
        }

        $subparameter->update($data);

        return back()->with('success', 'Health Package updated successfully!');
    }

    public function destroy(Subparameter $subparameter)
    {
        // Delete image from storage
        if ($subparameter->image && Storage::disk('public')->exists($subparameter->image)) {
            Storage::disk('public')->delete($subparameter->image);
        }

        $subparameter->delete();

        return back()->with('success', 'Health Package deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Subparameter::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}