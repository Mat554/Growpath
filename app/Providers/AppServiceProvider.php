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
        // Memastikan Laravel mencari manifest Vite di jalur yang benar saat di Vercel
        if (app()->environment('production') || env('VERCEL')) {
            Vite::useManifestFilename('build/manifest.json');
        }
    }
}
