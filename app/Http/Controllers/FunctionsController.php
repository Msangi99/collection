<?php

namespace App\Http\Controllers;

use App\Models\TempWallet;
use Illuminate\Http\Request;

class FunctionsController extends Controller
{
    public function delete_key($booking)
    {
        if ($booking->cancel_key != null) {
            $data = TempWallet::where('user_key', $booking->cancel_key)->first();
            if ($data) {
                $data->delete();
                return true;
            } else {
                return true;
            }

        }else{
            return true;
        }
    }
}
