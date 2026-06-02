<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsParent
{
    /**
     * Handle an incoming request.
     * Ensures only parent (ortu) users can access the route.
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

        // Check if user has ortu role
        if (Auth::user()->role !== 'ortu') {
            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Halaman ini hanya untuk orang tua.');
            } elseif (Auth::user()->role === 'siswa') {
                return redirect()->route('dashboard')->with('error', 'Halaman ini hanya untuk orang tua.');
            }
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}