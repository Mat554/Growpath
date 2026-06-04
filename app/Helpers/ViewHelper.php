<?php

namespace App\Helpers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ViewHelper
{
    /**
     * Check if mobile view should be used
     */
    public static function isMobileRequest(): bool
    {
        // Check session for mobile view preference
        if (session()->has('mobile_view')) {
            return session()->get('mobile_view') === true;
        }

        // Check query parameter
        if (request()->query('mobile') === '1' || request()->query('mobile') === 'true') {
            return true;
        }

        // Check query parameter for desktop
        if (request()->query('desktop') === '1') {
            return false;
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

        if (empty($userAgent)) {
            return false;
        }

        $mobilePatterns = [
            '/android/i',
            '/mobile/i',
            '/iphone/i',
            '/ipod/i',
            '/windows phone/i',
            '/blackberry/i',
        ];

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

    /**
     * Resolve view name based on device
     */
    public static function resolveView(string $viewPath): string
    {
        if (!self::isMobileRequest()) {
            return $viewPath;
        }

        $mobilePath = self::getMobileViewPath($viewPath);

        try {
            if (View::exists($mobilePath)) {
                return $mobilePath;
            }
        } catch (\Exception $e) {
            Log::error('View resolution error: ' . $e->getMessage());
        }

        return $viewPath;
    }

    /**
     * Get mobile view path from original view path
     */
    private static function getMobileViewPath(string $viewPath): string
    {
        $parts = explode('.', $viewPath);
        $lastPart = end($parts);

        return 'mobile.' . $lastPart;
    }

    /**
     * Toggle mobile view mode
     */
    public static function toggleMobileView(bool $enabled = null): bool
    {
        if ($enabled === null) {
            $current = session()->get('mobile_view', false);
            session()->put('mobile_view', !$current);
            return !$current;
        }

        session()->put('mobile_view', $enabled);
        return $enabled;
    }
}