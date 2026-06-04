<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Helpers\ViewHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show parent profile
     */
    public function show()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $viewName = ViewHelper::resolveView('ortu.ortu-profile');
        return view($viewName);
    }

    /**
     * Update parent avatar
     */
    public function updateAvatar(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        $file = $request->file('avatar');

        // 3. Buat nama file super unik
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // 4. Hapus foto lama
        if ($user->avatar && Storage::disk('s3')->exists($user->avatar)) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // 5. Upload foto baru ke Supabase
        Storage::disk('s3')->putFileAs('/', $file, $filename, 'public');

        // 6. Update database
        $user->update([
            'avatar' => $filename
        ]);

        return back()->with('status', 'Foto profil berhasil diperbarui!');
    }

    // =========================================================
    // KONEKSI ANAK
    // =========================================================

    /**
     * Connect to child (send connection request)
     */
    public function connectKoneksi(Request $request)
    {
        $request->validate([
            'child_code' => 'required|string',
        ]);

        // Cari siswa berdasarkan user_code yang diinput
        $siswa = User::where('user_code', $request->child_code)->first();

        // Jika siswa tidak ditemukan
        if (!$siswa) {
            return back()->with('error', 'User ID Siswa tidak ditemukan. Pastikan ID diketik dengan benar.');
        }

        // Jika ditemukan, simpan ke akun ortu dengan status PENDING
        Auth::user()->update([
            'child_id_code' => $siswa->user_code,
            'child_connection_status' => 'pending'
        ]);

        return back()->with('success', 'Permintaan koneksi telah dikirim ke akun ' . $siswa->name . '. Menunggu persetujuan.');
    }

    /**
     * Revoke/remove child connection
     */
    public function revokeKoneksi()
    {
        Auth::user()->update(['child_id_code' => null]);
        return back()->with('success', 'Koneksi dengan anak dilepaskan.');
    }
}