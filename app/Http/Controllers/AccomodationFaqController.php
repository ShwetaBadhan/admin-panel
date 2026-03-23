<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\AccomodationFaq;
use Illuminate\Http\Request;

class AccomodationFaqController extends Controller
{
    //

    public function index()
    {
        $AccomodationFaqs = AccomodationFaq::latest()->get();
        return view('admin.pages.acccommodation-faq', compact('AccomodationFaqs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'service_id' => 'nullable|array',
            'service_id.*' => 'integer|exists:independent_accommodations,id',
        ]);


       
        // Save selected test IDs as JSON array
        $validated['service_id'] = $request->input('service_id', []);


        AccomodationFaq::create($validated);

        return back()->with('success', ' Faq created successfully!');
    }

    public function update(Request $request, AccomodationFaq $card)
    {
        // dd($request->all());

        $validated = $request->validate([
            'question' => 'required|string|max:255,' . $card->id,
            'answer' => 'required|string|max:255,',
            'status' => 'required|in:active,inactive',
            'service_id' => 'nullable|array',
            'service_id.*' => 'integer|exists:independent_accommodations,id',
        ]);


        $validated['service_id'] = $request->input('service_id', []);

        $card->update($validated);

        return back()->with('success', 'Faq updated successfully!');
    }


    public function destroy(AccomodationFaq $card)
    {
        
        $card->delete();

        return back()->with('success', ' Faq deleted successfully!');
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        AccomodationFaq::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
