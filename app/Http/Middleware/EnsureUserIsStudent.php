<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsStudent
{
    /**
     * Handle an incoming request.
     * Ensures only student (siswa) users can access the route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check if user has siswa role
        if (Auth::user()->role !== 'siswa') {
            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini hanya untuk siswa.');
            } elseif (Auth::user()->role === 'ortu') {
                return redirect()->route('dashboard.ortu')->with('error', 'Halaman ini hanya untuk siswa.');
            }
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}