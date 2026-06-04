<?php

namespace App\Traits;

use Illuminate\Support\Facades\View;

trait MobileViewResolver
{
    /**
     * Return view name based on device type
     * Checks if mobile view exists, otherwise returns desktop view
     *
     * @param string $viewPath The view path (e.g., 'dashboard' or 'admin.admin-dashboard')
     * @return string The actual view to render
     */
    protected function resolveMobileView(string $viewPath): string
    {
        // Check if mobile view is enabled
        if ($this->shouldUseMobileView()) {
            $mobileView = 'mobile.' . $viewPath;

            // Check if mobile view file exists
            if (View::exists($mobileView)) {
                return $mobileView;
            }
        }

        return $viewPath;
    }

    /**
     * Check if mobile view should be used
     */
    protected function shouldUseMobileView(): bool
    {
        // Check session for mobile view preference
        return session()->get('mobile_view', false);
    }

    /**
     * Force mobile view for a request
     */
    protected function enableMobileView(): void
    {
        session()->put('mobile_view', true);
    }

    /**
     * Disable mobile view for a request
     */
    protected function disableMobileView(): void
    {
        session()->put('mobile_view', false);
    }
}