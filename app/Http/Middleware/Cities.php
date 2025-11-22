<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Cities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cities = City::orderBy('name', 'asc')->get();

        View::share('cities', $cities);
        return $next($request);
    }
}
