<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function ConvertToUsd($tzs)
    {
        $usd = app('usdToTzs') ?? 2500;
        return $usd * $tzs;
    }
}
