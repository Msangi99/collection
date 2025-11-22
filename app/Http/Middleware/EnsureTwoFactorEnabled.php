<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // If not logged in, redirect to login
        if (! $user) {
            return redirect()->route('login');
        }

        // If user does not have 2FA enabled or confirmed
        if (is_null($user->two_factor_secret) || is_null($user->two_factor_recovery_codes) || is_null($user->two_factor_confirmed_at)) {
            return redirect()->route('two-factor.setup')
                ->with('error', 'Please enable Two-Factor Authentication before accessing this section.');
        }

        // If you want to ensure it’s also confirmed (optional, depends on your logic)
        // if (! $user->two_factor_confirmed_at) {
        //     return redirect()->route('two-factor.confirm')
        //         ->with('error', 'Please confirm your Two-Factor Authentication before continuing.');
        // }

        return $next($request);
    }
}
