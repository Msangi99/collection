<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PDOController;
use App\Http\Controllers\TigosecureController;
use App\Http\Controllers\CashController; // Added this line
use App\Models\Booking;
use App\Models\bus;
use App\Models\City;
use App\Models\Discount;
use App\Models\route;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    const EXCESS_LUGGAGE_FEE = 2500; // TSh. 2,500 for excess luggage

    public function index()
    {
        // Fetch booking counts
        $paidCount = Booking::where('user_id', Auth::user()->id)
            ->where('payment_status', 'Paid')
            ->count();

        $failedCount = Booking::where('user_id', Auth::user()->id)
            ->where('payment_status', 'Fail')
            ->count();

        $unpaidCount = Booking::where('user_id', Auth::user()->id)
            ->where('payment_status', 'Unpaid')
            ->count();

        $cancelledCount = Booking::where('user_id', Auth::user()->id)
            ->where('payment_status', 'Cancel')
            ->count();

        return view('customer.index', compact('paidCount', 'failedCount', 'unpaidCount', 'cancelledCount'));
    }

    

    public function mybooking()
    {
    $booking = Booking::with('bus.route','vender','campany.busOwnerAccount')
            ->where('customer_email', Auth::user()->email)
            ->orWhere('user_id', auth()->user()->id)
            ->latest()
            ->get();

        //return $booking; 

        return view('customer.mybooking', compact('booking'));
    }

    public function mybooking_search(Request $request)
    {
        $busList = '';
        if (!$request->query('query')) {
            $currentTime = Carbon::now()->format('H:i:s');
            $currentDate = Carbon::now()->format('Y-m-d');

            $busList = Bus::with(['schedules' => function ($query) use ($currentDate) {
                    $query->where('schedule_date', '>', $currentDate)
                    ->orwhere('schedule_date', '=', $currentDate);
            }])
                ->where('campany_id', $request->bus_id)
                ->whereHas('schedules', function ($query) use ($currentDate) {
                    $query->where('schedule_date','>=', $currentDate);
                       //->where('start', '>=', Carbon::now()->format('H:i:s')); // Optional
                })
                ->get();
        }
        return view('customer.busroot', compact('busList'));
    }

    public function by_route()
    {
        return view('customer.byroute');
    }

    public function by_route_search(Request $request)
    {
        // Validate the request
        //return $request->all();
        $validated = $request->validate([
            'departure_city' => 'required|exists:cities,id',
            'arrival_city' => 'required|exists:cities,id|different:departure_city',
            'departure_date' => 'required|date|after_or_equal:today',
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
                    ->where('schedule_date', $departure_date)
                    ->where(function ($timeQuery) use ($departure_date) {
                        // If it's today, only show schedules that haven't started yet
                        if ($departure_date === Carbon::now()->toDateString()) {
                            $timeQuery->where('start', '>', Carbon::now()->toTimeString());
                        }
                    });
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
                    ->where('schedule_date', $departure_date)
                    ->where(function ($timeQuery) use ($departure_date) {
                        // If it's today, only show schedules that haven't started yet
                        if ($departure_date === Carbon::now()->toDateString()) {
                            $timeQuery->where('start', '>', Carbon::now()->toTimeString());
                        }
                    });
            });

        // Optional filter by bus_type if provided and not 'any'
        if ($request->filled('bus_type') && $request->bus_type !== 'any') {
            $busQuery->where('bus_type', $request->bus_type);
        }

        $busList = $busQuery
            ->get()
            ->map(function ($bus) {
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

                return $bus;
            }); // Convert to array to avoid dynamic property warnings

        // Debugging: Uncomment to inspect the data
        //return response()->json($busList);

        return view('customer.bookingcard', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
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

        return view('customer.bookingform', compact('car'));
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
        return redirect()->route('customer.seats');
    }

    public function seates()
    {
        $booking_form = session()->get('booking_form');
        $bus_id = $booking_form['bus_id'];
        $travel_date = $booking_form['travel_date'];
        $price = $booking_form['dropping_point_amount'];

        $info = session()->get('booking_form');
        $car = Bus::with([
            'busname',
            'route',
            'schedule' => function ($query) use ($travel_date) {
                $query->where('schedule_date', $travel_date);
            },
            'route.points'
        ])->find($bus_id);
        $car = Bus::with([
            'busname',
            'route',
            'schedule' => function ($query) use ($travel_date) {
                $query->where('schedule_date', $travel_date);
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

        return view('customer.seats', compact('price', 'booked_seats', 'car'));

        //return  $car;
    }

    public function get_seats(Request $request)
    {
        $seats = $request->selected_seats;
        if ((auth()->user()->temp_wallets->amount ?? 0) > 0) {
            $price  = $request->total_amount - auth()->user()->temp_wallets->amount;
        } else {
            $price = $request->total_amount;
        }

        $bus_info = session()->get('booking_form', []);

        $bus_info['seats'] = $seats;

        $bus_info['total_amount'] = $price;

        session()->put('booking_form', $bus_info);

        if (session('rebook') !== null) {
            $rebook = Booking::find(session('rebook')->id);
            //return $rebook;
            if ($rebook->busFee < $price) {
                return redirect()->route('customer.seats')->with('error', 'Your rebooking amount for seat is ' . convert_money($rebook->busFee) . '. ' . app('currency'));
            } else {
                $new = new RebookController();
                return $new->rebook_data(session()->get('booking_form'));
            }
        }

        if ($seats == null || $seats == []) {
            return back()->with('error', 'Seats not selected');
        }

        return redirect()->route('customer.pay');
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
        return view('customer.payment', compact('price', 'seats', 'info', 'car', 'time', 'date', 'fees', 'distance'));
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


    public function payment_info(Request $request)
    {
        //return $request->all();
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
        $bus_info['excess_luggage'] = $request->excess_luggage ?? 0; // Add excess luggage checkbox value
        $bus_info['excess_luggage_description'] = $request->excess_luggage_description ?? null; // Add excess luggage description
        session()->put('booking_form', $bus_info);

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
        $excessLuggageFee = 0;

        if (session()->get('booking_form')['excess_luggage'] == 1) {
            $excessLuggageFee = self::EXCESS_LUGGAGE_FEE;
            $bus_info = session()->get('booking_form', []);
            $bus_info['excess_luggage_fee'] = $excessLuggageFee;
            session()->put('booking_form', $bus_info);
        }

        if (!is_null(session()->get('booking_form')['discount'])) {
            $price = $this->applyDiscount($total_amount) + $ins + $excessLuggageFee;
            $dis = $total_amount - $this->applyDiscount($total_amount);

            $bus_info = session()->get('booking_form', []);
            $bus_info['dispo'] = $this->applyDiscount($total_amount);
            session()->put('booking_form', $bus_info);
        } else {
            $price = $total_amount + $ins + $excessLuggageFee;
        }

        $fees = $setting->service + ($setting->service_percentage / 100 * (session()->get('booking_form')['total_amount'] * 100 / 118));
        $bus_info = session()->get('booking_form', []);
        $bus_info['discount_amount'] = $dis;
        session()->put('booking_form', $bus_info);

        return view('customer.payment_details', compact('price', 'ins', 'fees', 'dis'));
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

    public function get_payment(Request $request)
    {
        //return $request->all();


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

        $isResave = $request->has('resave_ticket') && $request->input('resave_ticket') == '1';

        return $this->pay($request->amount, $user, $payment_method, $isResave);

        //return $request->all();
    }

    public function pay($amount, $user, $method, $isResave = false)
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
            'payment_status' => $isResave ? 'resaved' : 'Unpaid', // Set initial status to Unpaid or resaved
            'resaved_until' => $isResave ? Carbon::now()->addDay() : null,
            'customer_phone' => session()->get('booking_form')['customer_number'],
            'customer_name' => session()->get('booking_form')['customer_name'],
            'customer_email' => session()->get('booking_form')['customer_email'],
            'user_id' => auth()->user()->id,
            'bima' => session()->get('booking_form')['bima'],
            'insuranceDate' => session()->get('booking_form')['insuranceDate'],
            'vender_id' => $pop,
            'discount' => session()->get('booking_form')['discount'],
            'discount_amount' => session()->get('booking_form')['discount_amount'],
            'distance' => session()->get('booking_form')['route_distance'],
            'busFee' => session()->get('booking_form')['dispo'] ?? session()->get('booking_form')['total_amount'],
            'schedule_id' => session()->get('booking_form')['schedule_id'],
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
            Log::channel('tigo')->error('Failed to create booking', [
                'error' => $e->getMessage(),
                'data' => $bookingData,
            ]);
            return response()->json(['status' => 'error', 'message' => 'Failed to create booking'], 500);
        }

        if ($isResave) {
            session()->forget('booking_form');
            return redirect()->route('customer.mybooking')->with('success', 'Ticket resaved successfully! Please pay within 24 hours. After that, the booking will be cancelled.');
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
        } elseif ($method == 'cash') {

            try {
                Session::put('booking', $booking);
                $data = Session::get('booking');
                $cash = new CashController();
                //return $data;
                return $cash->cash($data, $xcode);
            } catch (\Exception $e) {
                // Log the error
                Log::error('DPO Payment initiation failed: ' . $e->getMessage());
                // Optionally, redirect the user back with an error message
                return $e->getMessage();
            }
        }
    }

    public function update_profile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'contact' => ['nullable', 'string', 'max:20'],
            'payment_number' => ['nullable', 'string', 'max:50'], // Adjust max length as needed
            'password' => ['nullable', 'string', 'min:8'], // Requires password_confirmation field
        ]);

        try {
            // Get the authenticated user
            $user = Auth::user();

            // Update user fields
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->contact = $validated['contact'];

            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }

            // Save user
            $user->save();


            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $booking = Booking::find($id);
        return view('customer.edit', compact('booking'));
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

    public function cancelResavedTicket($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->where('payment_status', 'resaved')
                          ->firstOrFail();

        $booking->update(['payment_status' => 'Cancel']);

        return redirect()->route('customer.mybooking')->with('success', 'Resaved ticket cancelled successfully.');
    }

    public function payResavedTicket($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->where('payment_status', 'resaved')
                          ->firstOrFail();

        $setting = Setting::first();
        $price = $booking->amount; // Use the amount from the resaved booking
        $fees = $setting->service + ($setting->service_percentage / 100 * ($booking->amount * 100 / 118));
        $dis = $booking->discount_amount ?? 0;
        $ins = $booking->bima_amount ?? 0;

        // We need to set up a session for the payment process, similar to the initial booking flow.
        // This is a simplified version, as the full booking_form session might not be available.
        // For now, we'll pass direct values to the view.
        // If the payment gateway requires a full booking_form session, we might need to reconstruct it.

        return view('customer.pay_resaved', compact('booking', 'price', 'fees', 'dis', 'ins'));
    }

    private function applyDiscount($amount)
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
}
