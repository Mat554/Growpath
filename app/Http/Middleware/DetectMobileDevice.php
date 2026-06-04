<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectMobileDevice
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check URL parameters first
        $forceMobile = $request->query('mobile') === '1' || $request->query('mobile') === 'true';
        $forceDesktop = $request->query('desktop') === '1';

        // Detect mobile device from User Agent
        $isMobile = $this->isMobileDevice($request);

        // Determine if we should show mobile view
        $useMobile = false;

        if ($forceMobile) {
            $useMobile = true;
        } elseif ($forceDesktop) {
            $useMobile = false;
        } else {
            $useMobile = $isMobile;
        }

        // Store in session if session is available
        if ($request->hasSession()) {
            $request->session()->put('mobile_view', $useMobile);
        }

        // Share with all views
        view()->share('isMobileDevice', $useMobile);

        return $next($request);
    }

    /**
     * Check if the request is from a mobile device
     */
    private function isMobileDevice(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');

        if (empty($userAgent)) {
            return false;
        }

        // Mobile patterns
        $mobilePatterns = [
            '/android/i',
            '/mobile/i',
            '/iphone/i',
            '/ipod/i',
            '/windows phone/i',
            '/blackberry/i',
        ];

        // Tablet patterns
        $tabletPatterns = [
            '/ipad/i',
            '/tablet/i',
            '/kindle/i',
        ];

        // Check tablets first
        foreach ($tabletPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        // Check phones
        foreach ($mobilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }
}
