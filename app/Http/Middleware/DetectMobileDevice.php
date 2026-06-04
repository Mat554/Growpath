<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectMobileDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if mobile view is forced via URL parameter or session
        $forceMobile = $request->query('mobile') === '1'
                       || $request->query('mobile') === 'true'
                       || $request->session()->get('force_mobile', false);

        // Detect mobile device from User Agent
        $isMobile = $this->isMobileDevice($request);

        // Store detection results in session for persistence
        if ($forceMobile || $isMobile) {
            $request->session()->put('mobile_view', true);
        } else {
            // Allow user to explicitly disable mobile view
            if ($request->query('desktop') === '1') {
                $request->session()->put('mobile_view', false);
            }
        }

        // Share mobile detection with all views
        view()->share('isMobileDevice', $request->session()->get('mobile_view', $isMobile));

        return $next($request);
    }

    /**
     * Check if the request is from a mobile device
     */
    private function isMobileDevice(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');

        // Mobile detection patterns
        $mobilePatterns = [
            '/android/i',
            '/webos/i',
            '/iphone/i',
            '/ipad/i',
            '/ipod/i',
            '/blackberry/i',
            '/windows phone/i',
            '/mobile/i',
            '/mini/i',
            '/opera mobi/i',
            '/kindle/i',
            '/silk/i',
            '/playbook/i',
            '/BB10/i',
            '/Chrome/i',
            '/Mobile Safari/i'
        ];

        foreach ($mobilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                // Additional check: tablet (iPad) should still be considered mobile-friendly
                // but laptops with "Mobile" in UA shouldn't be forced to mobile view
                if (preg_match('/tablet|ipad/i', $userAgent)) {
                    return true;
                }
                // For phones, definitely mobile
                if (preg_match('/android|iphone|ipod|windows phone/i', $userAgent)) {
                    return true;
                }
            }
        }

        // Check viewport width as fallback
        $viewportWidth = $request->header('Viewport-Width', '');
        if ($viewportWidth && (int)$viewportWidth < 768) {
            return true;
        }

        return false;
    }
}