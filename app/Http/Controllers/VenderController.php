<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MixByYassController;
use App\Http\Controllers\PDOController;
use App\Http\Controllers\TigosecureController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\status\Fees;
use App\Http\Controllers\status\Vender;
use App\Mail\SendEmail;
use App\Models\Bima;
use App\Models\Booking;
use App\Models\bus;
use App\Models\City;
use App\Models\Discount;
use App\Models\PaymentFees;
use App\Models\route;
use App\Models\Setting;
use App\Models\SystemBalance;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class VenderController extends Controller
{
    const EXCESS_LUGGAGE_FEE = 2500; // TSh. 2,500 for excess luggage

    public function index(Request $request)
    {
        $venderId = auth()->user()->id;
        $filter = $request->get('filter', 'month'); // Default to 'month'

        $TodayBookings = Booking::whereDate('created_at', Carbon::today())
            ->where('vender_id', $venderId)
            ->get();

        $WeekBookings = Booking::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->where('vender_id', $venderId)->get();

        $bookings = Booking::where('vender_id', $venderId)
            ->latest()
            ->get();

        // Prepare label/data based on filter
        $monthlyLabels = [];
        $monthlyData = [];

        switch ($filter) {
            case 'today':
                $labels = [];
                $data = [];

                foreach (range(0, 23) as $hour) {
                    $label = sprintf('%02d:00', $hour);
                    $labels[] = $label;

                    $count = Booking::where('vender_id', $venderId)
                        ->whereDate('created_at', Carbon::today()) // this is fine
                        ->whereRaw('HOUR(created_at) = ?', [$hour]) // this is the FIX
                        ->count();

                    $data[] = $count;
                }

                $monthlyLabels = $labels;
                $monthlyData = $data;
                break;

            case 'week':
                $labels = [];
                $data = [];

                foreach (range(0, 6) as $i) {
                    $day = Carbon::now()->startOfWeek()->addDays($i);
                    $labels[] = $day->format('D');

                    $count = Booking::where('vender_id', $venderId)
                        ->whereDate('created_at', $day)
                        ->count();

                    $data[] = $count;
                }
                $monthlyLabels = $labels;
                $monthlyData = $data;
                break;

            case 'year':
                $stats = Booking::select(
                    DB::raw("MONTH(created_at) as month"),
                    DB::raw("COUNT(*) as total")
                )
                    ->whereYear('created_at', Carbon::now()->year)
                    ->where('vender_id', $venderId)
                    ->groupBy(DB::raw("MONTH(created_at)"))
                    ->orderBy('month')
                    ->get();

                foreach (range(1, 12) as $month) {
                    $monthlyLabels[] = Carbon::create()->month($month)->format('M');
                    $monthlyData[] = $stats->firstWhere('month', $month)->total ?? 0;
                }
                break;

            case 'month':
            default:
                $stats = Booking::select(
                    DB::raw("DAY(created_at) as day"),
                    DB::raw("COUNT(*) as total")
                )
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->where('vender_id', $venderId)
                    ->groupBy(DB::raw("DAY(created_at)"))
                    ->orderBy('day')
                    ->get();

                $daysInMonth = Carbon::now()->daysInMonth;

                foreach (range(1, $daysInMonth) as $day) {
                    $monthlyLabels[] = $day;
                    $monthlyData[] = $stats->firstWhere('day', $day)->total ?? 0;
                }
                break;
        }

        return view('vender.index', compact(
            'TodayBookings',
            'WeekBookings',
            'bookings',
            'monthlyLabels',
            'monthlyData',
            'filter'
        ));
    }

    public function route(Request $request)
    {
        // Validate the request
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
        $busList = Bus::with([
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
            })
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
        // return response()->json($busList);

        return view('vender.begin', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
    }

    public function mybooking_search(Request $request)
    {
        //return $request->all();
        $busList = '';
        if (!$request->query('query')) {
            $currentTime = Carbon::now()->format('H:i:s');
            $currentDate = Carbon::now()->format('Y-m-d');

            $busList = Bus::with(['schedules' => function ($query) use ($currentDate) {
                $query->where('schedule_date', '>', $currentDate)
                    ->orwhere('schedule_date', '=', $currentDate);
                //->where('start', '>=', Carbon::now()->format('H:i:s')); // Optional: future schedules
            }])
                ->where('campany_id', $request->bus_id)
                ->whereHas('schedules', function ($query) use ($currentDate) {
                    $query->where('schedule_date', '>=', $currentDate);
                    //->where('start', '>=', Carbon::now()->format('H:i:s')); // Optional
                })
                ->get();
        }
        return view('vender.route', compact('busList'));
    }

    public function by_route_search(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'departure_city' => 'required|exists:cities,id',
            'arrival_city' => 'required|exists:cities,id|different:departure_city',
            'departure_date' => 'required|date|after_or_equal:today',
            'bus_type' => 'sometimes|in:any,10,20,30,40',
            'passengers' => 'sometimes|integer|min:1',
        ]);

        // Retrieve city names and normalize departure date
        $departureCityName = City::findOrFail($validated['departure_city'])->name;
        $arrivalCityName = City::findOrFail($validated['arrival_city'])->name;
        $departure_date = Carbon::parse($validated['departure_date'])->toDateString();

        session()->put('departure_date', $departure_date);

        // Query buses with relationships and filter by route - only today to future
        $busQuery = Bus::with([
            'busname' => function ($query) {
                $query->where('status', 1);
            },
            'route.via',
            'schedules' => function ($query) use ($departureCityName, $arrivalCityName, $departure_date) {
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
            ->whereHas('schedules', function ($query) use ($departureCityName, $arrivalCityName, $departure_date) {
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

        // Add bus class filter if specified and not "any"
        if (!empty($validated['bus_type']) && $validated['bus_type'] !== 'any') {
            $busQuery->where('bus_type', $validated['bus_type']);
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

        return view('vender.route', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
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

        // Post-process the points if route and schedule exist
        if ($car && $car->route && $car->schedule && $car->schedule->schedule_date === $date) {
            $route = $car->route;
            $schedule = $car->schedule;

            // Filter points based on route and schedule comparison
            if ($route->from === $schedule->from && $route->to === $schedule->to) {
                // Keep only points with return = 'no'
                $filteredPoints = $car->route->points->filter(function ($point) {
                    return $point->state === 'no';
                });
            } elseif ($route->from === $schedule->to && $route->to === $schedule->from) {
                // Keep only points with return = 'yes'
                $filteredPoints = $car->route->points->filter(function ($point) {
                    return $point->state === 'yes';
                });
            }
        } else {
            // If no valid schedule or date match, use all points
            $filteredPoints = $car->route->points ?? collect();
        }

        // Add filtered points as a new attribute to the car object
        $car->filtered_points = $filteredPoints;

        return view('vender.booking_form', compact('car'));
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
            'schedule_id' => $request->schedule_id,
            'route_id' => $request->route_id,
            'pickup_point' => $request->pickup_point ?? $route->from,
            'dropping_point' => $request->dropping_point ?? $route->to,
            'travel_date' => session()->get('departure_date') ?? now()->format('Y-m-d'),
            'dropping_point_amount' => $request->dropping_point_amount ?? ($route ? $route->price : 0),
            'route_distance' => $request->route_distance ?? 0
        ];

        // Store in session
        session()->put('booking_form', $bus_info);
        //return session()->get('booking_form');
        // Redirect to seats route
        return redirect()->route('seates.vender');
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

        return view('vender.seates', compact('price', 'booked_seats', 'car'));

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


        return redirect()->route('vender.pay');
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
        return view('vender.payment', compact('price', 'seats', 'info', 'car', 'time', 'date', 'fees', 'distance'));
    }

    public function payment_info(Request $request)
    {
        //return $bus_info = session()->get('booking_form', []);
        $bus_info = session()->get('booking_form', []);
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }

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
            $price = $this->applyDiscount($total_amount) + $ins + $excessLuggageFee - $bus_info['cancel_amount'];
            $dis = $total_amount - $this->applyDiscount($total_amount);

            $bus_info = session()->get('booking_form', []);
            $bus_info['dispo'] = $this->applyDiscount($total_amount);
            session()->put('booking_form', $bus_info);
        } else {
            $price = $total_amount + $ins + $excessLuggageFee - $bus_info['cancel_amount'];
        }

        Session::put('cancel', $bus_info['cancel_amount']);

        $fees = $setting->service + ($setting->service_percentage / 100 * (session()->get('booking_form')['total_amount'] * 100 / 118));
        $bus_info = session()->get('booking_form', []);
        $bus_info['discount_amount'] = $dis;
        session()->put('booking_form', $bus_info);

        return view('vender.payment_details', compact('price', 'ins', 'fees', 'dis'));
    }

    public function get_payment(Request $request)
    {
        $bus_info = session()->get('booking_form', []);
        if (is_null(session()->get('booking_form')) || !isset(session()->get('booking_form')['total_amount'])) {
            return redirect()->route('home')->with('error', 'Session expired. Please try again.');
        }

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

        session()->put('booking_form', $bus_info);
        $payment_method =  $request->payment_method;

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
            'busFee' => session()->get('booking_form')['dispo'] ?? session()->get('booking_form')['total_amount'],
            'schedule_id' => session()->get('booking_form')['schedule_id'],
            'cancel_key' => session()->get('booking_form')['cancel_key'],
            'excess_luggage' => session()->get('booking_form')['excess_luggage'], // Add excess luggage
            'excess_luggage_description' => session()->get('booking_form')['excess_luggage_description'], // Add excess luggage description
            'excess_luggage_fee' => session()->get('booking_form')['excess_luggage_fee'] ?? '',
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

    public function bus_route()
    {
        $cars = bus::with('campany', 'route', 'schedule')
            ->whereHas('campany', function ($query) {
                $query->where('status', 1);
            })
            ->whereHas('schedule', function ($query) {
                $query->where('schedule_date', '>', now());
            })
            ->get();
        return view('vender.bus_route', compact('cars'));
    }

    public function transaction(Request $request)
    {
        // Initialize the query for transactions belonging to the authenticated vendor
        $query = Transaction::where('vender_id', auth()->user()->id);

        // Apply filter based on request parameter
        $filter = $request->query('filter', 'today'); // Default to 'today' if no filter provided
        switch ($filter) {
            case 'today':
                $query->whereBetween('created_at', [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay(),
                ]);
                break;
            case 'week':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]);
                break;
            case 'month':
                $query->whereBetween('created_at', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month);
                break;
            default:
                // Default to today's transactions
                $query->whereBetween('created_at', [
                    Carbon::today()->startOfDay(),
                    Carbon::today()->endOfDay(),
                ]);
                break;
        }

        // Execute the query to get the filtered transactions
        $coll = $query->get();

        // Calculate summary statistics
        $accept = Transaction::where('vender_id', auth()->user()->id)
            ->where('status', 'Completed')
            ->sum('amount');
        $pending = Transaction::where('vender_id', auth()->user()->id)
            ->where('status', 'pending')
            ->sum('amount');
        $cancel = Transaction::where('vender_id', auth()->user()->id)
            ->where('status', 'Cancelled')
            ->sum('amount');

        // Return the view with the data
        //return $coll;
        return view('vender.transaction', compact('coll', 'accept', 'pending', 'cancel', 'filter'));
    }

    public function transaction_request(Request $request)
    {
        $user = auth()->user();
        // Check if the company balance is sufficient
        if ($request->amount > $user->VenderBalances->amount) {
            return back()->with('error', 'Insufficient balance');
        }
        // Create the transaction
        try {
            $transaction = Transaction::create([
                'vender_id' => $user->id, // Update to company_id after migration
                'user_id' => $user->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_number' => $request->payment_number ?? auth()->user()->VenderBalances->payment_number,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Transaction request sent successfully');
        } catch (\Exception $e) {
            // Log the error for debugging

            return back()->with('error', 'Transaction request failed');
        }
    }

    public function history(Request $request)
    {
        $query = Booking::with(['campany', 'route_name', 'user', 'bus.route', 'vender'])->where('vender_id', auth()->user()->id);

        if ($request->has('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $bookings = $query->where('payment_status', 'Paid')->latest()->get();
        //return $bookings;
        return view('vender.history', compact('bookings'));
    }


    public function print(Request $request)
    {
        $data = $request->data;
        $data = json_decode($data, true);

        return $this->generatePDF($data);
    }

    public function generatePDF($data)
    {
        //return $data;
        $pdf = Pdf::loadView('print.report', ['bookings' => $data]);

        return $pdf->download('income-' . now() . '.pdf');
    }

    public function profile()
    {
        return view('vender.profile');
    }

    public function update_profile(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'contact' => ['nullable', 'string', 'max:20'],
            'tin' => ['nullable', 'string', 'max:50'],
            'house_number' => ['nullable', 'string', 'max:50'],
            'street' => ['nullable', 'string', 'max:100'],
            'town' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'altenative_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'work' => ['nullable', 'string', 'max:100'],
            'bank_number' => ['nullable', 'string', 'max:50'],
            'payment_number' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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

            // Update or create VenderAccount
            if ($user->VenderAccount) {
                $user->VenderAccount->update([
                    'tin' => $validated['tin'] ?? $user->VenderAccount->tin,
                    'house_number' => $validated['house_number'],
                    'street' => $validated['street'],
                    'town' => $validated['town'],
                    'city' => $validated['city'],
                    'province' => $validated['province'],
                    'country' => $validated['country'],
                    'altenative_number' => $validated['altenative_number'],
                    'bank_name' => $validated['bank_name'] ?? $user->VenderAccount->bank_name,
                    'bank_number' => $validated['bank_number'] ?? $user->VenderAccount->bank_number,
                ]);
            } else {
                $user->VenderAccount()->create([
                    'tin' => $validated['tin'],
                    'house_number' => $validated['house_number'],
                    'street' => $validated['street'],
                    'town' => $validated['town'],
                    'city' => $validated['city'],
                    'province' => $validated['province'],
                    'country' => $validated['country'],
                    'altenative_number' => $validated['altenative_number'],
                    'bank_name' => $validated['bank_name'],
                    'work' => $validated['work'],
                    'bank_number' => $validated['bank_number'],
                    'user_id' => $user->id,
                ]);
            }

            // Update or create VenderBalance
            if ($user->VenderBalances) {
                $user->VenderBalances->update([
                    'payment_number' => $validated['payment_number'],
                ]);
            } else {
                $user->VenderBalances()->create([
                    'payment_number' => $validated['payment_number'],
                    'user_id' => $user->id,
                    'amount' => 0, // Default value
                    'fees' => 0,   // Default value
                ]);
            }

            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }

    public function sendEmail(Request $request)
    {
        $data = [
            'name' => $request->input('name', 'User'),
            'email' => $request->input('email', 'user@example.com')
        ];

        Mail::to($data['email'])->send(new SendEmail($data));

        return response()->json(['message' => 'Email sent successfully']);
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
