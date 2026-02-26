<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    // Tambahkan ini untuk memaksa jalur build Vite di Vercel
public function boot(): void
{
    // Cukup gunakan ini saja untuk memberitahu lokasi manifest
    if (app()->environment('production') || env('VERCEL')) {
        \Illuminate\Support\Facades\Vite::useManifestFilename('build/manifest.json');
    }
}
}
}
