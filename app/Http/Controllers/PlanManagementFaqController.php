<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanManagementFaq;
use Illuminate\Support\Facades\Storage;
class PlanManagementFaqController extends Controller
{
    //
     public function index()
    {
        $faqs = PlanManagementFaq::latest()->get();
        return view('admin.pages.plan-management-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        PlanManagementFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, PlanManagementFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(PlanManagementFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
