<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Halaman Dashboard Siswa
    public function index()
    {
        // 1. Pastikan yang akses cuma Siswa
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('dashboard.ortu');
        }

        // 2. Tampilkan View Dashboard Siswa
        return view('dashboard');
    }

   public function ortu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = \App\Models\User::where('user_code', Auth::user()->child_id_code)
                                ->where('role', 'siswa')
                                ->first();

        // UBAH BARIS INI:
        // Jika nama filenya 'ortu-dashboard.blade.php' di dalam folder views
        return view('ortu.ortu-dashboard', compact('anak')); 
    }
    // Tambahkan fungsi ini di bawah fungsi index() yang sudah ada
    public function profile()
    {
        // Pastikan hanya user yang login yang bisa akses
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('profile');
    }

    public function profileOrtu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }
        return view('ortu.ortu-profile');
    }

    public function dashboardAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.admin-dashboard');
    }
    
}