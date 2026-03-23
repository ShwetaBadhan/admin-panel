<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeCare2Service;
use Illuminate\Support\Facades\Storage;
class HomeCare2ServiceController extends Controller
{
    public function index()
    {
        $HomeCare2Cards = HomeCare2Service::latest()->get();
        return view('admin.pages.home-care2-service', compact('HomeCare2Cards'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        // remove empty points
        if (isset($validated['points'])) {
            $validated['points'] = array_values(array_filter($validated['points']));
        }

        HomeCare2Service::create($validated);

        return back()->with('success', 'Section card added successfully!');
    }
    public function update(Request $request, HomeCare2Service $card)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if (isset($validated['points'])) {
            $validated['points'] = array_values(array_filter($validated['points']));
        }

        $card->update($validated);

        return back()->with('success', 'Section Card updated successfully!');
    }
    public function destroy(HomeCare2Service $HomeCare2Cards)
    {
        if ($HomeCare2Cards->image) {
            Storage::disk('public')->delete($HomeCare2Cards->image);
        }
        $HomeCare2Cards->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
