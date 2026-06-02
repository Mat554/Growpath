<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleOTP
{
    /**
     * Handle an incoming request.
     * Rate limits OTP requests to prevent brute force attacks.
     * Allows3 OTP verification attempts per minute per IP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'otp_attempts:' . $request->ip();

        // Check if rate limit exceeded
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'success' => false,
                'error' => 'Terlalu banyak percobaan. Silakan coba lagi dalam ' . $seconds . ' detik.',
                'retry_after' => $seconds
            ], 429);
        }

        // Increment attempt counter
        RateLimiter::hit($key, 60); // 60 seconds window

        return $next($request);
    }
}
