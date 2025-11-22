<?php

namespace App\Http\Controllers;

use App\Models\DroppingPoint;
use App\Models\PickupPoint;
use App\Models\Point;
use App\Models\Route as BusRoute;   // adapt namespace
use App\Models\Schedule;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnePageBookingController extends Controller
{
    public function view()
    {
        $cities = DB::table('cities')->orderBy('name')->get(); // adapt to your schema
        return view('onepage_booking', compact('cities'));
    }

    // 1) Find route & schedules for From->To&Date
    public function findRoute(Request $r)
    {
        $v = $r->validate([
            'from' => 'required|string',
            'to'   => 'required|string',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $route = BusRoute::where('from', $v['from'])->where('to', $v['to'])->first();

        if (!$route) {
            return response()->json(['exists' => false, 'message' => 'No route found.'], 200);
        }

        $schedules = Schedule::with(['bus.company', 'route'])
            ->whereDate('date', $v['date'])
            ->where('route_id', $route->id)
            ->orderBy('departure_time')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'route_id' => $s->route_id,
                'bus_id' => $s->bus_id,
                'operator' => $s->bus->company->name ?? 'Operator',
                'bus' => $s->bus->name ?? 'Bus',
                'departure_time' => $s->departure_time,
                'fare_per_km' => (float)($s->fare_per_km ?? 0), // optional if you price by km
                'flat_fare' => (float)($s->fare ?? 0),          // optional if you price flat
            ]);

        return response()->json([
            'exists' => true,
            'route' => ['id' => $route->id, 'from' => $route->from, 'to' => $route->to],
            'schedules' => $schedules,
        ]);
    }

    // 2) Pickup & Dropping points (+ coords)
    public function points(Request $r)
    {
        $v = $r->validate([
            'route_id' => 'required|integer|exists:routes,id',
        ]);

        $pickups = Point::where('route_id', $v['route_id'])->get(['id', 'name', 'lat', 'lng']);
        $drops   = Point::where('route_id', $v['route_id'])->get(['id', 'name', 'lat', 'lng']);

        return response()->json([
            'pickups' => $pickups,
            'drops'   => $drops,
        ]);
    }

    // 3) Distance (km) via Haversine
    public function distance(Request $r)
    {
        $v = $r->validate([
            'pickup_id'  => 'required|integer|exists:pickup_points,id',
            'dropping_id' => 'required|integer|exists:dropping_points,id',
        ]);

        $p = PickupPoint::find($v['pickup_id']);
        $d = DroppingPoint::find($v['dropping_id']);
        $km = $this->haversine($p->lat, $p->lng, $d->lat, $d->lng);

        return response()->json(['km' => round($km, 2)]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    // 4) Seatmap for a schedule
    public function seatmap(Request $r)
    {
        $v = $r->validate([
            'schedule_id' => 'required|integer|exists:schedules,id',
        ]);
        $schedule = Schedule::with('bus')->findOrFail($v['schedule_id']);

        // TODO: Pull booked seats for this schedule from DB
        $booked = []; // ['1A','1B', ...]
        // TODO: Use bus-specific layout if you have (rows/cols)
        $rows = $schedule->bus->rows ?? 10;
        $cols = $schedule->bus->cols ?? 4;
        $labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']; // support wider buses

        $grid = [];
        for ($r = 1; $r <= $rows; $r++) {
            $row = [];
            for ($c = 0; $c < $cols; $c++) {
                $label = $r . $labels[$c];
                $row[] = [
                    'label' => $label,
                    'status' => in_array($label, $booked) ? 'booked' : 'free'
                ];
            }
            $grid[] = $row;
        }
        return response()->json([
            'bus'   => $schedule->bus->name ?? 'Bus',
            'seats' => $grid,
        ]);
    }

    // 5) Price summary (mimic payment.blade.php totals)
    public function price(Request $r)
    {
        $v = $r->validate([
            'km'         => 'required|numeric|min:0',
            'seats'      => 'required|array|min:1',
            'fare_per_km' => 'nullable|numeric|min:0',
            'flat_fare'  => 'nullable|numeric|min:0',
        ]);
        $setting = Setting::first();

        // prefer distance pricing if fare_per_km provided, else flat_fare
        $base = $v['fare_per_km'] ? ($v['km'] * $v['fare_per_km']) : ($v['flat_fare'] ?? 0);
        $subtotal = $base * count($v['seats']);

        $fees = $setting
            ? ($setting->service + ($setting->service_percentage / 100 * ($subtotal * 100 / 118)))
            : 0;

        return response()->json([
            'subtotal' => round($subtotal, 2),
            'fees'     => round($fees, 2),
            'total'    => round($subtotal + $fees, 2),
        ]);
    }

    // 6) Payment info box (like payment.blade.php visuals/data)
    public function paymentInfo(Request $r)
    {
        $v = $r->validate([
            'from'        => 'required|string',
            'to'          => 'required|string',
            'date'        => 'required|date',
            'pickup_name' => 'required|string',
            'drop_name'   => 'required|string',
            'km'          => 'required|numeric',
            'seats'       => 'required|array|min:1',
            'fare_per_km' => 'nullable|numeric',
            'flat_fare'   => 'nullable|numeric',
        ]);
        // Simply echo back a shaped payload your Blade can print
        return response()->json([
            'trip' => [
                'from' => $v['from'],
                'to' => $v['to'],
                'date' => $v['date'],
                'pickup' => $v['pickup_name'],
                'drop' => $v['drop_name'],
                'km' => $v['km'],
            ],
            'pricing_inputs' => [
                'seats' => $v['seats'],
                'fare_per_km' => $v['fare_per_km'],
                'flat_fare' => $v['flat_fare'],
            ]
        ]);
    }

    // 7) Payment details box (like payment_details.blade.php)
    public function paymentDetails(Request $r)
    {
        $v = $r->validate([
            'total'   => 'required|numeric',
            'seats'   => 'required|array|min:1',
            'km'      => 'required|numeric',
        ]);

        // Fill more if you need: tax, gateway fees, etc.
        $breakdown = [
            'base_description' => 'Fare total',
            'seat_count' => count($v['seats']),
            'km' => $v['km'],
            'total' => round($v['total'], 2),
        ];
        return response()->json(['breakdown' => $breakdown]);
    }
}
