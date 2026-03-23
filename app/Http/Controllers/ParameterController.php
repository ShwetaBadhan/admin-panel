<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ParameterController extends Controller
{

    // function for search
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $parameters = \App\Models\Parameter::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->get()
            ->map(function ($parameters) {
                return [
                    'id' => $parameters->id,
                    'title' => $parameters->title,
                    'status' => $parameters->status,
                    'icon' => $parameters->icon,
                    'price' => $parameters->price,
                    'icon_url' => $parameters->icon
                        ? asset('storage/' . $parameters->icon)
                        : asset('admin/images/no-image.png'),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $parameters
        ]);
    }
    public function index()
    {
        $parameters = Parameter::latest()->get();
        return view('admin.pages.parameter', compact('parameters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:parameters,title',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'detail_id' => 'nullable|array',
            'detail_id.*' => 'integer|exists:tests,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('package-icons', 'public');
        }

        // Save selected test IDs as JSON array
        $validated['detail_id'] = $request->filled('detail_id') ? $request->detail_id : [];

        Parameter::create($validated);

        return redirect()->route('admin.pages.parameter')
            ->with('success', 'Parameter created successfully!');
    }

    public function update(Request $request, Parameter $parameter)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:parameters,title,' . $parameter->id,
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'overview' => 'nullable|string',
            'detail_id' => 'nullable|array',
            'detail_id.*' => 'integer|exists:tests,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg+xml|max:2048',
        ]);

        // Update slug only when title changes
        if ($request->filled('title') && $request->title !== $parameter->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle icon replacement
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($parameter->icon && Storage::disk('public')->exists($parameter->icon)) {
                Storage::disk('public')->delete($parameter->icon);
            }
            $validated['icon'] = $request->file('icon')->store('package-icons', 'public');
        }

        // Save selected test IDs
        $validated['detail_id'] = $request->filled('detail_id') ? $request->detail_id : [];

        $parameter->update($validated);

        return redirect()->route('admin.pages.parameter')
            ->with('success', 'Parameter updated successfully!');
    }

    public function destroy(Parameter $parameter)
    {
        if ($parameter->icon && Storage::disk('public')->exists($parameter->icon)) {
            Storage::disk('public')->delete($parameter->icon);
        }

        $parameter->delete();

        return back()->with('success', 'Parameter deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Parameter::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}