<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Refund;
use App\Models\RefundPercentage;
use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class RefundController extends Controller
{
    public function get_booking(Request $request)
    {
        //return $request->all();
        // PhoneNumber
        // fullname
        // booking_id
        // check for payment number

        if(!$request->mobile_number && empty($request->bank_number))
        {
            return back()->with('error', 'Please enter a valid mobile number or payment number.');
        }
        
        // check booking is available for next 24hrs
        $booking = Booking::where('id', $request->booking_id)
            ->where('payment_status', 'Paid')
            //->where('travel_date', '>', now()->addHours(23))
            ->first(); // Retrieve the first matching booking

        if(!$booking)
        {
            return back()->with('error', 'Booking not available for refund or does not exist.');
        }

        if($booking->travel_date < (new ConstData())->carbon(6))
        {
            return back()->with('error', 'Booking not available for refund or does not exist.');
        }

        $amount = (new ConstData())->refund_logic($booking->id);
        $percentage = $booking->bus - $amount;

        // post request
        $data = Refund::create([
            'booking_code' => $booking->booking_code,
            'amount' => $amount, // Access busFee from the retrieved booking model
            'status' => 'Pending',
            'phone' => $request->mobile_number ?? $request->bank_number,
            'fullname' => $request->fullname,
        ]); 

        $booking->update([
            'payment_status' => 'refunded',
            //'refund_id' => $data->id,
        ]);

        RefundPercentage::create([
            'booking_code' => $booking->booking_code,
            'amount' => $percentage,
        ]);

        return back()->with('success','Refund request sent successfully');


    }
}
