<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class QRcodeScannerController extends Controller
{
    public function index()
    {
        return view('scanner.qr_scanner');
       //return Booking::with('bus.campany','route.schedule')->where('booking_code', 'ER42839017')->first();
    }

    public function scan(Request $request)
    {
        
        $qrData = $request->input('qr_data');

        $data = Booking::with('bus.campany','route.schedule')->where('booking_code', $qrData)->first();
        // Process $qrData (e.g., save to database, redirect, etc.)
        return response()->json(['message' => 'QR code scanned', 'data' => $data]);
    }
}
