<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Busmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $buses = bus::with('busname', 'route','campany')->get();

        View::share('buses', $buses);
        return $next($request);
    }
}
