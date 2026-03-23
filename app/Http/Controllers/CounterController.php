<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
    $counter = Counter::firstOrCreate(
        ['id' => 1],
        [
            'title1' => 'Expert Doctors',   'count1' => 90,
            'title2' => 'Different Services','count2' => 26,
            'title3' => 'Happy Patients',   'count3' => 3500,
            'title4' => 'Awards Win',       'count4' => 15,
        ]
    );

        return view('admin.pages.counters', compact('counter'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title1' => 'required|string|max:100',
            'count1' => 'required|integer|min:0',
            'title2' => 'required|string|max:100',
            'count2' => 'required|integer|min:0',
            'title3' => 'required|string|max:100',
            'count3' => 'required|integer|min:0',
            'title4' => 'required|string|max:100',
            'count4' => 'required|integer|min:0',
        ]);

        $counter = Counter::first();
        $counter->update($request->all());

        return back()->with('success', 'Counter updated successfully!');
    }
}