<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        return $next($request);
    }
    public function redirectTo(Request $request)
{
    // Check if the request expects JSON (for API calls)
    if (! $request->expectsJson()) {
        return route('home');  // Redirect unauthenticated users to /home
    }
    if ($request->is('vendor/*')) {
        return route('vendor.login');
    }

    return route('login');
    
}
}
