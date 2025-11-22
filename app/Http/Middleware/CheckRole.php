<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this resource.');
        }

        $user = Auth::user();

        // Check if user has a role
        if (empty($user->role)) {
            Log::warning('User ID ' . $user->id . ' has no role assigned.', ['request' => $request->path()]);
            return response()->view('errors.403', ['message' => 'No role assigned to your account.'], 403);
        }

        // Normalize roles for case-insensitive comparison
        $userRole = strtolower($user->role);
        $allowedRoles = array_map('strtolower', $roles);

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            Log::warning('Unauthorized access attempt by User ID ' . $user->id . ' with role ' . $user->role, [
                'allowed_roles' => $roles,
                'request' => $request->path()
            ]);
            return response()->view('errors.403', [
                'message' => 'You do not have the required role to access this resource.'
            ], 403);
        }

        return $next($request);
    }
}