<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\Subparameter;

class PackageController extends Controller
{
    // TO LAND ON PAGE
    public function index()
    {
        $packages = Package::all();
        $subparameters = Subparameter::where('status', 'active')->orderBy('title')->get();

        return view('admin.pages.packages', compact('packages', 'subparameters'));
    }


    // STORE PACKAGE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:active,inactive',
            'description' => 'required|string',
            'subparameter_id' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/package'), $imageName);
        }

        Package::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'image' => $imageName ?? null,
            'status' => $request->status,
            'description' => $request->description,
            'subparameter_id' => json_encode($request->subparameter_id),
        ]);

        return redirect()->back()->with('success', 'Package Added Successfully');
    }

    // TO VIEW DATA OF PACKAGE
    public function view($id)
    {
        $packages = Package::with('subparameter')->findOrFail($id);

        return response()->json([
            'id' => $packages->id,
            'title' => $packages->title,
            'slug' => $packages->slug,
            'status' => $packages->status,
            'description' => $packages->description,
            'subparameter_id' => $packages->subparameter_id,
            'subparameter_title' => $packages->subparameter->title ?? '—',
        ]);
    }

}
