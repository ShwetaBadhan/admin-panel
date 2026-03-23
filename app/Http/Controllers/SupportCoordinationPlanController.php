<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportCoordinationPlan;
use Illuminate\Support\Facades\Storage;
class SupportCoordinationPlanController extends Controller
{
    //
     public function index()
    {
        $SupportCoordinationPlanCard = SupportCoordinationPlan::latest()->get();
        return view('admin.pages.support-coordination-plan', compact('SupportCoordinationPlanCard'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('SupportCoordinationPlan', 'public');
        }
        

        SupportCoordinationPlan::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, SupportCoordinationPlan $SupportCoordinationPlanCard)
    {
        $request->validate([
           'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($SupportCoordinationPlanCard->image) {
                Storage::disk('public')->delete($SupportCoordinationPlanCard->image);
            }
            $data['image'] = $request->file('photo')->store('SupportCoordinationPlan', 'public');
        }

     

        $SupportCoordinationPlanCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(SupportCoordinationPlan $SupportCoordinationPlanCard)
    {
        if ($SupportCoordinationPlanCard->image) {
            Storage::disk('public')->delete($SupportCoordinationPlanCard->image);
        }
        $SupportCoordinationPlanCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
