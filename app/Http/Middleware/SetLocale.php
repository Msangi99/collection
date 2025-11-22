<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->query('lang', Session::get('locale', config('app.locale')));
        
        if (in_array($locale, ['en', 'sw'])) { // Supported languages: English and Kiswahili
            App::setLocale($locale);
            Session::put('locale', $locale); // Store in session
        }

        return $next($request);
    }
}