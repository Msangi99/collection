<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TestController extends Controller
{
    /**
     * Send WhatsApp message using Twilio WhatsApp Business API
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendWhatsAppMessage(Request $request)
    {
        $request->validate([
            'to' => 'required|string', // recipient WhatsApp number in format 'whatsapp:+1234567890'
            'message' => 'required|string',
        ]);

        $sid = env('');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_WHATSAPP_FROM'); // e.g. 'whatsapp:+14155238886'

        if (!$sid || !$token || !$from) {
            return response()->json(['error' => 'Twilio credentials are not set in environment variables.'], 500);
        }

        $client = new Client($sid, $token);

        try {
            $message = $client->messages->create(
                $request->input('to'), // to
                [
                    'from' => $from,
                    'body' => $request->input('message'),
                ]
            );

            return response()->json([
                'success' => true,
                'message_sid' => $message->sid,
                'status' => $message->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
