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
    if (config('app.env') === 'production' || config('app.env') === 'local') {
        \Illuminate\Support\Facades\Vite::useBuildDirectory('build');
        \Illuminate\Support\Facades\Vite::useManifestFilename('build/manifest.json');
    }
}
}
