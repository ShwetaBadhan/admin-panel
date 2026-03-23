<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareFaq;
class CareFaqController extends Controller
{
    //
          public function index()
    {
        $faqs = CareFaq::latest()->get();
        return view('admin.pages.care-faq', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        CareFaq::create($request->all());

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, CareFaq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq->update($request->all());

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(CareFaq $faq)
    {
        $faq->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}
