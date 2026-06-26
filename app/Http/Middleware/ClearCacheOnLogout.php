<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ClearCacheOnLogout
{
    /**
     * Handle an incoming request.
     *
     * Clears all application cache when user logs out or changes account.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Check if this is a logout request
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
        // Check for POST logout (Laravel default)
        if ($request->is('logout') && $request->isMethod('POST')) {
            return true;
        }

        // Check for GET logout (some apps use this)
        if ($request->is('logout') && $request->isMethod('GET')) {
            return true;
        }

        // Check if user was just logged out (session invalidated)
        if ($request->session()->has('_flash') &&
            $request->session()->get('_flash.old') &&
            in_array('auth', $request->session()->get('_flash.old'))) {
            return true;
        }

        return false;
    }

    /**
     * Clear all application cache
     */
    protected function clearAllCache(): void
    {
        // Clear all cache stores
        Cache::flush();

        // Clear config cache (optional, uncomment if needed)
        // $this->clearConfigCache();

        // Clear route cache (optional, uncomment if needed)
        // $this->clearRouteCache();

        // Clear view cache (optional, uncomment if needed)
        // $this->clearViewCache();
    }

    /**
     * Clear config cache
     */
    protected function clearConfigCache(): void
    {
        if (function_exists('exec')) {
            exec('php ' . base_path('artisan') . ' config:clear 2>/dev/null >/dev/null &');
        }
    }

    /**
     * Clear route cache
     */
    protected function clearRouteCache(): void
    {
        if (function_exists('exec')) {
            exec('php ' . base_path('artisan') . ' route:clear 2>/dev/null >/dev/null &');
        }
    }

    /**
     * Clear view cache
     */
    protected function clearViewCache(): void
    {
        if (function_exists('exec')) {
            exec('php ' . base_path('artisan') . ' view:clear 2>/dev/null >/dev/null &');
        }
    }
}
