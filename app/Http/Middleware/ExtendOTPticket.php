<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtendOTPticket
{
   public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Jika user aktif membuka halaman, perpanjang umur karcisnya 5 menit
        if (Auth::check() && $request->hasCookie('tiket_bebas_otp')) {
            $response->cookie('tiket_bebas_otp', 'terverifikasi', 5);
        }

        return $response;
    }
}
