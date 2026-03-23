<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller;
use App\Models\JobCareer;
use Illuminate\Http\Request;

class JobCareerController extends Controller
{

    public function apply($slug)
    {
        $job = JobCareer::where('slug', $slug)->firstOrFail();

        return view('website.pages.career-form', compact('job'));
    }


    // ────────────── MAIN ADMIN PAGE (All-in-One with Modals) ──────────────
    public function index()
    {
        $jobs = JobCareer::orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pages.jobcareer', compact('jobs'));
    }

    // ────────────── STORE NEW JOB (from Add Modal) ──────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'experience' => 'required|string|max:100',
            'qualification' => 'required|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        // Ensure boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        JobCareer::create($validated);

        return redirect()->route('jobcareer.index')
            ->with('success', 'Job opening created successfully!');
    }

    // ────────────── UPDATE JOB (from Edit Modal) ──────────────
    public function update(Request $request, JobCareer $jobCareer)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'experience' => 'required|string|max:100',
            'qualification' => 'required|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $jobCareer->update($validated);

        return redirect()->route('jobcareer.index')
            ->with('success', 'Job opening updated successfully!');
    }

    // ────────────── DELETE JOB ──────────────
    public function destroy(JobCareer $jobCareer)
    {
        $jobCareer->delete();

        return back()->with('success', 'Job opening deleted successfully!');
    }
}