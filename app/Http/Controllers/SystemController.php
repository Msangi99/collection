<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\bus;
use App\Models\Bima;
use App\Models\City;
use App\Models\User;
use App\Models\balance;
use App\Models\Booking;
use App\Models\Setting;
use App\Models\Campany;
use App\Models\Discount;
use App\Models\AdminWallet;
use App\Models\PaymentFees;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\SystemBalance;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use App\Models\AdminTransaction;
use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Pdf\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SmsController;
use App\Models\Refund;
use App\Models\CancelledBookings;

class SystemController extends Controller
{
    public function index()
    {
        $bookings = Booking::whereDate('created_at', today())->with(['bus', 'route', 'campany'])->where('payment_status', 'Paid')->get();

        // Today's total amount
        $todayAmount = Booking::whereDate('created_at', today())->where('payment_status', 'Paid')->sum('amount');
        $bima = Bima::sum('amount');

        // Weekly amounts (last 7 days)
        $weeklyAmounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $amount = Booking::whereDate('created_at', $date)->sum('amount');
            $weeklyAmounts[] = [
                'date' => $date->format('Y-m-d'),
                'amount' => $amount,
            ];
        }
        $service = SystemBalance::sum('balance');
        $fees = PaymentFees::sum('amount');
        $balance = AdminWallet::sum('balance');
        $cancelledAmount = CancelledBookings::sum('amount');

        return view('system.dashboard', compact('bookings', 'todayAmount', 'weeklyAmounts', 'service', 'fees', 'bima', 'balance', 'cancelledAmount'));
    }

    public function buses()
    {
        $buses = bus::with('busname', 'route')->paginate(10);
        return view('system.buses', compact('buses'));
    }

    public function pay_request(Request $request)
    {
        // Fetch pending transactions
        $pendingTransactions = Transaction::whereIn('status', ['Pending'])
            ->with(['campany', 'user'])
            ->get();

        // Fetch all transactions (default: no filter)
        $allTransactions = Transaction::with(['campany', 'user'])->get();

        // Pass modal state from query parameter
        return view('system.transaction', compact('pendingTransactions', 'allTransactions'));
    }

    public function filter(Request $request)
    {
        // Validate request
        $request->validate([
            'filter' => 'required|in:today,week,month,year,custom',
            'start_date' => 'required_if:filter,custom|date',
            'end_date' => 'required_if:filter,custom|date|after_or_equal:start_date',
        ]);

        // Fetch pending transactions (unchanged by filter)
        $pendingTransactions = Transaction::whereIn('status', ['Pending'])
            ->with(['campany', 'user'])
            ->get();

        // Initialize query for all transactions
        $query = Transaction::with(['campany', 'user']);

        // Apply filter
        $filter = $request->input('filter');
        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'week') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]);
        } elseif ($filter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($filter === 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        } elseif ($filter === 'custom') {
            $query->whereBetween('created_at', [
                Carbon::parse($request->input('start_date')),
                Carbon::parse($request->input('end_date'))->endOfDay(),
            ]);
        }

        $allTransactions = $query->get();

        // Redirect back to index with filtered data
        return view('system.transaction', compact('pendingTransactions', 'allTransactions'));
    }

    public function completes(Request $request, $transaction, $campany = null)
    {
        $transaction = Transaction::findOrFail($transaction);
        $transaction->status = 'Completed';
        $transaction->save();

        return redirect()->route('pay.request')->with('success', 'Transaction marked as completed.');
    }

    public function cancels(Request $request, $transaction, $campany = null)
    {
        $transaction = Transaction::findOrFail($transaction);
        $transaction->status = 'Cancelled';
        $transaction->save();

        return redirect()->route('pay.request')->with('success', 'Transaction cancelled.');
    }

    public function complete(Request $request, $transaction, $campany = null, $vender = null, $reference_number = null)
    {
        $transaction = Transaction::findOrFail($transaction);

        // Optional: Validate that the transaction belongs to the company
        if ($transaction->campany_id != $campany) {
            return redirect()->back()->with('error', 'Invalid company for this transaction.');
        } else if ($campany != 0) {
            $transaction->status = 'Completed';
            $transaction->reference_number = $request->reference_number;
            $transaction->save();
            $balance = balance::where('campany_id', $campany)->first();
            $percent = 100 - PercentController::PERCENTAGE;
            $transactions = $transaction->amount * ($percent / 100);

            $balance->amount -= $transactions;
            $balance->save();

            return redirect()->back()->with('success', 'Transaction marked as Completed.');
        } else if ($vender != 0) {
            $transaction->status = 'Completed';
            $transaction->reference_number = $request->reference_number;
            $transaction->save();
            $user = User::find($vender);
            $user->VenderBalances->amount -= $transaction->amount;
            $user->VenderBalances->save();
            return redirect()->back()->with('success', 'Transaction marked as Completed.');
        } else {
            return back()->with('error', 'invalid transaction');
        }
    }

    public function cancel($transaction, $campany = null, $vender = null)
    {
        $transaction = Transaction::findOrFail($transaction);

        // Optional: Validate that the transaction belongs to the company
        if ($transaction->campany_id != $campany) {
            return redirect()->back()->with('error', 'Invalid company for this transaction.');
        }

        $transaction->status = 'Cancelled';
        $transaction->save();

        return redirect()->back()->with('success', 'Transaction cancelled.');
    }

    public function campany()
    {

        $campanies = Campany::all();
        return view('system.campany', compact('campanies'));
    }

    public function campany_status(Request $request)
    {
        $percent = $request->percentage ?? 0;
        $status = $request->status;
        $campany_id = $request->campany_id;

        $campany = Campany::find($campany_id);

        $campany->status = $status;
        $campany->percentage = $percent;
        $campany->save();

        return back()->with('success', 'company edit successful');
    }

    public function system_payments()
    {
        $balances = SystemBalance::all();
        $pays = PaymentFees::all();

        return view('system.payments', compact('balances', 'pays'));
    }

    public function history(Request $request)
    {
        $query = Booking::with(['campany', 'route_name', 'user', 'route', 'vender', 'bus.route', 'campany.busOwnerAccount']);
        // Apply period filter from sidebar dropdown
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
        return view('system.history', compact('bookings'));
        //return $bookings;
    }

    public function print(Request $request)
    {
        $data = $request->data;
        $data = json_decode($data, true);

        return $this->generatePDF($data);
    }

    public function generatePDF($data)
    {
        $pdf = Pdf::loadView('print.report', ['bookings' => $data]);

        return $pdf->download('income-' . now() . '.pdf');
    }

    public function vender()
    {
        $venders = User::where('role', 'vender')->get();
        return view('system.vender', compact('venders'));
    }

    public function vender_status(Request $request)
    {
        $vender_id = $request->vender_id;
        $status = $request->status;

        $vender = User::find($vender_id);
        $vender->status = $status;
        $vender->save();

        return back()->with('success', 'changes successful');
    }
    
    public function vender_percent(Request $request)
    {
        $user = user::find($request->vender_id);
        $user->VenderAccount->update(['percentage' => $request->percent]);
        return back()->with('success','account updated');
    }

    public function profile()
    {
        return view('system.profile');
    }

    public function update_profile(Request $request)
    {
        // Validate the incoming request
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

    public function cities()
    {
        return view('system.cities');
    }

    public function store_city(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Create a new city
            if (City::where('name', $request->name)->exists()) {
                return back()->with('error', 'City already exists');
            }
            City::create([
                'name' => $request->name,
            ]);

            return back()->with('success', 'City created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create city: ' . $e->getMessage()])->withInput();
        }
    }


    public function discount()
    {
        // Retrieve all discounts with a count of associated bookings where payment_status is 'Paid'
        $discounts = Discount::withCount(['booking' => function ($query) {
            $query->where('payment_status', 'Paid');
        }])->get();

        return view('system.discount', compact('discounts'));
    }


    public function add_coupon(Request $request)
    {
        $code = $request->code;
        $used = $request->used;

        if (empty($code) || empty($used)) {
            return back()->with('error', 'fill all inputs');
        }

        $data = Discount::create([
            'code' => $code,
            'used' => $used,
            'percentage' => $request->percentage
        ]);

        // Get eligible phone numbers for the coupon
        $phone = Booking::where('distance', '>=', 100) // Exclude trips < 100km
            ->whereRaw('created_at <= DATE_SUB(travel_date, INTERVAL 24 HOUR)') // Tickets bought ≥ 24 hours before travel
            ->groupBy('customer_phone')
            ->select('customer_phone', \DB::raw('count(*) as total'))
            ->orderBy('total', 'desc')
            ->limit($used)
            ->get();

        $sms = new SmsController();
        foreach ($phone as $item) {
            $sms->sms_send($item->customer_phone, "Dear customer, we are pleased to inform you that we have created a discount coupon for you. Use code: $code to enjoy a discount of $request->percentage% on your next booking. Thank you for choosing our service!");
        }

        if ($data) {
            return back()->with('success', 'discount coupon created');
        } else {
            return back()->with('error', 'coupon fail to publish');
        }
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
        return view('system.bus_route', compact('cars'));
    }


    public function balance()
    {
        $data = AdminTransaction::all();
        return view('system.balance', compact('data'));
    }

    public function print_recipt2(Request $request)
    {
        $data = json_decode($request->data);

        $pdf = Pdf::loadView('print.admin', ['data' => $data]);

        $pdf->setPaper([0, 0, 4 * 72, 7 * 72], 'portrait');

        return $pdf->stream('admin-' . now() . '.pdf');
    }

    public function update_balance(Request $request)
    {
        $request->validate([
            'trans_ref_id' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'payment_number' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        // Fetch the first admin wallet record
        $wallet = AdminWallet::first();

        // Check if wallet exists and has sufficient balance
        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance or wallet not found');
        }

        try {
            // Create a new admin transaction
            AdminTransaction::create([
                'trans_ref_id' => $request->trans_ref_id,
                'amount' => $request->amount,
                'payment_number' => $request->payment_number,
                'payment_method' => $request->payment_method,
            ]);

            // Decrement the balance
            $wallet->decrement('balance', $request->amount);

            return back()->with('success', 'Balance updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update balance: ' . $e->getMessage());
        }
    }

    public function busOwner($id)
    {
        $user = User::find($id);

        return view('system.view_bus_owner', compact('user'));
    }

    public function update_profile_bus(Request $request)
    {
        try {
            // Get the authenticated user
            $user = User::find($request->id);

            // Update user fields
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->contact = $request->input('contact');

            // Update password only if provided
            if (!empty($request->input('password'))) {
                $user->password = bcrypt($request->input('password'));
            }

            // Save user
            $user->save();

            // Update or create company details
            if ($user->campany) {
                $user->campany->update([
                    'name' => $request->input('campany_name'),
                ]);
            } elseif ($request->input('campany_name')) {
                // Create a new company record if it doesn't exist and name is provided
                $user->campany()->create([
                    'name' => $request->input('campany_name'),
                ]);
            }

            // Update or create bus owner account details
            if ($user->campany && $user->campany->busOwnerAccount) {
                $user->campany->busOwnerAccount->update([
                    'registration_number' => $request->input('registration_number'),
                    'tin' => $request->input('tin'),
                    'vrn' => $request->input('vrn'),
                    'office_number' => $request->input('office_number'),
                    'box' => $request->box,
                    'street' => $request->input('street'),
                    'town' => $request->input('town'),
                    'city' => $request->input('city'),
                    'region' => $request->input('region'),
                    'whatsapp_number' => $request->input('whatsapp_number'),
                    'bank_name' => $request->input('bank_name'),
                    'bank_number' => $request->input('account_number'),
                ]);
            } elseif ($user->campany && (
                $request->input('registration_number') ||
                $request->input('tin') ||
                $request->input('vrn') ||
                $request->input('office_number') ||
                $request->input('street') ||
                $request->input('town') ||
                $request->input('city') ||
                $request->input('region') ||
                $request->input('whatsapp_number') ||
                $request->input('bank_name') ||
                $request->input('account_number')
            )) {
                // Create a new bus owner account if it doesn't exist and any relevant data is provided
                $user->campany->busOwnerAccount()->create([
                    'registration_number' => $request->input('registration_number'),
                    'tin' => $request->input('tin'),
                    'vrn' => $request->input('vrn'),
                    'office_number' => $request->input('office_number'),
                    'street' => $request->input('street'),
                    'town' => $request->input('town'),
                    'city' => $request->input('city'),
                    'region' => $request->input('region'),
                    'whatsapp_number' => $request->input('whatsapp_number'),
                    'bank_name' => $request->input('bank_name'),
                    'bank_number' => $request->input('account_number'),
                ]);
            }

            return back()->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()])->withInput();
        }
    }
    
    public function setting()
    {
        $settings = Setting::first();
        return view('system.setting', compact('settings'));
    }
    
    public function setting_update(Request $request)
    {
        Setting::first()->update(
                [
                    'local' => $request->local,
                    'international' => $request->international,
                    'service' =>  $request->service,
                    'service_percentage' => $request->service_percentage
                ]
            );
        
        return back()->with('success', 'settings updated');
    }

    public function refunds()
    {
        $refunds = Refund::all();
        return view('system.refunds', compact('refunds'));
    }

    public function approveRefund($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->status = 'Approved';
        $refund->save();

        $booking = Booking::where('booking_code', $refund->booking_code)->first();

        $booking->update([
            'payment_status' => 'Refund',
            'refund_id' => $refund->id,
        ]);

        $booking->save();

        $campany = Campany::with('balance')->find($booking->campany_id);

        $campany->balance->decrement('amount', $refund->amount);
        $campany->save();

        // Here you would typically handle the actual refund process,
        // e.g., integrate with a payment gateway to send money back.
        // For this task, we'll just update the status.

        return back()->with('success', 'Refund approved successfully.');
    }

    public function rejectRefund($id)
    {
        $refund = Refund::findOrFail($id);
        $refund->status = 'Rejected';
        $refund->save();

        return back()->with('error', 'Refund rejected.');
    }

    public function cancelled_bookings(Request $request)
    {
        // Get cancelled bookings with related data
        $cancelledBookings = CancelledBookings::with([
            'booking' => function($query) {
                $query->with(['bus.busname', 'campany', 'route']);
            }
        ])
        ->when($request->has('filter'), function($query) use ($request) {
            switch($request->filter) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate summary statistics
        $totalCancelled = CancelledBookings::count();
        $totalAmount = CancelledBookings::sum('amount');
        $todayCancelled = CancelledBookings::whereDate('created_at', Carbon::today())->count();
        $todayAmount = CancelledBookings::whereDate('created_at', Carbon::today())->sum('amount');

        return view('system.cancelled_bookings', compact(
            'cancelledBookings', 
            'totalCancelled', 
            'totalAmount', 
            'todayCancelled', 
            'todayAmount'
        ));
    }
}
