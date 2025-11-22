<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\bus;
use App\Models\Campany;
use App\Models\City;
use App\Models\Discount;
use App\Models\Roundtrip;
use App\Models\route;
use App\Models\Schedule;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class RoundTripController extends Controller
{
    const EXCESS_LUGGAGE_FEE = 2500; // TSh. 2,500 for excess luggage

    public function direction($view, $data = [])
    {
        $user = auth()->user(); // Get the authenticated user object

        if ($user) {
            if ($user->isCustomer()) {
                return view("customer.{$view}", $data);
            } elseif ($user->isVender()) {
                return view("vender.{$view}", $data);
            }
        }

        return view($view, $data);
    }

    public function index()
    {
        //$campanies = Campany::with('bus')->all();
        return $this->direction('round_1');
    }

    public function search(Request $request)
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
                    ->where('schedule_date', $departure_date);
                //->where('start', '>', Carbon::now()->toTimeString());
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
        $data = [
            'busList' => $busList,
            'departureCityName' => $departureCityName,
            'arrivalCityName' => $arrivalCityName,
            'departure_date' => $departure_date,
        ];

        return $this->direction('round_1', $data);

        //return view('vender.route', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
    }

    public function by_routesearch(Request $request)
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

        $data = [
            'busList' => $busList,
            'departureCityName' => $departureCityName,
            'arrivalCityName' => $arrivalCityName,
            'departure_date' => $departure_date,
        ];

        return $this->direction('round_1', $data);
    }

    public function by_bus(Request $request)
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
        $data = [
            'busList' => $busList,
        ];
        return $this->direction('round_1', $data);
        //return view('vender.route', compact('busList'));
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

        $data = [
            'busList' => $busList,
            'departureCityName' => $departureCityName,
            'arrivalCityName' => $arrivalCityName,
            'departure_date' => $departure_date,
        ];

        return $this->direction('round_2', $data);

        //return view('vender.begin', compact('busList', 'departureCityName', 'arrivalCityName', 'departure_date'));
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

        $data = [
            'car' => $car,
        ];

        return $this->direction('round_3', $data);

        //return view('booking_form', compact('car'));
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

        return redirect()->route('round.trip.seats');
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

        $data = [
            'booked_seats' => $booked_seats,
            'car' => $car,
            'price' => $price,
        ];

        return $this->direction('round_4', $data);

        //return view('seates', compact('price', 'booked_seats', 'car'));

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


        return redirect()->route('round.trip.payment');
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
        $data = [
            'price' => $price,
            'seats' => $seats,
            'info' => $info,
            'car' => $car,
            'time' => $time,
            'date' => $date,
            'fees' => $fees,
            'distance' => $distance,
        ];

        return $this->direction('round_5', $data);
        //return view('vender.payment', compact('price', 'seats', 'info', 'car', 'time', 'date', 'fees', 'distance'));
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
        $bus_info['has_excess_luggage'] = $request->excess_luggage ?? 0;
        $bus_info['excess_luggage_fee'] = 0; // Initialize to 0
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

        if (session()->get('booking_form')['has_excess_luggage'] == 1) {
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
        $bus_info['fees'] = $fees;
        $bus_info['price'] = $price;
        session()->put('booking_form', $bus_info);



        if (is_null(session()->get('key'))) {

            $key = uniqid('Round_');

            session()->put('key', $key);
        } else {
            $key = session()->get('key');
        }

        $round = Roundtrip::create([
            'key' => $key,
            'data' => json_encode(session()->get('booking_form')),
        ]);

        session()->forget('booking_form');

        $get = Roundtrip::where('key', $key)->count();

        if ($get !== 2) {

            return redirect()->route('round.trip')->with('info', 'proceed with returning booking');
        }

        $firstbooking = Roundtrip::where('key', $key)
            ->first();

        $secondbooking = Roundtrip::where('key', $key)
            ->orderByDesc('id')
            ->first();

        $data1 = json_decode($firstbooking->data, true);
        $data2 = json_decode($secondbooking->data, true);
        
        
        $data = [
            'price' => $data1['price'] + $data2['price'],
            'ins' => ($data1['bima_amount'] ?? 0) + ($data2['bima_amount'] ?? 0),
            'fees' => $data1['fees'] + $data2['fees'],
            'dis' => $data1['discount_amount'] + $data2['discount_amount'],
        ];

        session()->forget('key');

        session()->put('firstbooking', $firstbooking);
        session()->put('secondbooking', $secondbooking);

        return $this->direction('round_6', $data);

        // return $this->direction('round_6', $data);

        //return view('vender.payment_details', compact('price', 'ins', 'fees', 'dis'));
    }

    public function get_payment(Request $request)
    { 
        Log::info('Round Trip Get Payment Request', [
            'request_data' => $request->all(),
            'payment_method' => $request->payment_method,
            'amount' => $request->amount
        ]);

        //return $request->all();
        // Process contact number
        $contactNumber = $request->contactNumber;
        if (substr($contactNumber, 0, 1) === '0') {
            $contactNumber = '255' . substr($contactNumber, 1);
        }

        $bus_info['customer_number'] = $contactNumber;
        $bus_info['customer_email'] = $request->contactEmail;
        $bus_info['customer_payment_number'] = $request->payment_contact ?? '';
        $bus_info['countrycode'] = $request->countrycode;
        $bus_info['customer_name'] = $request->customer_name ?? 'Customer'; // Add customer name

        $user = $request->user_id ?? "";

        session()->put('booking_form', $bus_info);
        $payment_method =  $request->payment_method;
        
        Log::info('Round Trip Payment Info Prepared', [
            'bus_info' => $bus_info,
            'user' => $user,
            'payment_method' => $payment_method
        ]);

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

    public function pay($amount, $user, $method)
    {
        Log::info('Starting Round Trip Payment Processing', [
            'amount' => $amount,
            'user' => $user,
            'method' => $method,
            'firstbooking_session' => session()->get('firstbooking') ? 'exists' : 'missing',
            'secondbooking_session' => session()->get('secondbooking') ? 'exists' : 'missing',
            'booking_form_session' => session()->get('booking_form') ? 'exists' : 'missing'
        ]);
        
        // This method needs to handle two bookings for a round trip
        $firstBookingData = json_decode(session()->get('firstbooking')->data, true);
        $secondBookingData = json_decode(session()->get('secondbooking')->data, true);

        //return $secondBookingData;

        // Common payment details from the request (e.g., contact number, email)
        $commonPaymentInfo = session()->get('booking_form'); // This session data is set in get_payment
        
        Log::info('Round Trip Payment Data', [
            'firstBookingData' => $firstBookingData,
            'secondBookingData' => $secondBookingData,
            'commonPaymentInfo' => $commonPaymentInfo
        ]);

        // Prepare booking data for the first leg
        $bookingCode1 = $this->generateRandomCode();
        $bus1 = Bus::with(['busname', 'campany.balance'])->find($firstBookingData['bus_id']);
        $pop1 = '';
        $cust1 = 0;
        if (auth()->check()) {
            if (auth()->user()->role == 'vender') {
                $pop1 = auth()->user()->id;
            } else if(auth()->user()->role == 'customer') {
                $cust1 = auth()->user()->id;
            }
        } 

        $bookingData1 = [
            'booking_code' => $bookingCode1,
            'campany_id' => $bus1->campany->id,
            'bus_id' => $firstBookingData['bus_id'],
            'route_id' => $firstBookingData['route_id'],
            'pickup_point' => $firstBookingData['pickup_point'],
            'dropping_point' => $firstBookingData['dropping_point'],
            'travel_date' => $firstBookingData['travel_date'],
            'seat' => $firstBookingData['seats'],
            'amount' => round($firstBookingData['price']),
            'gender' => $firstBookingData['gender'],
            'age' => $firstBookingData['age'],
            'infant_child' => $firstBookingData['infant_child'],
            'age_group' => $firstBookingData['age_group'],
            'payment_status' => 'Unpaid',
            'customer_phone' => $commonPaymentInfo['customer_number'],
            'customer_name' => $firstBookingData['customer_name'],
            'customer_email' => $commonPaymentInfo['customer_email'],
            'user_id' => $cust1,
            'bima' => $firstBookingData['bima'],
            'insuranceDate' => $firstBookingData['insuranceDate'],
            'vender_id' => $pop1,
            'discount' => $firstBookingData['discount'],
            'discount_amount' => $firstBookingData['discount_amount'],
            'distance' => $firstBookingData['route_distance'],
            'busFee' => $firstBookingData['dispo'] ?? $firstBookingData['total_amount'],
            'schedule_id' => $firstBookingData['schedule_id'],
            'has_excess_luggage' => $firstBookingData['has_excess_luggage'],
            'excess_luggage_fee' => $firstBookingData['excess_luggage_fee'],
        ];
        if ($firstBookingData['bima'] == 1) {
            $bookingData1['bima_amount'] = $firstBookingData['bima_amount'];
        } else {
            $bookingData1['bima_amount'] = 0;
        }

        $data = [
            'account' => $commonPaymentInfo['customer_payment_number'] ?? '',
            'countryCode' => '255',
            'country' => 'TZA',
            'firstName' => $commonPaymentInfo['customer_name'] ?? '',
            'lastName' => '',
            'email' => $commonPaymentInfo['customer_email'] ?? '',
            'currency' => 'TZS',
            'amount' => round($amount),
            'transactionRefId' => uniqid('Round_'),
        ];

        // Prepare booking data for the second leg
        $bookingCode2 = $this->generateRandomCode();
        $bus2 = Bus::with(['busname', 'campany.balance'])->find($secondBookingData['bus_id']);
        $pop2 = '';
        $cust2 = 0;
        if (auth()->check()) {
            if (auth()->user()->role == 'vender') {
                $pop2 = auth()->user()->id;
            } else if(auth()->user()->role == 'customer') {
                $cust2 = auth()->user()->id;
            }
        }

        $bookingData2 = [
            'booking_code' => $bookingCode2,
            'campany_id' => $bus2->campany->id,
            'bus_id' => $secondBookingData['bus_id'],
            'route_id' => $secondBookingData['route_id'],
            'pickup_point' => $secondBookingData['pickup_point'],
            'dropping_point' => $secondBookingData['dropping_point'],
            'travel_date' => $secondBookingData['travel_date'],
            'seat' => $secondBookingData['seats'],
            'amount' => round($secondBookingData['price']),
            'gender' => $secondBookingData['gender'],
            'age' => $secondBookingData['age'],
            'infant_child' => $secondBookingData['infant_child'],
            'age_group' => $secondBookingData['age_group'],
            'payment_status' => 'Unpaid',
            'customer_phone' => $commonPaymentInfo['customer_number'],
            'customer_name' => $secondBookingData['customer_name'],
            'customer_email' => $commonPaymentInfo['customer_email'],
            'user_id' => $cust2,
            'bima' => $secondBookingData['bima'],
            'insuranceDate' => $secondBookingData['insuranceDate'],
            'vender_id' => $pop2,
            'discount' => $secondBookingData['discount'],
            'discount_amount' => $secondBookingData['discount_amount'],
            'distance' => $secondBookingData['route_distance'],
            'busFee' => $secondBookingData['dispo'] ?? $secondBookingData['total_amount'],
            'schedule_id' => $secondBookingData['schedule_id'],
            'has_excess_luggage' => $secondBookingData['has_excess_luggage'],
            'excess_luggage_fee' => $secondBookingData['excess_luggage_fee'],
        ];
        if ($secondBookingData['bima'] == 1) {
            $bookingData2['bima_amount'] = $secondBookingData['bima_amount'];
        } else {
            $bookingData2['bima_amount'] = 0;
        }

        DB::beginTransaction();
        try {
            $booking1 = Booking::create($bookingData1);
            $booking2 = Booking::create($bookingData2);

            // Store bookings in session for payment processing
            session()->put('booking1', $booking1);
            session()->put('booking2', $booking2);
            session()->put('is_round', true);

            // Keep customer payment info in session for DPO payment
            // Don't clear booking_form yet as it contains customer payment details

            // Redirect to a success page or initiate payment gateway
            // For now, let's redirect to a generic success page.
            //return redirect()->route('round.trip.payment.success')->with('success', 'Round trip bookings created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error
            \Illuminate\Support\Facades\Log::error('Round trip booking failed: ' . $e->getMessage());
            return redirect()->route('round.trip.payment.failed')->with('error', 'Failed to create round trip bookings: ' . $e->getMessage());
        }

        if ($method == 'mixx') {
            Log::info('Initiating Mixx Payment for Round Trip', [
                'amount' => $amount,
                'data' => $data,
                'commonPaymentInfo' => $commonPaymentInfo,
                'session_booking_form' => session()->get('booking_form')
            ]);
            
            $tigo = new TigosecureController();
            try {
                $paymentResponse = $tigo->payment($data);
                
                Log::info('Mixx Payment Response', [
                    'response' => $paymentResponse,
                    'redirectUrl' => $paymentResponse['redirectUrl'] ?? 'Not set'
                ]);
                
                // Store transactionRefId in booking
                //$booking->update(['transaction_ref_id' => $paymentResponse['transactionRefId']]);
                // Clear session data
                session()->forget('booking_form');
                // Redirect to payment URL
                return redirect($paymentResponse['redirectUrl']);
                
            } catch (\Exception $e) {
                Log::channel('tigo')->error('Mixx Payment initiation failed', [
                    'error' => $e->getMessage(),
                    'data' => $data,
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('round.trip.payment')->withErrors(['payment_error' => 'Mixx Payment initiation failed: ' . $e->getMessage()]);
            }
        } elseif ($method == 'dpo') {

            try {
                $dpo = new PDOController();
                
                // Clear roundtrip session data after storing in bookings
                session()->forget(['firstbooking', 'secondbooking']);
                
                Log::info('Initiating DPO Payment for Round Trip', [
                    'amount' => $amount,
                    'customer_name' => $commonPaymentInfo['customer_name'] ?? 'Customer',
                    'customer_number' => $commonPaymentInfo['customer_number'],
                    'customer_email' => $commonPaymentInfo['customer_email']
                ]);
                
                $result = $dpo->initiatePayment(
                    round($amount),
                    $commonPaymentInfo['customer_name'] ?? 'Customer',
                    $commonPaymentInfo['customer_name'] ?? 'Customer',
                    $commonPaymentInfo['customer_number'],
                    $commonPaymentInfo['customer_email'],
                    uniqid('Round_')
                );
                
                Log::info('DPO Payment Initiation Result', [
                    'result_type' => gettype($result),
                    'result' => $result
                ]);
                
                return $result;
            } catch (\Exception $e) {
                // Log the error
                Log::error('DPO Payment initiation failed: ' . $e->getMessage());
                // Redirect back with error message instead of returning string
                return redirect()->route('round.trip.payment')->withErrors(['payment_error' => 'DPO Payment initiation failed: ' . $e->getMessage()]);
            }
        } elseif ($method == 'cash') {
            Log::info('Processing Cash Payment for Round Trip', [
                'amount' => $amount,
                'booking1_id' => $booking1->id ?? 'Not set',
                'booking2_id' => $booking2->id ?? 'Not set',
                'booking1_code' => $booking1->booking_code ?? 'Not set',
                'booking2_code' => $booking2->booking_code ?? 'Not set'
            ]);
            
            try {
                // Process cash payment for both bookings
                $cashController = new CashController();
                
                // Process first booking
                $result1 = $cashController->cash($booking1, uniqid('Round_Cash_'));
                Log::info('Cash Payment Result 1', ['result' => $result1]);
                
                // Process second booking  
                $result2 = $cashController->cash($booking2, uniqid('Round_Cash_'));
                Log::info('Cash Payment Result 2', ['result' => $result2]);
                
                // Clear session data
                session()->forget(['booking1', 'booking2', 'is_round', 'booking_form']);
                
                return redirect()->route('round.trip.payment.success')->with('success', 'Round trip bookings created successfully via cash!');
            } catch (\Exception $e) {
                Log::error('Cash Payment processing failed', [
                    'error' => $e->getMessage(),
                    'booking1_id' => $booking1->id ?? 'Not set',
                    'booking2_id' => $booking2->id ?? 'Not set',
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('round.trip.payment')->withErrors(['payment_error' => 'Cash Payment processing failed: ' . $e->getMessage()]);
            }
        }

        
    }

    public function paymentSuccess()
    {
        $booking1 = session()->get('booking1');
        $booking2 = session()->get('booking2');
        $isRound = session()->get('is_round');

        // Clear session data after retrieval
        session()->forget(['booking1', 'booking2', 'is_round']);

        return $this->direction('round_payment_success', compact('booking1', 'booking2', 'isRound'));
    }

    public function paymentFailed($error)
    {
        // Clear any lingering session data related to the failed booking attempt
        session()->forget(['booking1', 'booking2', 'is_round', 'firstbooking', 'secondbooking', 'booking_form']);
        $data = [
            'error' => $error
        ];
        return $this->direction('round_payment_failed', $data);
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
