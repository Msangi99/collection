<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Illuminate\Support\Facades\Auth;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($request->wantsJson()) {
            return new JsonResponse(['two_factor' => false]);
        }

        $request->session()->regenerate();

        if ($user->role === 'bus_campany' || $user->role === 'local_bus_owner') {
            return redirect()->route('index')->with('success', 'Logged in successfully.');
        } else if ($user->role === 'admin') {
            return redirect()->route('system.index')->with('success', 'Login successful.');
        } else if ($user->role === 'vender') {
            return redirect()->route('vender.index')->with('success', 'Login successful.');
        } else if ($user->role === 'customer') {
            return redirect()->route('customer.index')->with('success', 'Login successful.');
        }

        return redirect()->intended(config('fortify.home'))->with('success', 'Logged in successfully.');
    }
}
