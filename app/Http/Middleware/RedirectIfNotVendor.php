<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the vendor is logged in using the 'vendor' guard
        if (!auth()->guard($guard)->check()) {
        // If request expects JSON, return JSON error
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // Otherwise redirect to vendor login page
        return redirect()->route('vendor.login');
        }

        return $next($request);
    }
}
