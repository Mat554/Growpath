<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    // --- 1. HALAMAN LOGIN ---
    public function showLoginForm()
    {
        return view('login');
    }

    // --- 2. PROSES LOGIN (Kirim OTP) ---
   // --- 2. PROSES LOGIN ---
    public function login(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:siswa,ortu,admin'], // Pastikan admin ada di sini
        ]);

        // B. Cari User
        $user = User::where('email', $request->email)->first();

        // C. Cek Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // D. Cek Kesesuaian Role
        if ($user->role !== $request->role) {
            $roleName = ucfirst($user->role);
            return back()->withErrors([
                'email' => "Akun ini terdaftar sebagai $roleName. Silakan pindah ke tab $roleName.",
            ]);
        }

        // --- 🚀 BARU: JALUR KHUSUS ADMIN (TANPA OTP) ---
        if ($user->role === 'admin') {
            // Langsung login resmi
            Auth::login($user);
            $request->session()->regenerate();

            // Langsung arahkan ke dashboard admin
            return redirect()->intended('/admin-dashboard');
        }

        // --- MULAI LOGIKA OTP (Hanya untuk Siswa & Ortu) ---

        // E. Generate OTP
        $otp = rand(100000, 999999);

        // F. Simpan OTP ke Database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addSeconds(60)
        ]);

        // G. Kirim Email (Gunakan Try-Catch agar tidak error jika internet putus)
        try {
            Mail::to($user->email)->send(new \App\Mail\OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Cek koneksi SMTP.']);
        }

        // H. Simpan ID sementara di session (Bukan Login permanen)
        session(['temp_user_id' => $user->id]);

        // I. Arahkan ke Halaman Input OTP
        return redirect()->route('otp.verify');
    }

    // --- 3. HALAMAN INPUT OTP (Method yang tadi hilang) ---
   public function showOtpForm()
    {
        if (!session()->has('temp_user_id')) {
            return redirect('/login');
        }

        // Ambil data user untuk tahu kapan expired-nya
        $user = User::find(session('temp_user_id'));

        return view('auth.otp', [
            'email' => $user->email,
            // Kirim waktu expired dalam format Timestamp (detik) agar mudah dibaca JS
            'expired_time' => $user->otp_expires_at ? $user->otp_expires_at->timestamp : 0
        ]);
    }

    // --- BARU: Method Resend OTP ---
    public function resendOtp(Request $request)
    {
        if (!session()->has('temp_user_id')) {
            return redirect('/login');
        }

        $user = User::find(session('temp_user_id'));

        // 1. Generate OTP Baru
        $otp = rand(100000, 999999);
        
        // 2. Update Database (Perpanjang 5 menit lagi)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(1)
        ]);

        // 3. Kirim Email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['otp_code' => 'Gagal mengirim ulang email.']);
        }

        // 4. Kembali ke halaman OTP dengan pesan sukses
        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    // --- 4. VERIFIKASI KODE OTP ---
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|numeric'
        ]);

        // Ambil ID dari session
        $userId = session('temp_user_id');
        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        // Cek 1: Apakah kodenya sama?
        if ($user->otp !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        // Cek 2: Apakah sudah kadaluarsa?
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan login ulang.']);
        }

        // --- SUKSES ---

        // 1. Login Resmi Laravel
        Auth::login($user);

        // 2. Hapus data OTP bekas pakai & session sementara
        $user->update(['otp' => null, 'otp_expires_at' => null]);
        session()->forget('temp_user_id');
        $request->session()->regenerate();

        // 3. Redirect ke Dashboard sesuai Role
        if ($user->role === 'siswa') {
            return redirect()->intended('/dashboard');
        } else {
            return redirect()->intended('/dashboard-ortu');
        }
    }

    // --- 5. HALAMAN REGISTER ---
    public function showRegisterForm()
    {
        return view('register');
    }

    // --- 6. PROSES REGISTER ---
    public function register(Request $request)
    {
        // 1. Validasi Input + Cek Kode Siswa
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:siswa,ortu',
            'kelas' => 'required_if:role,siswa|nullable|integer',
            
            // Aturan 'exists:users,user_code' memastikan kode yang diketik ada di database
            'child_id_code' => 'required_if:role,ortu|nullable|string|exists:users,user_code', 
        ], [
            // Pesan error kustom jika kodenya salah/tidak ditemukan
            'child_id_code.exists' => 'User ID Siswa tidak ditemukan. Pastikan kodenya sudah benar.'
        ]);

        // 2. Logika Generate Kode Siswa (Kode yang kita buat sebelumnya)
        $generatedUserCode = null;
        if ($validated['role'] === 'siswa') {
            $randomNumber = rand(10000, 99999);
            $generatedUserCode = 'SIS-' . $validated['kelas'] . '-' . $randomNumber;
        }

        // 3. Simpan ke Database
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'kelas' => $validated['role'] === 'siswa' ? $validated['kelas'] : null,
            'user_code' => $generatedUserCode, 
            
            // Kolom ini sekarang dijamin valid dan benar-benar terhubung dengan akun siswa!
            'child_id_code' => $validated['role'] === 'ortu' ? $validated['child_id_code'] : null,
        ];

        User::create($userData);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }
    // --- 7. LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}