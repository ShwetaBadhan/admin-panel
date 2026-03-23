<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobCareer;

class CareerApiController extends Controller
{
    public function index()
    {
        $jobs = JobCareer::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'career_jobs' => $jobs->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'type' => $job->type,
                    'location' => $job->location,
                    'description' => $job->description,
                    'qualification' => $job->qualification,
                    'experience' => $job->experience,
                    'salary_range' => $job->salary_range,
                    'is_featured' => $job->is_featured,
                    'is_active' => $job->is_active,
                ];
            }),
        ]);
    }
    // for single detail
    public function show($slug)
    {
        $job = JobCareer::where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (!$job) {
            return response()->json([
                'message' => 'Job not found'
            ], 404);
        }

        return response()->json([
            'career_job' => [
                'title' => $job->title,
                'type' => $job->type,
                'location' => $job->location,
                'description' => $job->description,
                'qualification' => $job->qualification,
                'experience' => $job->experience,
                'salary_range' => $job->salary_range,
            ]
        ]);
    }

}
