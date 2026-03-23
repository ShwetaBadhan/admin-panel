<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{


    // search function
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $contact = \App\Models\Contact::where('fullname', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->orWhere('phone', 'LIKE', "%$keyword%")
            ->get();
            return response()->json([
                "status"=> true,
                "data"=>$contact
            ]);

        // return view('admin.pages.partials.contact-rows', compact('contact'));
    }

    // Total Count
    public function countLead()
    {
        $totalLeads = Contact::count();
        return view('admin.pages.dashboard', compact('totalLeads'));
    }


    // TO FETCH ALL THE DATA OF Contact
    public function index()
    {
        $contact = Contact::orderBy('id', 'desc')->get();
        return view('admin.pages.admin-contact', compact('contact'));
    }
    // to store
    public function store(Request $request)
    {

        // Validation
        $request->validate([
            'fullname' => [
                'required',
                'regex:/^[a-zA-Z\s\.\-]{2,255}$/'
            ],
            'email' => [
                'required',
                'regex:/^[^<>{}()*$!;:=\[\]]+$/'
            ],
            'phone' => [
                'required',
                'regex:/^\+91[6-9]\d{9}$/'
            ],
            'subject' => [
                'required',
                'regex:/^[a-zA-Z\s\.\-]{2,255}$/'
            ],
            'message' => [
                'nullable',
                'max:5000',
                'regex:/^(?!.*(<|>|script|onload|onclick|javascript:)).*$/i'
            ],
        ]);



        Contact::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'ip' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Thankyou for contacting us , We will get back to you soon !!!!');
    }

    // TO VIEW ALL THE DATA
    public function view($id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json($contact);
    }

    // FOR UPDATE AND EDIT
    public function update(Request $request)
    {
        $contact = Contact::find($request->id);
        $contact->fullname = $request->fullname;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->message = $request->message;

        $contact->save();
        return response()->json(['success' => true]);
    }

    // TO DELETE
    public function delete($id)
    {
        Contact::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Contact::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

}
