<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientResource;
use Illuminate\Support\Facades\Storage;
class ClientResourceController extends Controller
{
    //
    public function index()
    {
        $ClientPolicy = ClientResource::latest()->get();
        return view('admin.pages.client-resources', compact('ClientPolicy'));
    }

    public function store(request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'pdf' => 'required|mimes:pdf|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ClientPolicy', 'public');
        }
        // for pdf 
        if ($request->hasFile('pdf')) {
            $filename = time() . '_' . $request->file('pdf')->getClientOriginalName();
            $data['pdf'] = $request->file('pdf')->storeAs('ClientPolicy/pdfs', $filename, 'public');
        }


        ClientResource::create($data);

        return back()->with('success', 'Policy  added successfully!');
    }
    public function update(Request $request, ClientResource $ClientPolicy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:5120',
            'status' => 'required|in:active,inactive',
        ]);

        //  ONLY FILLABLE DATA
        $data = $request->only([
            'title',
            'description',
            'status'
        ]);

        // IMAGE
        if ($request->hasFile('image')) {
            if ($ClientPolicy->image) {
                Storage::disk('public')->delete($ClientPolicy->image);
            }
            $data['image'] = $request->file('image')->store('ClientPolicy', 'public');
        }

        // PDF
        if ($request->hasFile('pdf')) {
            if ($ClientPolicy->pdf) {
                Storage::disk('public')->delete($ClientPolicy->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('ClientPolicy/pdfs', 'public');
        }

        $ClientPolicy->update($data);

        return back()->with('success', 'Policy Card updated successfully!');
    }

    public function destroy(ClientResource $card)
    {
        if ($card->image) {
            Storage::disk('public')->delete($card->image);
        }
        if ($card->pdf) {
            Storage::disk('public')->delete($card->pdf);
        }
        $card->delete();
        return back()->with('success', 'Policy Card deleted successfully!');
    }
}

