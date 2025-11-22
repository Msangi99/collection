<?php

if (!function_exists('convert_to_usd')) {
    function convert_money($tzs)
    {
        $currecy = session('currency');
        $usd = app('usdToTzs') ?? 2500;

        if($currecy == 'Usd'){
             return  number_format($tzs / $usd, 2);
        }else{
            return number_format($tzs, 2);
        }
    } 

    function convert_to_tzs($money)
    {
        $usd = app('usdToTzs') ?? 2500;

        return  number_format($money * $usd, 2);
    }
}