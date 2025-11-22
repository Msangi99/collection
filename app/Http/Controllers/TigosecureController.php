<?php

namespace App\Http\Controllers;

use App\Models\route;
use Exception;

class TigosecureController extends Controller
{
    private $client_id;
    private $client_secret;

    public function __construct()
    {
        $this->client_id = "FohxYSyTZmAIqMxXqJKUWhZWYx7021KU"; // Your Key
        $this->client_secret = "FfEYvf8juaRfsER3"; // Your Secret
    }

    private function generateAccessToken()
    {
        $url = "https://secure.tigo.com/v1/oauth/generate/accesstoken?grant_type=client_credentials";

        $headers = ["Content-Type: application/x-www-form-urlencoded"];

        $data = [
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception("Curl error: " . curl_error($ch));
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result["accessToken"])) {
            return $result["accessToken"];
        }

        throw new Exception("Failed to generate access token: " . json_encode($result));
    }

    private function generateRandomId()
    {
        $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        $randomString = "";
        for ($i = 0; $i < 8; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        $randomNumber = rand(100, 999);

        return $randomString . "-" . $randomNumber;
    }

    public function payment($postedData)
    {
        try {
            $accessToken = $this->generateAccessToken();

            $transactionRefId = $this->generateRandomId();

            $paymentDetails = [
                "MasterMerchant" => [
                    "account" => "25565311977",
                    "pin" => "1977",
                    "id" => "HIGHLINK ISGC"
                ],
                "Subscriber" => [
                    "account" => $postedData['account'],
                    "countryCode" => $postedData['countryCode'],
                    "country" => $postedData['country'],
                    "firstName" => $postedData['firstName'],
                    "lastName" => $postedData['lastName'],
                    "emailId" => $postedData['email']
                ],
                "redirectUri" => route('tigo.redirect', ['transactionRefId' => $transactionRefId]),
                "callbackUri" => route('tigo.callback'),
                "language" => "eng",
                "terminalId" => "",
                "originPayment" => [
                    "amount" => $postedData['amount'],
                    "currencyCode" => $postedData['currency'],
                    "tax" => "0.00",
                    "fee" => "0.00"
                ],
                "exchangeRate" => "1",
                "LocalPayment" => [
                    "amount" => $postedData['amount'],
                    "currencyCode" => $postedData['currency']
                ],
                "transactionRefId" => $transactionRefId
            ];

            $url = "https://secure.tigo.com/v1/tigo/payment-auth/authorize";

            $headers = [
                "Content-Type: application/json",
                "accessToken: " . $accessToken,
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentDetails));

            $response = curl_exec($ch);

            if ($response === false) {
                throw new Exception("Curl error: " . curl_error($ch));
            }

            curl_close($ch);

            $result = json_decode($response, true);

            if (isset($result["redirectUrl"])) {
                return [
                    'transactionRefId' => $transactionRefId,
                    'redirectUrl' => $result["redirectUrl"]
                ];
            }

            throw new Exception("Payment authorization failed: " . json_encode($result));
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
    
}
?>