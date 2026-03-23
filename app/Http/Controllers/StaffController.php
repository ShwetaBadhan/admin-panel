<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
class StaffController extends Controller
{
    // for index
    public function index()
    {
        $staffs = Staff::all();
        return view('admin.pages.admin-staff', compact('staffs'));
    }

    // to add or store data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'fullname' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|string|max:20',
            'designation' => 'required|string|max:255',
            'tag' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
        ]);

        // Upload image
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
        }

        // Store in DB
        Staff::create([
            'fullname' => $request->fullname,
            'image' => $imageName,
            'status' => $request->status,
            'designation' => $request->designation,
            'tag' => $request->tag,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'twitter' => $request->twitter,

        ]);

        return redirect()->back()->with('success', 'Staff Added Successfully');
    }

    // to view staff
    public function view($id)
    {
        $staff = Staff::findOrFail($id);
        return response()->json($staff);
    }

    // to update staff 
    public function update(Request $request)
    {
        $staff = Staff::find($request->id);
        $staff->fullname = $request->fullname;
        $staff->status = $request->status;
        $staff->designation = $request->designation;
        $staff->tag = $request->tag;
        $staff->facebook = $request->facebook;
        $staff->instagram = $request->instagram;
        $staff->linkedin = $request->linkedin;
        $staff->twitter = $request->twitter;
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);

            // update new image
            $staff->image = $imageName;
        }

        // to save
        $staff->save();
        return response()->json(['success' => true]);

    }

    // to single delete
    public function delete($id)
    {

        $staff = Staff::findOrFail($id);
        if ($staff->image && file_exists(public_path('uploads/' . $staff->image))) {
            unlink(public_path('uploads/' . $staff->image));
        }
        $staff->delete();

        return response()->json([
            'status' => true
        ]);
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json([
                'error' => true,
                'message' => 'No IDs Received'
            ]);
        }

        Staff::whereIn('id', $request->ids)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Deleted Successfully'
        ]);
    }


}
