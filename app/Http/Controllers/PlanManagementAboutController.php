<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanManagementAbout;
use Illuminate\Support\Facades\Storage;
class PlanManagementAboutController extends Controller
{
    //
    public function index()
    {
        $PlanManagementAboutCard = PlanManagementAbout::latest()->get();
        return view('admin.pages.plan-management-about', compact('PlanManagementAboutCard'));
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
            $data['image'] = $request->file('image')->store('PlanManagementAbout', 'public');
        }


        PlanManagementAbout::create($data);

        return back()->with('success', 'Section card added successfully!');
    }

    public function update(Request $request, PlanManagementAbout $PlanManagementAboutCard)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($PlanManagementAboutCard->image) {
                Storage::disk('public')->delete($PlanManagementAboutCard->image);
            }
            $data['image'] = $request->file('photo')->store('PlanManagementAbout', 'public');
        }



        $PlanManagementAboutCard->update($data);

        return back()->with('success', 'Section Card updated successfully!');
    }

    public function destroy(PlanManagementAbout $PlanManagementAboutCard)
    {
        if ($PlanManagementAboutCard->image) {
            Storage::disk('public')->delete($PlanManagementAboutCard->image);
        }
        $PlanManagementAboutCard->delete();
        return back()->with('success', 'Section Card deleted successfully!');
    }
}
