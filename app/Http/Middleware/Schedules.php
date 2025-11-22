<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Discount;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Schedules
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

        $discounts = Discount::withCount(['booking' => function ($query) {
            $query->where('payment_status', 'Paid');
        }])->get();

        foreach ($discounts as $discount) {
            if ($discount->booking->count() == $discount->used) {
                //$discount->delete();
            }
        }
        return $next($request);
    }
}
