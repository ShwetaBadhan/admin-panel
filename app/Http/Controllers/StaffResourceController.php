<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffResource;
use Illuminate\Support\Facades\Storage;
class StaffResourceController extends Controller
{
    //

    public function index()
    {
        $StaffPolicy = StaffResource::latest()->get();
        return view('admin.pages.staff-resources', compact('StaffPolicy'));
    }

    public function store(request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'pdf' => 'required|mimes:pdf|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('StaffPolicy', 'public');
        }
        // for pdf 
        if ($request->hasFile('pdf')) {
            $filename = time() . '_' . $request->file('pdf')->getClientOriginalName();
            $data['pdf'] = $request->file('pdf')->storeAs('StaffPolicy/pdfs', $filename, 'public');
        }


        StaffResource::create($data);

        return back()->with('success', 'Policy  added successfully!');
    }
    public function update(Request $request, StaffResource $StaffPolicy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        //  ONLY FILLABLE DATA
        $data = $request->only([
            'title',
            'description',
            'status'
        ]);

        // IMAGE
        if ($request->hasFile('image')) {
            if ($StaffPolicy->image) {
                Storage::disk('public')->delete($StaffPolicy->image);
            }
            $data['image'] = $request->file('image')->store('StaffPolicy', 'public');
        }

        // PDF
        if ($request->hasFile('pdf')) {
            if ($StaffPolicy->pdf) {
                Storage::disk('public')->delete($StaffPolicy->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('StaffPolicy/pdfs', 'public');
        }

        $StaffPolicy->update($data);

        return back()->with('success', 'Policy Card updated successfully!');
    }

    public function destroy(StaffResource $card)
    {
        if ($card->image) {
            Storage::disk('public')->delete($card->image);
        }
        if ($card->pdf) {
            Storage::disk('public')->delete($card->pdf);
        }
        $card->delete();
        return back()->with('success', 'Policy Card deleted successfully!');
    }
}
