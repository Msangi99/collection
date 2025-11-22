<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Session;
use \Carbon\Carbon;

class RebookController extends Controller
{
    public function rebook(Request $request)
    {
        $booking = Booking::find($request->input('order_id'));
        $now = Carbon::parse(now())->format('Y-m-d');
    
        if($booking->travel_date <= $now)
        {
            return back()->with('error', 'Your rebooking is out to date you cant rebook this booking');
        }

        Session::put('rebook', $booking);

        return redirect()->route('customer.mybooking.search')->with('warning', 'Make sure you finish your booking before logout.');
    }

    public function rebook_data($data)
    {

        //return $data;

        $bookingData = [  
            'bus_id' => $data['bus_id'],
            'route_id' => $data['route_id'],
            'pickup_point' => $data['pickup_point'],
            'dropping_point' => $data['dropping_point'],
            'travel_date' => $data['travel_date'],
            'seat' => $data['seats'],
            'payment_status' => 'Paid',
            'distance' => $data['route_distance']
        ];

        $rebook = session('rebook');
        $rebook->update($bookingData);
        
        Session::forget('rebook');

        return redirect()->route('customer.mybooking')->with('success', 'Your rebooking has been completed successfully');
    }
}
