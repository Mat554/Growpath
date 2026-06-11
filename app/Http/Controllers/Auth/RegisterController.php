<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Process registration
     */
    public function register(Request $request)
    {
        // 1. Validasi Input + Cek Kode Siswa
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/'
            ],
            'role' => 'required|in:siswa,ortu',
            'kelas' => 'required_if:role,siswa|nullable|integer',

            // Opsional tapi harus valid jika diisi
            'child_id_code' => 'nullable|string|exists:users,user_code',
        ], [
            'child_id_code.exists' => 'User ID Siswa tidak ditemukan. Pastikan kodenya sudah benar.',
            'password.min' => 'Password terlalu pendek, minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka.'
        ]);

        // 2. Logika Generate Kode Siswa
        $generatedUserCode = null;
        if ($validated['role'] === 'siswa') {
            $randomNumber = rand(10000, 99999);
            $generatedUserCode = 'SIS-' . $validated['kelas'] . '-' . $randomNumber;
        }

        // 3. Logika Permintaan Koneksi (Khusus Ortu)
        $childId = null;
        $connectionStatus = null;

        // Jika dia Ortu DAN dia mengisi kode anak di form registrasi
        if ($validated['role'] === 'ortu' && !empty($validated['child_id_code'])) {
            // Cari data siswa asli berdasarkan user_code tersebut
            $child = User::where('user_code', $validated['child_id_code'])->first();

            if ($child) {
                $childId = $child->id;
                $connectionStatus = 'pending';
            }
        }

        // 4. Simpan ke Database
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $validated['role'],
            'kelas' => $validated['role'] === 'siswa' ? $validated['kelas'] : null,
            'user_code' => $generatedUserCode,

            // Simpan data koneksi
            'child_id_code' => $validated['role'] === 'ortu' ? $validated['child_id_code'] : null,
            'child_id' => $childId,
            'child_connection_status' => $connectionStatus,
        ];

        User::create($userData);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }
}
