<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use App\Models\Booking;
use App\Models\Roundtrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RedirectController extends Controller
{
    public function showBookingStatus($bookingId)
    {
        $booking = Booking::with('bus.route', 'campany.busOwnerAccount')
            ->where('id', $bookingId)
            ->orwhere('transaction_ref_id', $bookingId)
            ->first();

        if (!$booking) {
            abort(404, 'Booking not found.');
        }

        return view('bookings.status', compact('booking'));
    }
    public function _redirect($transactionRefId)
    {
        $data = Booking::with('bus.route', 'campany.busOwnerAccount')
            ->where('transaction_ref_id', $transactionRefId)
            ->orwhere('id', $transactionRefId)
            ->first();

        if ($data->payment_status == "Paid") {
            
            $sms = new SmsController();
            $data1 =  "Dear {$data->customer_name}, Karibu {$data->campany->name}. Utasafiri na basi namba {$data->bus->bus_number} Tarehe {$data->travel_date} kutoka {$data->pickup_point} kwenda {$data->dropping_point} muda wa kuondoka ni {$data->bus->route->route_start} tafadhali report kituoni mapema kwa safari.Namba ya kiti chako ni {$data->seat} na namba yako ya safari ni {$data->booking_code}. Kwa mawasiliano piga {$data->bus->conductor}. HIGHLINK ISGC inakutakia safari njema";
            $sms->sms_send($data->customer_phone, $data1);
            $data2 = "Dear conductor, Kiti {$data->seat} katika basi namba {$data->bus->bus_number} kimeuzwa kwa {$data->customer_name} kwa safari ya kutoka {$data->pickup_point} kwenda {$data->dropping_point} tarehe {$data->travel_date} namba ya safari yake ni {$data->booking_code} wasiliana naye kwa namba {$data->customer_phone} HIGHLINK ISGC inawatakia safari njema";
            $sms->sms_send($data->bus->conductor, $data2);

            if (!is_null($data->customer_email)) {
                Mail::to($data->customer_email)->send(new SendEmail($data1));
                Mail::to($data->customer_email)->send(new SendEmail($data2));
            }
                

            return view('payments.success', compact('data'));
        } else {
            return view('payments.failed', compact('data'));
        }
    }

    // public function _round($booking1, $booking2)
    // {
    //     $bookingone = Booking::with('bus.route', 'campany.busOwnerAccount')
    //         ->orwhere('id', $booking1)
    //         ->first();
    //     $bookingtwo = Booking::with('bus.route', 'campany.busOwnerAccount')
    //         ->orwhere('id', $booking2)
    //         ->first();

    //     return ['bookingone' => $bookingone, 'bookingtwo' => $bookingtwo];
    // }

    public function showRoundTripBookingStatus($booking1Id, $booking2Id)
    {
        //$bookings = $this->_round($booking1Id, $booking2Id);

        $bookingone = $booking1Id;
        $bookingtwo = $booking2Id;

        if (!$bookingone || !$bookingtwo) {
            abort(404, 'One or both bookings not found.');
        }

        return view('bookings.roundtrip_status', compact('bookingone', 'bookingtwo'));
    }
}
