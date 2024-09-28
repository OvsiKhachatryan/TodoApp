<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is authenticated and email is not verified
        if ($user && !$user->hasVerifiedEmail()) {
            return redirect('http://localhost:5173/verify-email'); // Redirect to your Vue.js verification page
        }

        return $next($request); // Proceed to the next middleware or request handler
    }
}
