<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
public function boot(): void
{
    // Cukup gunakan ini untuk memastikan Laravel mencari manifest di lokasi standar
    if (app()->environment('production') || env('VERCEL')) {
        \Illuminate\Support\Facades\Vite::useManifestFilename('manifest.json');
    }
}
}
