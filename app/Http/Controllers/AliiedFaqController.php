<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AliiedFaq;
class AliiedFaqController extends Controller
{
    //
      public function index()
    {
        $faqs = AliiedFaq::latest()->get();
        return view('admin.pages.aliied-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        AliiedFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, AliiedFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(AliiedFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
