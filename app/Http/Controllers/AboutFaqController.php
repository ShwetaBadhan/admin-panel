<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AboutFaq;
class AboutFaqController extends Controller
{
    //
    public function index()
    {
        $faqs = AboutFaq::latest()->get();
        return view('admin.pages.about-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        AboutFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, AboutFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(AboutFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
