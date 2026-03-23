<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
class DoctorController extends Controller
{

    // TO FTECH IN FRONTEND
    public function frontendDoctors()
    {
        $doctors = Doctor::where('status', '1')->get(); 

        return view('website.pages.doctors', compact('doctors'));
    }



    // to fetch data 

    public function index()
    {
        //  dd("INDEX IS RUNNING");
        $doctors = Doctor::all();
        return view('admin.pages.admin-doctor', compact('doctors'));
    }

    // for view
    public function view($id)
    {
        $doctor = Doctor::findOrFail($id);
        return response()->json($doctor);
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
            'specialization' => 'nullable|string|max:5000',
        ]);

        // Upload image
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);
        }

        // Store in DB
        Doctor::create([
            'fullname' => $request->fullname,
            'image' => $imageName,
            'status' => $request->status,
            'designation' => $request->designation,
            'specialization' => $request->specialization,
        ]);

        return redirect()->back()->with('success', 'Doctor Added Successfully');
    }
    public function update(Request $request)
    {
        $doctor = Doctor::find($request->id);

        $doctor->fullname = $request->fullname;
        $doctor->status = $request->status;
        $doctor->designation = $request->designation;
        $doctor->specialization = $request->specialization;

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads'), $imageName);

            // update new image
            $doctor->image = $imageName;
        }

        $doctor->save();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $doctor = Doctor::findOrFail($id);

        // delete old image
        if ($doctor->image && file_exists(public_path('uploads/' . $doctor->image))) {
            unlink(public_path('uploads/' . $doctor->image));
        }

        $doctor->delete();

        return response()->json(['success' => true]);
    }
  public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Doctor::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

}
