<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportCoordinationFaq;
use Illuminate\Support\Facades\Storage;
class SupportCoordinationFaqController extends Controller
{
    //
    public function index()
    {
        $faqs = SupportCoordinationFaq::latest()->get();
        return view('admin.pages.support-coordination-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        SupportCoordinationFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, SupportCoordinationFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(SupportCoordinationFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
