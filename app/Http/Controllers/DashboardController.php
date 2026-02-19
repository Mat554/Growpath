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

    // Halaman Dashboard Orang Tua
   public function ortu()
    {
        // 1. Pastikan yang akses cuma Ortu
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        // 2. Cari data anak berdasarkan child_id_code yang dimasukkan ortu saat register
        $anak = \App\Models\User::where('user_code', Auth::user()->child_id_code)
                                ->where('role', 'siswa')
                                ->first();

        // 3. Tampilkan View Dashboard Ortu sambil mengirim data anak
        return view('dashboard.ortu', compact('anak'));
    }
}