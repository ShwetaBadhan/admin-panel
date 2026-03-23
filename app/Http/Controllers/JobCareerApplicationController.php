<?php

namespace App\Http\Controllers;
use App\Models\JobCareerApplication;
use App\Models\JobCareer;

use Illuminate\Http\Request;

class JobCareerApplicationController extends Controller
{
    // search function

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $application = \App\Models\JobCareerApplication::where('fullname', 'LIKE', "%$keyword%")
            
            ->orWhere('job_title', 'LIKE', "%$keyword%")
            ->orWhere('phone', 'LIKE', "%$keyword%")
            ->get();
            return response()->json([
                "status"=>true,
                "data"=>$application
            ]);

        // return view('admin.pages.partials.job-application-row', compact('application'));
    }
    // TO FETCH ALL THE DATA OF Career Application
    public function index()
    {
        $application = JobCareerApplication::orderBy('id', 'desc')->get();
        return view('admin.pages.admin-job-application', compact('application'));
    }

    //  
    public function store(Request $request)
    {
        $request->validate([
            'job_slug' => 'required|exists:job_careers,slug',
            'fullname' => [
                'required',
                'regex:/^[a-zA-Z\s\.\-]{2,255}$/'
            ],
            'email' => [
                'required',
                'regex:/^[^<>{}()*$!;:=\[\]]+$/'
            ],
            'phone' => [
                'required',
                'regex:/^\+91[6-9]\d{9}$/'
            ],
            'address' => [
                'nullable',
                'max:5000',
                'regex:/^(?!.*(<|>|script|onload|onclick|javascript:)).*$/i'
            ],
            'details' => [
                'nullable',
                'max:5000',
                'regex:/^(?!.*(<|>|script|onload|onclick|javascript:)).*$/i'
            ],
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
        ]);
        $resumePath = null;

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        }


        $job = JobCareer::where('slug', $request->job_slug)->firstOrFail();
        JobCareerApplication::create([
            'job_id' => $job->id,
            'job_title' => $job->title,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'details' => $request->details,
            'resume' => $resumePath,
        ]);


        return back()->with('success', 'Your application has been submitted successfully!');
    }
    // FOR VIEW JobCareerApplication LEADS
    public function view($id)
    {
        $application = JobCareerApplication::findOrFail($id);
        return response()->json($application);
    }

    // FOR UPDATE AND EDIT
    public function update(Request $request)
    {
        $application = JobCareerApplication::find($request->id);
        $application->fullname = $request->fullname;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->address = $request->address;
        $application->details = $request->details;
        $application->resume = $request->resume;


        $application->save();
        return response()->json(['success' => true]);
    }


    public function delete($id)
    {
        JobCareerApplication::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        JobCareerApplication::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
