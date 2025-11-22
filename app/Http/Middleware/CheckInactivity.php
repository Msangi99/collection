<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckInactivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = Session::get('last_activity_time');
            $inactivityLimit = config('session.lifetime'); // In minutes

            if ($lastActivity && Carbon::now()->diffInMinutes(Carbon::parse($lastActivity)) >= $inactivityLimit) {
                $user = Auth::user();
                
                // Check if user is vendor, admin, or bus owner before resetting two_factor_confirmed_at
                if (in_array($user->role, ['admin', 'bus_campany', 'vender', 'local_bus_owner'])) {
                    $user->two_factor_confirmed_at = null;
                    $user->save();
                    
                    \Log::info('Session timeout: Reset two_factor_confirmed_at for user ID: ' . $user->id . ' (Role: ' . $user->role . ')');
                }
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Your session has expired due to inactivity. Please log in again.');
            }

            Session::put('last_activity_time', Carbon::now());
        }

        return $next($request);
    }
}
