<?php

namespace App\Http\Controllers;
use App\Models\PopularTests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PopularTestController extends Controller
{
    // to fetch data 

    public function index()
    {
        //  dd("INDEX IS RUNNING");
        $PopularTest = PopularTests::all();
        return view('admin.pages.admin-popuplar-test', compact('PopularTest'));
    }

    // for view
    public function view($id)
    {
        $PopularTest = PopularTests::findOrFail($id);
        return response()->json($PopularTest);
    }

    // to add or store data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:Active,Inactive',
            'description' => 'required|string|max:5000',
            'overview' => 'required|string|max:5000',
            'price' => 'required|string|max:5000',
        ]);

        $slug = Str::slug($request->title);
        $count = PopularTests::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        // Handle image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->store('popular', 'public');
        }

        // Store in DB
        PopularTests::create([
            'title' => $request->title,
            'slug' => $slug,
            'image' => $imageName,
            'status' => $request->status,
            'description' => $request->description,
            'overview' => $request->overview,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Popular Test Added Successfully');
    }
    public function update(Request $request)
    {
        $PopularTest = PopularTests::findOrFail($request->id);

        $PopularTest->title = $request->title;

        if ($PopularTest->isDirty('title')) {
            $slug = Str::slug($request->title);
            $count = PopularTests::where('slug', 'LIKE', "{$slug}%")
                ->where('id', '!=', $PopularTest->id)
                ->count();

            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $PopularTest->slug = $slug;
        }

        $PopularTest->status = $request->status;
        $PopularTest->description = $request->description;
        $PopularTest->overview = $request->overview;
        $PopularTest->price = $request->price;

        if ($request->hasFile('image')) {

            if ($PopularTest->image && Storage::disk('public')->exists($PopularTest->image)) {
                Storage::disk('public')->delete($PopularTest->image);
            }

            $PopularTest->image = $request->file('image')->store('popular', 'public');
        }

        $PopularTest->save();

        return response()->json(['success' => true]);
    }


    public function delete($id)
    {
        $PopularTest = PopularTests::findOrFail($id);

        // delete old image
        if ($PopularTest->image && file_exists(public_path('storage/' . $PopularTest->image))) {
            unlink(public_path('storage/' . $PopularTest->image));
        }

        $PopularTest->delete();

        return response()->json(['success' => true]);
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        PopularTests::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

}
