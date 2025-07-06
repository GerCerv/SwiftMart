<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckVendorVerifiedAndApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $vendor = Auth::user();  // Get the authenticated user

        if (!$vendor->hasVerifiedEmail()) {
            return redirect()->route('vendor.verifyEmail');  // Redirect to email verification page
        }

        if ($vendor->status === 'pending') {
            return redirect()->route('vendor.pending');  // Redirect to pending page
        }

        // Allow the request to continue if vendor is verified and approved
        return $next($request);
    }
}

