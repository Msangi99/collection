<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Reserved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Delete all schedules where schedule_date is before today
        //Schedule::where('schedule_date', '<', now()->startOfDay())->delete();

        $bookings = Booking::where('payment_status', 'Reserved')->get();

        foreach ($bookings as $booking) {
            $mda = Carbon::parse($booking->created_at)->addDays(1);
            $now = Carbon::parse(now());

            if($now > $mda) {
                $booking->delete();
            }
        }
        return $next($request);
    }
}
