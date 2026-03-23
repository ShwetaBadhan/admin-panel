<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommitmentTwo;
use Illuminate\Support\Facades\Storage;

class CommitmentTwoController extends Controller
{
    //
    public function index()
    {
        $CommitmentTwo = CommitmentTwo::firstOrCreate(
            [],
            [

                'main_title' => 'The New Aged Care Act is here',
                'sub_title'=>'this is sub title',
                'description_1' => 'Continuity Care is a dedicated provider of disability and independent living services, offering comprehensive care coordination and navigation. Everything we do is focused on bridging the gap between clinical requirements and daily life, ensuring people with disabilities can live with dignity and independence.With a deep commitment to integrated support at our core, Continuity Care is dedicated to empowering individuals through expert guidance. We believe everyone deserves a seamless care experience that celebrates their choices and supports their long-term goals.',
                'is_active' => true
            ]
        );


        return view('admin.pages.commitment-two-section', compact('CommitmentTwo'));
    }
    public function update(Request $request)
    {
        $CommitmentTwo = CommitmentTwo::firstOrFail();

        $request->validate([
            'main_title' => 'required|string|max:200',
            'sub_title' => 'required|string|max:400',
            'description_1' => 'required|string',
            'is_active' => 'boolean'
        ]);

        // Data
        $data = [
            'main_title' => $request->main_title,
            'sub_title'=>$request->sub_title,
            'description_1' => $request->description_1,
            'is_active' => $request->is_active ?? 1,
        ];

       
        $CommitmentTwo->update($data);
        return back()->with('success', ' Commitment Section Updated Successfully');
    }
}
