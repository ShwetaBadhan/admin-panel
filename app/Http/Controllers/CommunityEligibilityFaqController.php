<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityEligiblilityFaq;
class CommunityEligibilityFaqController extends Controller
{
    //
     public function index()
    {
        $faqs = CommunityEligiblilityFaq::latest()->get();
        return view('admin.pages.community-eligibility-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        CommunityEligiblilityFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, CommunityEligiblilityFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(CommunityEligiblilityFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
