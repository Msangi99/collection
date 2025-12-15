<?php

namespace App\Http\Controllers;

use App\Models\AdminWallet;
use App\Models\Bima;
use App\Models\Booking;
use App\Models\bus;
use App\Models\PaymentFees;
use App\Models\Roundtrip;
use App\Models\SystemBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\FunctionsController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\VenderWalletController;

class ClickPesaController extends Controller
{
    // ClickPesa API Configuration
    private $apiKey;
    private $apiSecret;
    private $endpoint;
    private $callbackUrl;

    public function __construct()
    {
        $this->apiKey = env('CLICKPESA_API_KEY'); // Your ClickPesa API Key
        $this->apiSecret = env('CLICKPESA_API_SECRET'); // Your ClickPesa API Secret
        $this->endpoint = env('CLICKPESA_ENDPOINT', 'https://api.clickpesa.com/third-parties/payments/initiate-ussd-push-request');
        $this->callbackUrl = route('clickpesa.callback');
    }

    /**
     * Initiate ClickPesa payment
     */
    public function initiatePayment($amount, $first_name, $last_name, $phone, $email, $order_id = null)
    {
        // Prepare order details
        $orderDetails = [
            'amount' => $amount,
            'order_id' => $order_id ?? 'ORD-' . now()->timestamp,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email,
            'redirect_url' => route('clickpesa.callback'),
            'cancel_url' => route('clickpesa.cancel'),
        ];

        // Create transaction with ClickPesa
        $checkoutResponse = $this->createCheckoutSession($orderDetails);

        // Check if response is a string (error) or object (success)
        if (is_string($checkoutResponse)) {
            // Handle error case
            Log::error('ClickPesa Checkout Creation Failed', [
                'order_id' => $orderDetails['order_id'],
                'error' => $checkoutResponse,
            ]);

            return back()->with('error', 'ClickPesa Payment Failed: ' . $checkoutResponse);
        }

        // Check if we have a valid response with checkout URL
        // ClickPesa USSD-PUSH doesn't return a URL, it sends payment request to phone
        // Response includes: id, status, channel, orderReference, etc.
        $checkoutUrl = null;
        
        // For USSD-PUSH, we check if the request was successfully initiated
        if ($checkoutResponse && isset($checkoutResponse->id) && isset($checkoutResponse->status)) {
            $transactionId = (string) $checkoutResponse->id;
            $status = (string) $checkoutResponse->status;
            $orderRef = isset($checkoutResponse->orderReference) 
                ? (string) $checkoutResponse->orderReference 
                : $orderDetails['order_id'];

            // Log successful USSD-PUSH initiation
            Log::info('ClickPesa USSD-PUSH Initiated Successfully', [
                'order_id' => $orderDetails['order_id'],
                'transaction_id' => $transactionId,
                'status' => $status,
                'amount' => $orderDetails['amount']
            ]);

            // Redirect to a waiting page where user confirms payment on their phone
            return view('clickpesa.payment_waiting', [
                'transaction_id' => $transactionId,
                'order_id' => $orderRef,
                'amount' => $orderDetails['amount'],
                'status' => $status,
                'message' => 'Payment request sent to your phone. Please check your mobile device and enter your PIN to complete the payment.'
            ]);
        } else {
            $errorMessage = isset($checkoutResponse->message)
                ? (string) $checkoutResponse->message
                : "Unknown error creating USSD-PUSH request";

            Log::error('ClickPesa USSD-PUSH Request Failed', [
                'order_id' => $orderDetails['order_id'],
                'error' => $errorMessage,
                'response' => $checkoutResponse,
                'response_keys' => $checkoutResponse ? array_keys((array)$checkoutResponse) : []
            ]);

            return back()->with('error', $errorMessage);
        }
    }

    public function VenderinitiatePayment($amount, $first_name, $last_name, $phone, $email, $order_id = null)
    {
        // Prepare order details
        $orderDetails = [
            'amount' => $amount,
            'order_id' => $order_id ?? 'ORD-' . now()->timestamp,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
            'email' => $email,
            'redirect_url' => route('clickpesa.callback'),
            'cancel_url' => route('clickpesa.cancel'),
        ];

        // Create transaction with ClickPesa
        $checkoutResponse = $this->createCheckoutSession($orderDetails);

        // Check if response is a string (error) or object (success)
        if (is_string($checkoutResponse)) {
            Log::error('ClickPesa Checkout Creation Failed', [
                'order_id' => $orderDetails['order_id'],
                'error' => $checkoutResponse,
            ]);

            return back()->withErrors(['clickpesa_error' => $checkoutResponse]);
        }

        if ($checkoutResponse && isset($checkoutResponse->checkout_url)) {
            $checkoutUrl = (string) $checkoutResponse->checkout_url;
            $reference = (string) $checkoutResponse->reference;

            Log::info('ClickPesa Checkout Created Successfully (Vendor)', [
                'order_id' => $orderDetails['order_id'],
                'reference' => $reference,
                'amount' => $orderDetails['amount']
            ]);

            Session::put('vender', 'vender');

            // Redirect to ClickPesa checkout page
            return redirect()->away($checkoutUrl);
        } else {
            $errorMessage = isset($checkoutResponse->message)
                ? (string) $checkoutResponse->message
                : "Unknown error creating checkout session";

            Log::error('ClickPesa Checkout Creation Failed', [
                'order_id' => $orderDetails['order_id'],
                'error' => $errorMessage,
                'response' => $checkoutResponse
            ]);

            return back()->withErrors(['clickpesa_error' => $errorMessage]);
        }
    }


    /**
     * Handle ClickPesa callback (success and failure)
     */
    public function handleCallback(Request $request)
    {
        $reference = $request->get('reference');
        $status = $request->get('status');

        // Handle cancellation
        if ($status === 'cancelled' || $status === 'failed') {
            Log::info('ClickPesa Transaction Canceled/Failed', [
                'reference' => $reference,
                'status' => $status,
                'query_params' => $request->all()
            ]);

            return view('clickpesa.cancel', [
                'reference' => $reference,
                'status' => $status,
                'message' => 'Transaction was ' . $status
            ]);
        }

        // Verify transaction if reference is present
        if ($reference) {
            $verifyResponse = $this->verifyTransaction($reference);

            if ($verifyResponse && (string) $verifyResponse->status == 'success') {
                Log::info('ClickPesa Payment Verification Successful', [
                    'reference' => $reference,
                    'response' => $verifyResponse
                ]);

                $vender = Session::get('vender') ?? '';

                $booking1 = session()->get('booking1');
                $booking2 = session()->get('booking2');

                // Handle round trip bookings
                if (!is_null($booking1) && !is_null($booking2)) {
                    $round = new RoundpaymentController();
                    $code1 = $booking1->booking_code ?? 'N/A';
                    $code2 = $booking2->booking_code ?? 'N/A';

                    try {
                        $data1 = $round->roundtrip($reference, $reference, $verifyResponse, $code1);
                        $data2 = $round->roundtrip($reference, $reference, $verifyResponse, $code2);

                        // Clear round trip session data after successful processing
                        session()->forget(['booking1', 'booking2', 'is_round', 'booking_form']);

                        $red = new RedirectController();
                        return $red->showRoundTripBookingStatus($data1, $data2);
                    } catch (\Exception $e) {
                        Log::error('Round trip payment processing failed', [
                            'error' => $e->getMessage(),
                            'booking1_code' => $code1,
                            'booking2_code' => $code2,
                            'reference' => $reference
                        ]);

                        session()->forget(['booking1', 'booking2', 'is_round', 'booking_form']);

                        return view('clickpesa.error', [
                            'message' => 'Failed to process round trip payment: ' . $e->getMessage(),
                            'reference' => $reference
                        ]);
                    }

                } else if (!$vender) {
                    return $this->processSuccessfulPayment($reference, $request->merchant_reference, $verifyResponse);
                } else {
                    Session::forget('vender');
                    $venderclass = new VenderWalletController();
                    return $venderclass->returned();
                }

            } else {
                $errorMessage = isset($verifyResponse->message)
                    ? (string) $verifyResponse->message
                    : (is_string($verifyResponse) ? $verifyResponse : "Unknown verification error");

                Log::error('ClickPesa Payment Verification Failed', [
                    'reference' => $reference,
                    'error' => $errorMessage,
                    'response' => $verifyResponse
                ]);

                return [
                    'reference' => $reference,
                    'errorMessage' => $errorMessage,
                    'response' => $verifyResponse
                ];
            }
        } else {
            Log::warning('No Reference in ClickPesa Callback', [
                'query_params' => $request->all()
            ]);

            return [
                'errorMessage' => 'No transaction reference provided in callback',
                'queryParams' => $request->all()
            ];
        }
    }

    /**
     * Handle cancellation specifically
     */
    public function handleCancel(Request $request)
    {
        $reference = $request->get('reference');
        $status = $request->get('status');

        Log::info('ClickPesa Transaction Canceled (Direct)', [
            'reference' => $reference,
            'status' => $status,
            'query_params' => $request->all()
        ]);

        return view('clickpesa.cancel', [
            'reference' => $reference,
            'status' => $status,
            'message' => 'Transaction was canceled'
        ]);
    }

    /**
     * Get ClickPesa access token
     */
    private function getAccessToken()
    {
        $tokenEndpoint = 'https://api.clickpesa.com/third-parties/generate-token';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'api-key: ' . $this->apiKey,
            'client-id: ' . $this->apiSecret  // client-id is the API Secret
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            Log::error('ClickPesa Token Error', [
                'http_code' => $httpCode,
                'response' => $response
            ]);
            return null;
        }

        $jsonResponse = json_decode($response);
        
        // Response format: {"success":true,"token":"Bearer eyJ..."}
        if (isset($jsonResponse->success) && $jsonResponse->success && isset($jsonResponse->token)) {
            // Token already includes "Bearer " prefix, so extract just the token part
            $token = $jsonResponse->token;
            if (strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7); // Remove "Bearer " prefix
            }
            return $token;
        }
        
        Log::error('ClickPesa Token Response Invalid', [
            'response' => $jsonResponse
        ]);
        return null;
    }

    /**
     * Create ClickPesa USSD-PUSH request
     */
    private function createCheckoutSession($orderDetails)
    {
        // Get access token first
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return "Failed to obtain access token from ClickPesa";
        }

        // Format phone number (remove + and ensure it starts with country code)
        $phoneNumber = $orderDetails['phone'];
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); // Remove non-digits
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '255' . substr($phoneNumber, 1); // Convert 0... to 255...
        } elseif (substr($phoneNumber, 0, 3) !== '255') {
            $phoneNumber = '255' . $phoneNumber; // Add country code if missing
        }

        // ClickPesa requires alphanumeric-only order reference (no hyphens or special chars)
        $orderReference = preg_replace('/[^a-zA-Z0-9]/', '', $orderDetails['order_id']);

        // ClickPesa USSD-PUSH API format
        $payload = [
            'amount' => (string) $orderDetails['amount'],
            'currency' => 'TZS',
            'orderReference' => $orderReference,
            'phoneNumber' => $phoneNumber,
        ];

        $jsonPayload = json_encode($payload);

        Log::debug('ClickPesa USSD-PUSH Request', [
            'order_id' => $orderDetails['order_id'],
            'endpoint' => $this->endpoint,
            'payload' => $payload,
            'phone_formatted' => $phoneNumber,
            'token_preview' => substr($accessToken, 0, 20) . '...'
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);

        if ($httpCode != 200 && $httpCode != 201) {
            Log::error('ClickPesa Create Checkout HTTP Error', [
                'http_code' => $httpCode,
                'order_id' => $orderDetails['order_id'],
                'response' => $response,
                'curl_error' => $curlError,
                'endpoint' => $this->endpoint,
                'curl_info' => $curlInfo
            ]);
            return "HTTP Error: $httpCode - Failed to connect to ClickPesa API";
        }

        $jsonResponse = json_decode($response);
        if ($jsonResponse === null) {
            Log::error('ClickPesa Create Checkout JSON Parse Error', [
                'response' => $response,
                'order_id' => $orderDetails['order_id']
            ]);
            return "Error parsing JSON response: $response";
        }

        Log::debug('ClickPesa Create Checkout Response', [
            'order_id' => $orderDetails['order_id'],
            'response' => $jsonResponse
        ]);

        return $jsonResponse;
    }

    /**
     * Verify ClickPesa transaction
     */
    private function verifyTransaction($reference)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/transaction/' . $reference);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            Log::error('ClickPesa Verify Transaction HTTP Error', [
                'http_code' => $httpCode,
                'reference' => $reference
            ]);
            return "HTTP Error: $httpCode - Failed to connect to ClickPesa API";
        }

        $jsonResponse = json_decode($response);
        if ($jsonResponse === null) {
            Log::error('ClickPesa Verify Transaction JSON Parse Error', [
                'response' => $response,
                'reference' => $reference
            ]);
            return "Error parsing JSON response: $response";
        }

        Log::debug('ClickPesa Verify Transaction Response', [
            'reference' => $reference,
            'response' => $jsonResponse
        ]);

        return $jsonResponse;
    }

    private function processSuccessfulPayment($transToken, $companyRef, $verifyResponse)
    {
        // Retrieve booking using CompanyRef (which should be booking_code)
        $booking1 = session()->get('booking1');
        $booking2 = session()->get('booking2');
        if (!is_null($booking1) && !is_null($booking2)) {
            $round = new RoundpaymentController();
            $code1 = $booking1->booking_code ?? 'N/A';
            $code2 = $booking2->booking_code ?? 'N/A';

            try {
                $data1 = $round->roundtrip($transToken, $transToken, $verifyResponse, $code1);
                $data2 = $round->roundtrip($transToken, $transToken, $verifyResponse, $code2);

                // Clear round trip session data after successful processing
                session()->forget(['booking1', 'booking2', 'is_round', 'booking_form']);

                $red = new RedirectController();
                return $red->showRoundTripBookingStatus($data1, $data2);
            } catch (\Exception $e) {
                Log::error('Round trip payment processing failed in processSuccessfulPayment', [
                    'error' => $e->getMessage(),
                    'booking1_code' => $code1,
                    'booking2_code' => $code2,
                    'transaction_token' => $transToken
                ]);

                // Clear session data on error
                session()->forget(['booking1', 'booking2', 'is_round', 'booking_form']);

                return view('clickpesa.error', [
                    'message' => 'Failed to process round trip payment: ' . $e->getMessage(),
                    'reference' => $transToken
                ]);
            }
        }

        $code = session('booking')->booking_code;
        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            Log::error('Booking not found', ['transaction_ref_id' => $companyRef]);
            return [
                'errorMessage' => 'Booking not found',
                'reference' => $transToken
            ];
        }

        // Check for duplicate processing
        if ($booking->payment_status !== 'Unpaid') {
            Log::warning('Booking already processed', ['transaction_ref_id' => $companyRef]);
            return view('clickpesa.success', [
                'message' => 'Payment already processed',
                'booking' => $booking
            ]);
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Initialize admin wallet
            $adminWallet = AdminWallet::find(1);

            if (!$adminWallet) {
                throw new \Exception('Admin wallet not found');
            }

            // Define VAT function
            $vat = function ($amount, $state) use ($booking, $adminWallet) {
                $vatRate = 18; // VAT percentage
                $vatFactor = 1 + ($vatRate / 100);
                $vatAmount = $amount - ($amount / $vatFactor);

                if ($state == 'fee') {
                    $booking->fee_vat = $vatAmount;
                } elseif ($state == 'service') {
                    $booking->service_vat = $vatAmount;
                } else {
                    return $amount; // Fallback in case state is invalid
                }

                $adminWallet->increment('vat', $vatAmount);
                return $amount - $vatAmount;
            };

            // Define vendor function
            $vender = function ($amount, $state) use ($booking) {
                if ($booking->vender_id > 0 && $booking->vender && $booking->vender->VenderAccount) {
                    $vendorPercentage = $booking->vender->VenderAccount->percentage;
                    $vendorShare = $amount * ($vendorPercentage / 100);

                    $booking->vender->VenderBalances->increment('amount', $vendorShare);

                    if ($state === 'fee') {
                        $booking->vender_fee = $vendorShare;
                    } elseif ($state === 'service') {
                        $booking->vender_service = $vendorShare;
                    }

                    return $amount - $vendorShare;
                }

                return $amount;
            };

            // Calculate shares
            $bimaAmount = $booking->bima_amount ?? 0;
            $fees = $booking->amount - $booking->busFee - $bimaAmount;
            $busOwnerAmount = $booking->busFee + Session::get('cancel');

            if (auth()->user()->role == 'customer') {
                if (auth()->user()->temp_wallets != null) {
                    $busOwnerAmount = $busOwnerAmount + auth()->user()->temp_wallets->amount;
                    auth()->user()->temp_wallets->amount = 0;
                    auth()->user()->temp_wallets->save();
                }
            }

            // Calculate system shares
            $bus = Bus::with(['busname', 'route', 'campany.balance'])->find($booking->bus_id);
            $companyPercentage = $bus->campany->percentage;
            $systemShares = $busOwnerAmount * ($companyPercentage / 100);
            $busOwnerAmount -= $systemShares;

            // Apply vendor share calculations
            $systemBalanceAmount = $systemShares;
            $paymentFeesAmount = $fees;

            if ($booking->vender_id > 0) {
                $systemBalanceAmount = $vender($systemShares, 'fee');
                $paymentFeesAmount = $vender($fees, 'service');
            }

            $bookingFee = $systemBalanceAmount;
            $bookingService = $paymentFeesAmount;

            // Update Bima if applicable
            if ($bimaAmount > 0) {
                Bima::create([
                    'booking_id' => $booking->id,
                    'start_date' => $booking->travel_date,
                    'end_date' => $booking->insuranceDate,
                    'amount' => $bimaAmount,
                    'bima_vat' => $bimaAmount * (18 / 118),
                ]);
                $adminWallet->increment('balance', $bimaAmount);
            }

            // Update booking
            $booking->update([
                'payment_status' => 'Paid',
                'trans_status' => 'success',
                'trans_token' => $transToken,
                'fee' => $bookingFee,
                'service' => $bookingService,
                'amount' => $busOwnerAmount, // Store bus owner share separately
                'payment_method' => 'clickpesa',
            ]);

            // Update SystemBalance
            SystemBalance::create([
                'campany_id' => $bus->campany->id,
                'balance' => $systemBalanceAmount,
            ]);

            // Increment admin wallet for system balance
            $adminWallet->increment('balance', $systemBalanceAmount);

            // Update PaymentFees
            PaymentFees::create([
                'campany_id' => $bus->campany->id,
                'amount' => $paymentFeesAmount,
                'booking_id' => $booking->id,
            ]);

            // Increment admin wallet for payment fees
            $adminWallet->increment('balance', $paymentFeesAmount);

            // Update company balance
            $bus->campany->balance->increment('amount', $busOwnerAmount);

            DB::commit();

            Log::info('ClickPesa Payment processed successfully', [
                'booking_id' => $booking->id,
                'company_id' => $bus->campany->id,
                'company_balance_increment' => $busOwnerAmount,
                'system_balance' => $systemBalanceAmount,
                'payment_fees' => $paymentFeesAmount,
                'vendor_fee_share' => $booking->vender_fee ?? 0,
                'vendor_service_share' => $booking->vender_service ?? 0,
                'bima_amount' => $bimaAmount,
            ]);

            Session::forget('booking');
            Session::forget('cancel');
            $key = new FunctionsController();
            $key->delete_key($booking);

            $url = new RedirectController();
            return $url->_redirect($booking->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update records in ClickPesa payment', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'transaction_token' => $transToken
            ]);

            $url = new RedirectController();
            return $url->_redirect($booking->id);
        }
    }
}
