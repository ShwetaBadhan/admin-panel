<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Mail\AppointmentConfirmation;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class AppointmentController extends Controller
{

    // search function

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $appointments = \App\Models\Appointment::where('fullname', 'LIKE', "%$keyword%")
            ->orWhere('email', 'LIKE', "%$keyword%")
            ->orWhere('phone', 'LIKE', "%$keyword%")
            ->get();
        // return json
        return response()->json([
            "status" => true,
            "data" => $appointments
        ]);

        // return view('admin.pages.partials.appointment-rows', compact('appointments'));
    }

    // TO FETCH ALL THE DATA OF APPOINTMENT
    public function index()
    {
        $appointments = Appointment::orderBy('id', 'desc')->get();
        return view('admin.pages.admin-appointment', compact('appointments'));
    }


    public function store(Request $request)
    {


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


            'choosedoctor' => 'nullable|string|max:255',

            'selectdepartment' => [
                'required',
                'regex:/^[a-zA-Z\s\.\-]{2,255}$/'
            ],

            'appointmentdate' => [
                'required',
                'date'
            ],


            'message' => [
                'nullable',
                'max:5000',
                'regex:/^(?!.*(<|>|script|onload|onclick|javascript:)).*$/i'
            ],
        ]);


        try {
            $appointmentdate = Carbon::parse($request->appointmentdate)->format('Y-m-d');
        } catch (Exception $e) {
            $appointmentdate = Carbon::createFromFormat('d-m-Y', $request->appointmentdate)->format('Y-m-d');
        }


        // Save to DB
        $appointment = Appointment::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'choosedoctor' => $request->choosedoctor,
            'selectdepartment' => $request->selectdepartment,
            'appointmentdate' => $appointmentdate,
            'message' => $request->message,
            'ip' => $request->ip(),
        ]);

        // Send confirmation email (synchronous)
        try {
            Mail::to($appointment->email)->send(new AppointmentConfirmation($appointment));
        } catch (Exception $e) {
            Log::error('Appointment email failed: ' . $e->getMessage());
            // optionally set a flash message for email failure
            return back()->with('success', 'Your appointment has been submitted. Will get back to you soon');
        }

        return back()->with('success', 'Your appointment has been submitted successfully! Check your email for the details.');
    }
    // FOR VIEW APPOINTMENT LEADS
    public function view($id)
    {
        $appointments = Appointment::findOrFail($id);
        return response()->json($appointments);
    }


    // FOR UPDATE AND EDIT
    public function update(Request $request)
    {
        $appointments = Appointment::find($request->id);
        $appointments->fullname = $request->fullname;
        $appointments->email = $request->email;
        $appointments->phone = $request->phone;
        $appointments->choosedoctor = $request->choosedoctor;
        $appointments->selectdepartment = $request->selectdepartment;
        $appointments->appointmentdate = $request->appointmentdate;
        $appointments->message = $request->message;

        $appointments->save();
        return response()->json(['success' => true]);
    }
    public function delete($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Appointment::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }



}
