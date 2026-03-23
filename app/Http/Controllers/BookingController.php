<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Booking;

class BookingController extends Controller
{
    // TO SEARCH 

    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $bookings = \App\Models\Booking::where('name', 'LIKE', "%$keyword%")
           
            ->orWhere('mobile', 'LIKE', "%$keyword%")
            ->get();

            return response()->json([
                "status"=>true,
                "data"=>$bookings
            ]);

        // return view('admin.pages.partials.booking-rows', compact('bookings'));
    }
    // TO FETCH ALL THE DATA OF BOOKING FORM
    public function index()
    {
        $bookings = Booking::orderBy('id', 'desc')->get();
        return view('admin.pages.admin-booking', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'regex:/^[a-zA-Z\s\.\-]{2,255}$/'
            ],
            'mobile' => [
                'required',
                'regex:/^\+91[6-9]\d{9}$/'
            ],
            'g-recaptcha-response' => 'required',
        ]);

        // Save to database
        Booking::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'source' => $request->source,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Our health advisor will contact you shortly.'
        ]);
    }
    // to delete

    public function delete($id)
    {
        Booking::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteSelected(Request $request)
    {
        if (!$request->ids || count($request->ids) == 0) {
            return response()->json(['error' => true, 'message' => 'No IDs received']);
        }

        Booking::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

}
