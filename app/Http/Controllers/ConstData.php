<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConstData extends Controller
{
    public function cancel_logic($booking_id){
        $booking = Booking::find($booking_id);
        if($this->carbon(72) < $booking->travel_date )
        {
            return $booking->busFee;
        }else if($this->carbon(48) < $booking->travel_date && $this->carbon(72) > $booking->travel_date)
        {
            return $booking->busFee * 0.8;
        }else if($this->carbon(24) < $booking->travel_date && $this->carbon(48) > $booking->travel_date)
        {
            return $booking->busFee * 0.5;
        }else{
            return 0;
        }
    }

    public function refund_logic($booking_id){
        $booking = Booking::find($booking_id);
        if($this->carbon(48) >= $booking->travel_date)
        {
            return $booking->busFee;

        }else if($this->carbon(24) < $booking->travel_date && $this->carbon(48) > $booking->travel_date)
        {
            return $booking->busFee * 0.8;
        }else if($this->carbon(6) < $booking->travel_date && $this->carbon(24) > $booking->travel_date){
            return $booking->busFee * 0.5;
        }else{
            return 0;
        }
    }

    public function carbon($hours)
    {
        if( $hours < 0)
        {
            return Carbon::parse(now())->addHours($hours)->format('Y-m-d h:i:s');
        }else{
            return Carbon::parse(now())->format('Y-m-d h:i:s');
        }
    }


}
