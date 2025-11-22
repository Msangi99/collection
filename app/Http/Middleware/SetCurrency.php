<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SetCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get USD to TZS rate with caching
        $usdToTzs = round($this->getUsdToTzsRate());
        
        // Share with all views
        view()->share('usdToTzs', $usdToTzs);
        
        // Store in app container for global access
        app()->instance('usdToTzs', $usdToTzs);
        
        return $next($request);
    }

    /**
     * Get USD to TZS exchange rate
     */
    protected function getUsdToTzsRate(): float
    {
        return Cache::remember('usd_to_tzs_rate', now()->addHours(6), function () {
            $response = Http::withOptions([
                'verify' => false // Disable SSL verification for localhost
            ])->get('https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/usd.json');
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['usd']['tzs'] ?? 2500; // Default fallback rate
            }
            
            return 2500; // Default value if API fails
        });
    }
}