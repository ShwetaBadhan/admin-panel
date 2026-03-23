<?php

namespace App\Http\Controllers;

use App\Models\Faqspackage;
use App\Models\Parameter;
use App\Models\Subparameter;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    // function to search
     public function search(Request $request)
    {
        $keyword = $request->keyword;

        $faqs = \App\Models\FaqsPackage::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('status', 'LIKE', "%{$keyword}%")
            ->get()
            ->map(function ($faqs) {
                return [
                    'id' => $faqs->id,
                    'question' => $faqs->question,
                    'status' => $faqs->status,
                   
                   
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $faqs
        ]);
    }
    public function index()
    {
        $faqs = Faqspackage::with(['parameter', 'subparameter'])->latest()->get();
        return view('admin.pages.faqspackages', compact('faqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question'        => 'required|string|max:255',
            'answer'          => 'required|string',
            'link_type'       => 'required|in:none,parameter,package',
            'parameter_id'    => 'required_if:link_type,parameter|nullable|exists:parameters,id',
            'subparameter_id' => 'required_if:link_type,package|nullable|exists:subparameters,id',
            'status'          => 'required|in:active,inactive',
        ]);

        Faqspackage::create([
            'question'        => $request->question,
            'answer'          => $request->answer,
            'parameter_id'    => $request->link_type === 'parameter' ? $request->parameter_id : null,
            'subparameter_id' => $request->link_type === 'package' ? $request->subparameter_id : null,
            'status'          => $request->status,
            'sort_order'      => Faqspackage::max('sort_order') + 1,
        ]);

        return back()->with('success', 'FAQ added successfully!');
    }

    public function update(Request $request, Faqspackage $faqspackage)
    {
        $request->validate([
            'question'        => 'required|string|max:255',
            'answer'          => 'required|string',
            'link_type'       => 'required|in:none,parameter,package',
            'parameter_id'    => 'required_if:link_type,parameter|nullable|exists:parameters,id',
            'subparameter_id' => 'required_if:link_type,package|nullable|exists:subparameters,id',
            'status'          => 'required|in:active,inactive',
        ]);

        $faqspackage->update([
            'question'        => $request->question,
            'answer'          => $request->answer,
            'parameter_id'    => $request->link_type === 'parameter' ? $request->parameter_id : null,
            'subparameter_id' => $request->link_type === 'package' ? $request->subparameter_id : null,
            'status'          => $request->status,
        ]);

        return back()->with('success', 'FAQ updated successfully!');
    }

    public function destroy(Faqspackage $faqspackage)
    {
        $faqspackage->delete();
        return back()->with('success', 'FAQ deleted successfully!');
    }
}