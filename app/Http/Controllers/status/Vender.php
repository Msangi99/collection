<?php

namespace App\Http\Controllers\status;

use App\Http\Controllers\Controller;
use App\Models\VenderBalance;
use Illuminate\Http\Request;

class Vender extends Controller
{
    const VENDER_SHARE = 0.1; // 10% share for the vendor  

    public function total($amount)
    {
        $venderShare = $amount * self::VENDER_SHARE;
        return $venderShare;
    }
}
