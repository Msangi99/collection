<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\PDOController;
use App\Http\Controllers\TigosecureController;

class ResaveController extends Controller
{
    public function byMix(Request $request)
    {
        $id = $request->booking_id;
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $postedData = [
            'account' => $booking->phone,
            'countryCode' => '255', // Assuming Tanzania
            'country' => 'TZA', // Assuming Tanzania
            'firstName' => $booking->first_name,
            'lastName' => $booking->last_name,
            'email' => $booking->email,
            'amount' => $booking->amount,
            'currency' => 'TZS', // Assuming Tanzanian Shilling
        ];

        try {
            $tigoSecureController = new TigosecureController();
            return $tigoSecureController->payment($postedData);
            //return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function byPdo(Request $request)
    {
        $id = $request->booking_id;
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $pdoController = new PDOController();
        return $pdoController->initiatePayment(
            $booking->amount,
            $booking->first_name,
            $booking->last_name,
            $booking->phone,
            $booking->email,
            $booking->booking_code
        );
    }
}
