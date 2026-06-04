<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;

class ViewHelper
{
    /**
     * Check if mobile view should be used
     */
    public static function isMobileRequest(): bool
    {
        // Check session for mobile view preference
        if (session()->has('mobile_view') && session()->get('mobile_view') === true) {
            return true;
        }

        // Check query parameter
        if (request()->query('mobile') === '1' || request()->query('mobile') === 'true') {
            return true;
        }

        // Auto-detect based on user agent
        return self::isMobileDevice();
    }

    /**
     * Check if request is from mobile device
     */
    public static function isMobileDevice(): bool
    {
        $userAgent = request()->header('User-Agent', '');

        $mobilePatterns = [
            '/android/i',
            '/mobile/i',
            '/iphone/i',
            '/ipod/i',
            '/windows phone/i',
            '/blackberry/i',
            '/tablet/i',
            '/ipad/i',
        ];

        foreach ($mobilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve view name based on device
     * Tries mobile. prefix first if mobile, otherwise returns original
     */
    public static function resolveView(string $viewPath): string
    {
        if (self::isMobileRequest()) {
            $mobileView = 'mobile.' . $viewPath;
            if (View::exists($mobileView)) {
                return $mobileView;
            }
        }

        return $viewPath;
    }

    /**
     * Toggle mobile view mode
     */
    public static function toggleMobileView(bool $enabled = null): bool
    {
        if ($enabled === null) {
            // Toggle
            $current = session()->get('mobile_view', false);
            session()->put('mobile_view', !$current);
            return !$current;
        }

        session()->put('mobile_view', $enabled);
        return $enabled;
    }
}
