<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ClearCacheOnLogout
{
    /**
     * Handle an incoming request.
     *
     * Clears all application cache when user logs out.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if this is a logout request (AFTER response to catch the logout)
        if ($this->isLogoutRequest($request)) {
            $this->clearAllCache();
        }

        return $response;
    }

    /**
     * Check if the current request is a logout request
     */
    protected function isLogoutRequest(Request $request): bool
    {
        // Only check for POST /logout route
        if ($request->is('logout') && $request->isMethod('POST')) {
            return true;
        }

        // Only check for GET /logout route
        if ($request->is('logout') && $request->isMethod('GET')) {
            return true;
        }

        return false;
    }

    /**
     * Clear all application cache
     */
    protected function clearAllCache(): void
    {
        // Clear all Laravel cache stores
        Cache::flush();

        // Clear config, route, view cache
        $this->clearSystemCache();
    }

    /**
     * Clear system caches via artisan
     */
    protected function clearSystemCache(): void
    {
        if (function_exists('exec')) {
            exec('php ' . base_path('artisan') . ' cache:clear 2>/dev/null >/dev/null &');
        }
    }
}
