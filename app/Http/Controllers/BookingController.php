<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BimaController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\status\Fees;
use App\Http\Controllers\status\Vender;
use App\Http\Controllers\TigosecureController;
use App\Mail\SendEmail;
use App\Models\AdminWallet;
use App\Models\Bima;
use App\Models\Booking;
use App\Models\bus;
use App\Models\campany;
use App\Models\City;
use App\Models\Discount;
use App\Models\PaymentFees;
use App\Models\route;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\SystemBalance;
use App\Models\User;
use App\Models\VenderBalance;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Milon\Barcode\DNS2D;

class BookingController extends Controller
{
    public function booking_info(Request $request)
    {
        $data = $request->data;

        // Check if data is numeric and starts with 0
        if (is_numeric($data) && str_starts_with($data, '0')) {
            $data = '255' . ltrim($data, '0');
        }

        // Check if this is an email address
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            // Check if email exists in bookings
            $bookingExists = Booking::where('customer_email', $data)->exists();
            
            if ($bookingExists) {
                // Generate verification code (we'll store it in session since user might not have account)
                $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = now()->addMinutes(15);
                
                // Store verification data in session
                $request->session()->put('booking_verification_email', $data);
                $request->session()->put('booking_verification_code', $verificationCode);
                $request->session()->put('booking_verification_expires_at', $expiresAt);

                try {
                    // Send verification email (create a simple user object for the email)
                    $tempUser = (object) ['email' => $data, 'name' => 'Customer'];
                    Mail::to($data)->send(new \App\Mail\EmailVerification($tempUser, $verificationCode));
                } catch (\Exception $e) {
                    Log::error('Failed to send verification email: ' . $e->getMessage());
                }

                return redirect()->route('booking.verification.show')
                    ->with('email', $data)
                    ->with('status', 'Please verify your email address to view your bookings. A verification code has been sent to your email.');
            } else {
                return back()->withErrors(['data' => 'No bookings found for this email address.'])->withInput();
            }
        } else {
            // For phone numbers, show bookings directly (no verification required)
            $bookings = Booking::with(['campany', 'route_name', 'user', 'bus.route', 'vender', 'campany.busOwnerAccount'])
                ->where('customer_phone', $data)
                ->get();
            
            return view('booking_info', compact('bookings'));
        }
    }

    /**
     * Show booking verification form
     */
    public function showBookingVerificationForm()
    {
        return view('auth.booking-verification');
    }

    /**
     * Verify email for booking access
     */
    public function verifyBookingEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        // Get verification data from session
        $email = $request->session()->get('booking_verification_email');
        $storedCode = $request->session()->get('booking_verification_code');
        $expiresAt = $request->session()->get('booking_verification_expires_at');
        
        if (!$email || !$storedCode || !$expiresAt) {
            return redirect()->route('info')->withErrors(['email' => 'Verification session expired. Please search again.']);
        }

        // Check if verification code is valid and not expired
        if ($request->verification_code !== $storedCode) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.'])->withInput();
        }

        if (now()->isAfter($expiresAt)) {
            return back()->withErrors(['verification_code' => 'Verification code has expired. Please request a new one.'])->withInput();
        }

        // Clear verification session data
        $request->session()->forget(['booking_verification_email', 'booking_verification_code', 'booking_verification_expires_at']);

        // Get bookings for the verified email
        $bookings = Booking::with(['campany', 'route_name', 'user', 'bus.route', 'vender', 'campany.busOwnerAccount'])
            ->where('customer_email', $email)
            ->get();

        return view('booking_info', compact('bookings'))
            ->with('success', 'Email verified successfully. Here are your bookings.');
    }

    /**
     * Resend verification code for booking access
     */
    public function resendBookingVerificationCode(Request $request)
    {
        // Get email from session
        $email = $request->session()->get('booking_verification_email');
        
        if (!$email) {
            return redirect()->route('info')->withErrors(['email' => 'Verification session expired. Please search again.']);
        }

        // Generate new verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);
        
        // Update session with new verification data
        $request->session()->put('booking_verification_code', $verificationCode);
        $request->session()->put('booking_verification_expires_at', $expiresAt);

        try {
            // Send verification email
            $tempUser = (object) ['email' => $email, 'name' => 'Customer'];
            Mail::to($email)->send(new \App\Mail\EmailVerification($tempUser, $verificationCode));
            return back()
                ->with('email', $email)
                ->with('status', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send verification email. Please try again.'])->withInput();
        }
    }

    public function form()
    {
        return view('form');
    }

    public function choose()
    {
        return view('choose');
    }

    public function booking(Request $request)
    {
        return view('booking');
    }

    public function search(Request $request)
    {

        $bus = campany::with('bus.schedules')
            ->where('id', $request->campany_id)
            ->get();
        return view('booking', compact('bus'));
        //return $bus;
    }

    public function booking_form($id, $from, $to)
    {
        $date = session()->get('departure_date');
        $car = Bus::with([
            'busname',
            'route',
            'schedule' => function ($query) use ($from, $to) {
                $query->where('from', $from)->where('to', $to);
            },
            'route.points'
        ])->find($id);
        $time = [
            'start' => $car->route->route_start,
            'end' => $car->route->route_end,
        ];

        session()->put('time', $time);

        // Initialize a filtered points collection
        $filteredPoints = collect();

        //$filteredPoints = $car->route->points;

        if ($car->route->from == $car->schedule->from) {
            $filteredPoints = $car->route->points->filter(function ($point) use ($car) {
                return $point->state === 'no';
            });
        } else {
            $filteredPoints = $car->route->points->filter(function ($point) use ($car) {
                return $point->state === 'yes';
            });
        }

        // Add filtered points as a new attribute to the car object
        $car->filtered_points = $filteredPoints;

        return view('booking_form', compact('car'));
        //return $car;
    }

    public function get_form(Request $request)
    {
        //return $request->all();
        if ($request->route_distance < 1) {
            return back()->with('error', 'Calculate distance before continue');
        }
        $route = Route::find($request->route_id);
        $bus_info = [
            'bus_id' => $request->bus_id,
            'from' => $route->from,
            'to' => $route->to,
            'route_id' => $request->route_id,
            'pickup_point' => $request->pickup_point ?? $route->from,
            'dropping_point' => $request->dropping_point ?? $route->to,
            'travel_date' => session()->get('departure_date') ?? now()->format('Y-m-d'),
            'dropping_point_amount' => $request->dropping_point_amount ?? ($route ? $route->price : 0),
            'route_distance' => $request->route_distance ?? 0,
            'schedule_id' => $request->schedule_id,
        ];

        // Store in session
        session()->put('booking_form', $bus_info);
        //return session()->get('booking_form');
        // Redirect to seats route
        return redirect()->route('seates');
        //return session()->get('booking_form');
    }

    public function seates()
    {
        $booking_form = session()->get('booking_form');
        $bus_id = $booking_form['bus_id'];
        $travel_date = $booking_form['travel_date'];
        $price = $booking_form['dropping_point_amount'];

        //return $travel_date;

        $info = session()->get('booking_form');
        $car = Bus::with([
            'busname',
            'route',
            'schedule' => function ($query) use ($travel_date) {
                $query->where('schedule_date', $travel_date)
                    ->orwhere('schedule_date', '>', $travel_date);
            },
            'route.points'
        ])->find($bus_id);

        $car = Bus::with([
            'busname',
            'route',
            'schedule' => function ($query) use ($travel_date) {
                $query->where('schedule_date', $travel_date)
                    ->orwhere('schedule_date', '>', $travel_date);
            },
        ])->find($bus_id);

        // Fetch booked seats for the bus and travel date
        $booked_seats = Booking::where('bus_id', $bus_id)
            ->where('travel_date', $travel_date)
            ->where('payment_status', 'Paid')
            ->pluck('seat') // Get the 'seat' column (comma-separated seat numbers)
            ->flatMap(function ($seats) {
                return explode(',', $seats); // Split comma-separated seats into an array
            })
            ->unique() // Remove duplicates
            ->values()
            ->toArray();

        return view('seates', compact('price', 'booked_seats', 'car'));

        //return  $car;
    }

    public function get_seats(Request $request)
    {
        $seats = $request->selected_seats;
        $price = $request->total_amount;

        $bus_info = session()->get('booking_form', []);
        $bus_info['total_amount'] = $price;
        $bus_info['seats'] = $seats;

        session()->put('booking_form', $bus_info);


        return redirect()->route('pay');
    }

    public function payment()
    {
        $setting = Setting::first();
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }
        $price = session()->get('booking_form')['total_amount'];
        $seats = session()->get('booking_form')['seats'];
        $car = Bus::with([
            'busname',
            'route.via'
        ])->find(session()->get('booking_form')['bus_id']);
        $info = session()->get('booking_form');
        $time = session()->get('time');
        $date = session()->get('booking_form')['travel_date'];
        $fees = $setting->service + ($setting->service_percentage / 100 * (session()->get('booking_form')['total_amount'] * 100 / 118));

        $distance = session()->get('booking_form')['route_distance'] ?? 0;
        //return $info;
        return view('payment', compact('price', 'seats', 'info', 'car', 'time', 'date', 'fees', 'distance'));
    }

    public function payment_info(Request $request)
    {
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }

        $bus_info = session()->get('booking_form', []);
        $bus_info['customer_name'] = $request->customer;
        $bus_info['gender'] = $request->gender;
        $bus_info['age'] = $request->age;
        $bus_info['infant_child'] = $request->infant_child ?? 0;
        $bus_info['age_group'] = $request->age_group;
        $bus_info['category'] = $request->category;
        $bus_info['start'] = session()->get('time')['start'];
        $bus_info['end'] = session()->get('time')['end'];
        $bus_info['bima'] = $request->Insurance ?? 0;
        $bus_info['insuranceDate'] = $request->insuranceDate;
        $bus_info['discount'] = $request->discount ?? '';
        $bus_info['cancel_amount'] = $request->amount_cancel ?? 0;
        $bus_info['cancel_key'] = $request->key ?? '';
        $bus_info['excess_luggage'] = $request->excess_luggage ?? 0; // Add excess luggage checkbox value
        $bus_info['excess_luggage_description'] = $request->excess_luggage_description ?? null; // Add excess luggage description
        session()->put('booking_form', $bus_info);

        function discount($amount)
        {
            $coupon = session()->get('booking_form')['discount'];
            $discount = Discount::where('code', $coupon)->first();
            if (is_null($discount) || is_null($discount->booking) || $discount->booking->count() >= $discount->used) {
                return session()->get('booking_form')['total_amount'];
            }
            $bus_info = session()->get('booking_form', []);
            $new = $amount * (1 - $discount->percentage / 100);
            $bus_info['total_amount'] = $new;
            session()->put('booking_form', $bus_info);
            return $new;
        }

        $ins = 0;
        $dis = 0;
        $setting = Setting::first();
        if (session()->get('booking_form')['bima'] == 1) {

            if ($request->type == 'local') {
                $ins = $setting->local;
            } else {
                $ins = $setting->international;
            }
            $insuranceDate = session()->get('booking_form')['insuranceDate'];
            $today = \Carbon\Carbon::parse(session()->get('booking_form')['travel_date']);
            //$today = session()->get('booking_form')['travel_date'];
            $travelDate = \Carbon\Carbon::parse($insuranceDate);
            $days = max(1, abs($today->diffInDays($insuranceDate, false)) + 1);
            $ins *= $days;
            $bus_info = session()->get('booking_form', []);
            $bus_info['bima_amount'] = $ins;
            session()->put('booking_form', $bus_info);
            //return $days; 
        }

        $total_amount = session()->get('booking_form')['total_amount'];
        if (!is_null(session()->get('booking_form')['discount'])) {
            $price = discount($total_amount) + $ins - $bus_info['cancel_amount'];
            $dis = $total_amount - discount($total_amount);

            $bus_info = session()->get('booking_form', []);
            $bus_info['dispo'] = discount($total_amount);
            session()->put('booking_form', $bus_info);
        } else {
            $price = $total_amount + $ins - $bus_info['cancel_amount'];
        }

        Session::put('cancel', $bus_info['cancel_amount']);

        $fees = $setting->service + ($setting->service_percentage / 100 * (session()->get('booking_form')['total_amount'] * 100 / 118));
        $bus_info = session()->get('booking_form', []);
        $bus_info['discount_amount'] = $dis;
        session()->put('booking_form', $bus_info);

        return view('payment_details', compact('price', 'ins', 'fees', 'dis'));
    }

    public function get_payment(Request $request)
    {
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }
        $bus_info = session()->get('booking_form', []);

        // Process contact number
        $contactNumber = $request->contactNumber;
        if (substr($contactNumber, 0, 1) === '0') {
            $contactNumber = '255' . substr($contactNumber, 1);
        }

        $bus_info['customer_number'] = $contactNumber;
        $bus_info['customer_email'] = $request->contactEmail;
        $bus_info['customer_payment_number'] = $request->payment_contact;
        $bus_info['countrycode'] = $request->countrycode;

        $user = $request->user_id ?? "";
        $payment_method =  $request->payment_method;

        session()->put('booking_form', $bus_info);

        return $this->pay($request->amount, $user, $payment_method);
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

    public function pay($amount, $user, $method)
    {
        $tigo = new TigosecureController();
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }
        $bookingForm = session()->get('booking_form');
        $bima = session()->get('booking_form')['bima'];
        $xcode = $this->generateRandomId();
        $data = [
            'account' => session()->get('booking_form')['customer_payment_number'],
            'countryCode' => '255',
            'country' => 'TZA',
            'firstName' => session()->get('booking_form')['customer_name'],
            'lastName' => '',
            'email' => session()->get('booking_form')['customer_email'],
            'currency' => 'TZS',
            'amount' => round($amount),
            'transactionRefId' => $xcode,
        ];
        // Generate unique booking code
        $bookingCode = $this->generateRandomCode();
        $bus = Bus::with(['busname', 'campany.balance'])->find(session()->get('booking_form')['bus_id']);

        // Prepare booking data with payment_status as Unpaid
        $pop = '';
        if (auth()->check()) {
            if (auth()->user()->role == 'vender') {
                $pop = auth()->user()->id;
            } else {
                $pop = '';
            }
        }
        $bookingData = [
            'booking_code' => $bookingCode,
            'campany_id' => $bus->campany->id,
            'bus_id' => session()->get('booking_form')['bus_id'],
            'route_id' => session()->get('booking_form')['route_id'],
            'pickup_point' => session()->get('booking_form')['pickup_point'],
            'dropping_point' => session()->get('booking_form')['dropping_point'],
            'travel_date' => session()->get('booking_form')['travel_date'],
            'seat' => session()->get('booking_form')['seats'],
            'amount' => round($amount),
            'gender' => session()->get('booking_form')['gender'],
            'age' => session()->get('booking_form')['age'],
            'infant_child' => session()->get('booking_form')['infant_child'],
            'age_group' => session()->get('booking_form')['age_group'],
            'payment_status' => 'Unpaid', // Set initial status to Unpaid
            'customer_phone' => session()->get('booking_form')['customer_number'],
            'customer_name' => session()->get('booking_form')['customer_name'],
            'customer_email' => session()->get('booking_form')['customer_email'],
            'bima' => session()->get('booking_form')['bima'],
            'insuranceDate' => session()->get('booking_form')['insuranceDate'],
            'vender_id' => $pop,
            'discount' => session()->get('booking_form')['discount'],
            'discount_amount' => session()->get('booking_form')['discount_amount'],
            'distance' => session()->get('booking_form')['route_distance'],
            'busFee' => session()->get('booking_form')['dispo'] ?? session()->get('booking_form')['total_amount'],
            'schedule_id' => session()->get('booking_form')['schedule_id'],
            'cancel_key' => session()->get('booking_form')['cancel_key'],
            'excess_luggage' => session()->get('booking_form')['excess_luggage'], // Add excess luggage
            'excess_luggage_description' => session()->get('booking_form')['excess_luggage_description'], // Add excess luggage description
        ];

        if ($bima == 1) {
            $bookingData['bima_amount'] = session()->get('booking_form')['bima_amount'];
        } else {
            $bookingData['bima_amount'] = 0;
        }

        // Create booking with Unpaid status
        try {
            $booking = Booking::create($bookingData);
        } catch (\Exception $e) {
            Log::channel('tigo')->error('Failed to create unpaid booking', [
                'error' => $e->getMessage(),
                'data' => $bookingData,
            ]);
            return response()->json(['status' => 'error', 'message' => 'Failed to create booking'], 500);
        }

        // Initiate payment and get transactionRefId
        if ($method == 'mixx') {
            try {
                $paymentResponse = $tigo->payment($data);
                // Store transactionRefId in booking
                $booking->update(['transaction_ref_id' => $paymentResponse['transactionRefId']]);
                // Clear session data
                session()->forget('booking_form');
                // Redirect to payment URL
                return redirect($paymentResponse['redirectUrl']);
            } catch (\Exception $e) {
                Log::channel('tigo')->error('Payment initiation failed', [
                    'error' => $e->getMessage(),
                    'booking_id' => $booking->id,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment initiation failed'], 500);
            }
        } elseif ($method == 'dpo') {

            try {
                $dpo = new PDOController();
                Session::put('booking', $booking);
                //return "haha";
                return $dpo->initiatePayment(
                    round($amount),
                    session()->get('booking_form')['customer_name'],
                    session()->get('booking_form')['customer_name'],
                    session()->get('booking_form')['customer_number'],
                    session()->get('booking_form')['customer_email'],
                    $xcode
                );
            } catch (\Exception $e) {
                // Log the error
                Log::error('DPO Payment initiation failed: ' . $e->getMessage());
                // Optionally, redirect the user back with an error message
                return $e->getMessage();
            }
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            // Log request details
            Log::channel('tigo')->info('Tigo Callback Request', [
                'method' => $request->method(),
                'data' => $request->all(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            // Extract and validate parameters
            $data = $request->all();
            $transStatus = $data['trans_status'] ?? null;
            $transactionRefId = $data['transaction_ref_id'] ?? null;
            $mfsId = $data['mfs_id'] ?? null;
            $verificationCode = $data['verification_code'] ?? null;

            if (!$transactionRefId || !$transStatus) {
                Log::channel('tigo')->warning('Missing required parameters', [
                    'transaction_ref_id' => $transactionRefId,
                    'trans_status' => $transStatus,
                ]);
                return view('payments.failed', ['data' => null]);
            }

            $booking1 = session()->get('booking1');
            $booking2 = session()->get('booking2');
            if (!is_null($booking1) && !is_null($booking2)) {
                $round = new RoundpaymentController();
                $code1 = $booking1->booking_code ?? 'N/A';
                $code2 = $booking2->booking_code ?? 'N/A';
                $data1 = $round->roundtrip($$transactionRefId, $request->CompanyRef, $verificationCode, $code1);
                $data2 = $round->roundtrip($transactionRefId, $request->CompanyRef, $verificationCode, $code2);
                $red = new RedirectController();
                return $red->showRoundTripBookingStatus($data1, $data2);
            }

            // Retrieve booking
            $booking = Booking::where('transaction_ref_id', $transactionRefId)->first();

            if (!$booking) {
                Log::channel('tigo')->error('Booking not found', ['transaction_ref_id' => $transactionRefId]);
                return response()->json(['status' => 'error', 'message' => 'Booking not found'], 400);
            }

            // Check for duplicate processing
            if ($booking->payment_status !== 'Unpaid') {
                Log::channel('tigo')->warning('Booking already processed', ['transaction_ref_id' => $transactionRefId]);
                return response()->json(['status' => 'received'], 200);
            }

            // Validate transaction status
            if (strtolower($transStatus) !== 'success') {
                Log::channel('tigo')->warning('Payment failed', [
                    'transaction_ref_id' => $transactionRefId,
                    'trans_status' => $transStatus,
                ]);
                $booking->update(['payment_status' => 'Failed']);
                return response()->json(['status' => 'received'], 200);
            }

            // Validate bus and company
            $bus = Bus::with(['busname', 'route', 'campany.balance'])->find($booking->bus_id);

            if (!$bus || $bus->busname->id != $booking->campany_id) {
                Log::channel('tigo')->error('Invalid bus or company', [
                    'bus_id' => $booking->bus_id,
                    'company_id' => $booking->campany_id,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid bus or company'], 400);
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
                    if ($booking->vender_id > 0 && $booking->vender->VenderAccount) {
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

                /////////for cancel procelss./////////

                //auth()->user()->temp_wallets->amount = 0;
                //auth()->user()->temp_wallets->save();

                /////////////////////////

                // Calculate VAT on bus owner amount
                //$vatAmount = $busOwnerAmount * (18 / 118);
                // $vatAmount = $busOwnerAmount * (0.5 / 100);
                // $booking->vat = $vatAmount;
                // $busOwnerAmount -= $vatAmount;


                // Calculate system shares
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
                    'trans_status' => $transStatus,
                    'mfs_id' => $mfsId,
                    'verification_code' => $verificationCode,
                    'fee' => $bookingFee,
                    'service' => $bookingService,
                    'amount' => $busOwnerAmount, // Store bus owner share separately
                    'payment_method' => 'mixx',
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
                    'booking_id' => $booking->booking_code, // Use booking ID
                ]);

                // Increment admin wallet for payment fees
                $adminWallet->increment('balance', $paymentFeesAmount);

                // Update company balance
                $bus->campany->balance->increment('amount', $busOwnerAmount);

                DB::commit();

                Log::channel('tigo')->info('Payment processed successfully', [
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
            } catch (\Exception $e) {
                DB::rollBack();
                Log::channel('tigo')->error('Failed to update records', [
                    'error' => $e->getMessage(),
                    'booking_id' => $booking->id,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Failed to update records'], 500);
            }

            return response()->json(['status' => 'received'], 200);
        } catch (\Exception $e) {
            Log::channel('tigo')->error('Tigo Callback Error', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'timestamp' => now()->toDateTimeString(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Server error'], 500);
        } finally {
            // Clear only payment-related session data
            session()->forget('payment_data');
        }
    }

    public function handleRedirect($transactionRefId)
    {
        $url = new RedirectController();
        Session::forget('cancel');
        return $url->_redirect($transactionRefId);
    }
    private function generateRandomCode()
    {
        do {
            // Generate 2 random letters
            $letters = '';
            for ($i = 0; $i < 2; $i++) {
                $letters .= chr(rand(65, 90)); // A-Z
            }

            // Generate 8 random digits
            $numbers = '';
            for ($i = 0; $i < 8; $i++) {
                $numbers .= rand(0, 9);
            }

            // Combine with # prefix
            $code = $letters . $numbers;
        } while (Booking::where('booking_code', $code)->exists());

        return $code;
    }

    public function by_route()
    {
        $cities = City::orderBy('name', 'asc')->get();
        return view('by_route', compact('cities'));
    }

    public function by_route_search(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'departure_city' => 'required|exists:cities,id',
            'arrival_city' => 'required|exists:cities,id|different:departure_city',
            'departure_date' => 'required|date|after_or_equal:today',
            'bus_class' => 'sometimes|in:any,10,20,30,40',
            'passengers' => 'sometimes|integer|min:1',
        ]);

        // Retrieve city names and normalize departure date
        $departureCityName = City::findOrFail($validated['departure_city'])->name;
        $arrivalCityName = City::findOrFail($validated['arrival_city'])->name;
        $departure_date = Carbon::parse($validated['departure_date'])->toDateString();

        session()->put('departure_date', $departure_date);

        // Query buses with relationships and filter by route
        $busQuery = Bus::with([
            'busname' => function ($query) {
                $query->where('status', 1);
            },
            'route.via',
            'schedule' => function ($query) use ($departureCityName, $arrivalCityName, $departure_date) {
                $query->where('from', $departureCityName)
                    ->where('to', $arrivalCityName)
                    ->where('schedule_date', $departure_date);
            },
            'booking' => function ($query) use ($departure_date) {
                $query->where('travel_date', $departure_date)
                    ->where('payment_status', 'Paid');
            }
        ])
            ->whereHas('busname', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('schedule', function ($query) use ($departureCityName, $arrivalCityName, $departure_date) {
                $query->where('from', $departureCityName)
                    ->where('to', $arrivalCityName)
                    ->where('schedule_date', $departure_date);
            });

        // Add bus class filter if specified and not "any"
        if (!empty($validated['bus_class']) && $validated['bus_class'] !== 'any') {
            $busQuery->where('bus_type', $validated['bus_class']);
        }

        $busList = $busQuery->get()
            ->map(function ($bus) {
                return tap($bus, function ($bus) {
                    // Ensure total_seats is available
                    $total_seats = $bus->total_seats ?? $bus->busname->total_seats ?? 0;

                    // Calculate booked seats from pre-loaded bookings
                    $booked_seats = $bus->booking
                        ->flatMap(function ($booking) {
                            // Handle comma-separated seats, trim whitespace, and filter valid seats
                            return array_filter(array_map('trim', explode(',', $booking->seat)));
                        })
                        ->unique()
                        ->count();

                    $bus->booked_seats = $booked_seats;
                    $bus->remain_seats = $total_seats - $booked_seats;

                    // Ensure remain_seats is not negative
                    $bus->remain_seats = max(0, $bus->remain_seats);
                });
            });

        // Debugging: Uncomment to inspect the data
        //return $busList;

        return view('by_route_search', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
    }

    public function history(Request $request)
    {
        $query = Booking::with(['campany', 'route_name', 'user'])
            ->whereHas('campany', function ($q) {
                $q->where('id', auth()->user()->campany->id);
            });

        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('travel_date', today());
                    break;
                case 'week':
                    $query->whereBetween('travel_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('travel_date', now()->month)->whereYear('travel_date', now()->year);
                    break;
                case 'year':
                    $query->whereYear('travel_date', now()->year);
                    break;
            }
        }

        $bookings = $query->where('payment_status', 'Paid')->latest()->get();
        return view('controller.history', compact('bookings'));
    }


    // public function print_ticket(Request $request)
    // {
    //     //return json_decode($request->data);
    //     $data = json_decode($request->data);
    //     $dns2d = new DNS2D();

    //     // Generate QR code as HTML
    //     $qrCode = $dns2d->getBarcodeHTML($data->booking_code, 'QRCODE', 6, 6, 'black');
    //     $data->qrcode = $qrCode;

    //     // Load the view for the PDF
    //     $pdf = Pdf::loadView('print.ticket', ['data' => $data]);

    //     // Set paper size (4x10 inches converted to points)
    //     $pdf->setPaper([0, 0, 4 * 72, 10 * 72], 'portrait');

    //     // Get the Dompdf instance
    //     $dompdf = $pdf->getDomPDF();
    //     $canvas = $dompdf->getCanvas();
    //     $width = $canvas->get_width();
    //     $height = $canvas->get_height();

    //     // Add text watermark
    //     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($width, $height) {
    //         $text = "Kilimanjaro Bus - Copy";
    //         $font = $fontMetrics->getFont('Helvetica', 'normal');
    //         $size = 20;
    //         $textWidth = $fontMetrics->getTextWidth($text, $font, $size);
    //         $textHeight = $fontMetrics->getFontHeight($font, $size);

    //         // Position watermark diagonally across the page
    //         $x = ($width - $textWidth) / 2;
    //         $y = ($height - $textHeight) / 2;

    //         // Set opacity and rotation
    //         $canvas->set_opacity(0.3, 'Multiply');
    //         $canvas->page_text($x, $y, $text, $font, $size, [0.6, 0.6, 0.6], 0, 0, -45);
    //     });

    //     return $pdf->download($data->customer_name . '.pdf');
    // }

    public function print_ticket(Request $request)
    {
        $data = json_decode($request->data);
        $dns2d = new DNS2D();
        $qrCode = $dns2d->getBarcodeHTML($data->booking_code, 'QRCODE', 6, 6, 'black');
        $data->qrcode = $qrCode;

        $pdf = Pdf::loadView('print.ticket', ['data' => $data]);
        $pdf->setPaper([0, 0, 4 * 72, 10 * 72], 'portrait'); // 4"x10"

        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $width = $canvas->get_width();
        $height = $canvas->get_height();

        // Add diagonal text watermark
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($width, $height) {
            $text = "HIGHLINK";
            $font = $fontMetrics->getFont('Helvetica', 'bold');
            $size = 24;
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);
            $textHeight = $fontMetrics->getFontHeight($font, $size);

            $canvas->save();
            $canvas->set_opacity(0.2);
            $canvas->translate($width / 2, $height / 2);
            $canvas->rotate(-45, 0, 0);
            $canvas->translate(-$textWidth / 2, $textHeight / 4);
            $canvas->text(0, 0, $text, $font, $size, [0.7, 0.7, 0.7]);
            $canvas->restore();
        });

        return $pdf->download($data->customer_name . '.pdf');
    }

    public function edit($id)
    {
        $booking = Booking::find($id);
        return view('edit', compact('booking'));
    }

    public function update(Request $request)
    {
        //return $request->all();

        $booking = Booking::find($request->booking_id);
        $booking->update([
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'updated successfully');
    }

    public function transferBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'new_bus_id' => 'required|exists:buses,id',
            'new_schedule_id' => 'required|exists:schedules,id',
            'new_travel_date' => 'required|date',
            'new_pickup_point' => 'required|string',
            'new_dropping_point' => 'required|string',
            'new_amount' => 'required|numeric|min:0',
            'new_busFee' => 'required|numeric|min:0',
            'new_discount_amount' => 'required|numeric|min:0',
            'new_distance' => 'required|numeric|min:0',
            'new_bima_amount' => 'required|numeric|min:0',
            'new_vat' => 'required|numeric|min:0',
            'new_fee' => 'required|numeric|min:0',
            'new_service' => 'required|numeric|min:0',
            'new_vender_fee' => 'required|numeric|min:0',
            'new_vender_service' => 'required|numeric|min:0',
            'new_campany_id' => 'required|exists:campanies,id',
            'new_route_id' => 'required|exists:routes,id',
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::find($request->booking_id);
            if (!$booking) {
                return back()->with('error', 'Booking not found.');
            }

            // Fetch new bus details to get route and company information
            $newBus = Bus::with('route', 'campany')->find($request->new_bus_id);
            if (!$newBus) {
                return back()->with('error', 'New bus not found.');
            }

            // Generate a new booking code
            $newBookingCode = $this->generateRandomCode();

            $booking->update([
                'bus_id' => $request->new_bus_id,
                'schedule_id' => $request->new_schedule_id,
                'route_id' => $request->new_route_id,
                'campany_id' => $request->new_campany_id,
                'travel_date' => $request->new_travel_date,
                'pickup_point' => $request->new_pickup_point,
                'dropping_point' => $request->new_dropping_point,
                'amount' => $request->new_amount,
                'busFee' => $request->new_busFee,
                'discount_amount' => $request->new_discount_amount,
                'distance' => $request->new_distance,
                'bima_amount' => $request->new_bima_amount,
                'vat' => $request->new_vat,
                'fee' => $request->new_fee,
                'service' => $request->new_service,
                'vender_fee' => $request->new_vender_fee,
                'vender_service' => $request->new_vender_service,
                'payment_status' => 'Unpaid', // Reset payment status for new payment
                'transaction_ref_id' => null,
                'mfs_id' => null,
                'verification_code' => null,
                'payment_method' => null,
                'booking_code' => $newBookingCode,
                // Retain passenger details from original booking
                'gender' => $booking->gender,
                'age' => $booking->age,
                'infant_child' => $booking->infant_child,
                'age_group' => $booking->age_group,
                'customer_phone' => $booking->customer_phone,
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'user_id' => $booking->user_id,
                'vender_id' => $booking->vender_id,
                'bima' => $booking->bima,
                ////'bima_amount' => $booking->bima_amount,
                'insuranceDate' => $booking->insuranceDate,
                'discount' => $booking->discount,
                //'discount_amount' => $booking->discount_amount,
                'cancel_amount' => $booking->cancel_amount,
                'cancel_key' => $booking->cancel_key,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Booking transferred successfully. Passenger needs to re-enter details and make payment.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking transfer failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to transfer booking: ' . $e->getMessage());
        }
    }
}
