<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sms_send($destination,$message) {
        $username="HIGHLINK";
        $password="ifxcs1ud";
        $senderid="HIGHLINK";
        $message=urlencode($message);
        $fetch=file_get_contents("https://www.sms.co.tz/api.php?do=sms&username={$username}&password={$password}&senderid={$senderid}&dest={$destination}&msg={$message}");
        //$fetch=file_get_contents("https://www.sms.co.tz/api.php?do=sms&api_key=<APIKEY>&senderid={$senderid}&dest={$destination}&msg={$message}");
        if ($fetch) {
           $result=explode(",",$fetch);
           $result_status=$result[0]; //OK or ERR
           $result_status_detail=$result[1];
           if ($result_status=="OK") {
              $result_id=$result[2]; //Save this ID somewhere to be able to lookup DLR
              return $result_id;
           } else {
              echo $result_status_detail; //INVALIDACCT or INVALIDNUMBER or NOBALANACE or CHECKINPUT
              return false;
           }
        }
    }
}
