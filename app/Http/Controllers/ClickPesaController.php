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
        // TODO: Move these to .env file for production
        $this->apiKey = env('CLICKPESA_API_KEY', 'test_api_key'); // Test API Key
        $this->apiSecret = env('CLICKPESA_API_SECRET', 'test_api_secret'); // Test API Secret
        $this->endpoint = env('CLICKPESA_ENDPOINT', 'https://api.clickpesa.com/v1'); // API endpoint
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

            return back()->withErrors(['clickpesa_error' => $checkoutResponse]);
        }

        // Check if we have a valid response with checkout URL
        if ($checkoutResponse && isset($checkoutResponse->checkout_url)) {
            $checkoutUrl = (string) $checkoutResponse->checkout_url;
            $reference = (string) $checkoutResponse->reference;

            // Log successful checkout creation
            Log::info('ClickPesa Checkout Created Successfully', [
                'order_id' => $orderDetails['order_id'],
                'reference' => $reference,
                'amount' => $orderDetails['amount']
            ]);

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
     * Create ClickPesa checkout session
     */
    private function createCheckoutSession($orderDetails)
    {
        $payload = [
            'merchant_reference' => $orderDetails['order_id'],
            'amount' => (float) $orderDetails['amount'],
            'currency' => 'TZS',
            'customer' => [
                'first_name' => $orderDetails['first_name'],
                'last_name' => $orderDetails['last_name'],
                'email' => $orderDetails['email'],
                'phone' => $orderDetails['phone'],
            ],
            'redirect_url' => $orderDetails['redirect_url'],
            'cancel_url' => $orderDetails['cancel_url'],
        ];

        $jsonPayload = json_encode($payload);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/checkout');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200 && $httpCode != 201) {
            Log::error('ClickPesa Create Checkout HTTP Error', [
                'http_code' => $httpCode,
                'order_id' => $orderDetails['order_id'],
                'response' => $response
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
