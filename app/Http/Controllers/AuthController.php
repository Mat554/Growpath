<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
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



   public function login(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:siswa,ortu,admin'], 
        ]);

        // B. Cari User
        $user = \App\Models\User::where('email', $request->email)->first();
        // dd('SUPABASE RESPONSE:', $user);

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

        // --- LOGIN ADMIN (Tanpa OTP) ---
        if ($user->role === 'admin') {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/admin-dashboard');
        }

        // =========================================================
        // TAMBAHAN BARU: CEK KARCIS BEBAS OTP (Untuk Siswa & Ortu)
        // =========================================================
        if ($request->hasCookie('tiket_bebas_otp')) {
            // Langsung login resmi
            Auth::login($user);
            $request->session()->regenerate();

            // Perpanjang karcis 5 menit lagi
            $karcisBebasOtp = cookie('tiket_bebas_otp', 'terverifikasi', 5);

            // Redirect sesuai role dengan membawa karcis baru
            if ($user->role === 'siswa') {
                return redirect()->intended('/dashboard')->withCookie($karcisBebasOtp);
            } else {
                return redirect()->intended('/dashboard-ortu')->withCookie($karcisBebasOtp);
            }
        }
        // =========================================================

        // --- MULAI LOGIKA OTP (Jika tidak punya karcis / karcis hangus) ---

        // E. Generate OTP
        $otp = rand(100000, 999999);

        // F. Simpan OTP ke Database
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => \Carbon\Carbon::now()->addSeconds(60) // Catatan: Ini expired dalam 1 menit
        ]);

        // G. Kirim Email
        try {
            Mail::to($user->email)->send(new \App\Mail\OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Cek koneksi SMTP.']);
        }

        // H. Simpan ID sementara di session
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
        if (\Carbon\Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan login ulang.']);
        }

        // --- SUKSES ---

        // 1. Login Resmi Laravel
        Auth::login($user);

        // 2. Hapus data OTP bekas pakai & session sementara
        $user->update(['otp' => null, 'otp_expires_at' => null]);
        session()->forget('temp_user_id');
        $request->session()->regenerate();

        // === TAMBAHAN BARU: CETAK KARCIS BEBAS OTP ===
        // Membuat cookie 'tiket_bebas_otp' yang berlaku selama 5 menit
        $karcisBebasOtp = cookie('tiket_bebas_otp', 'terverifikasi', 120);
        // =============================================

        // 3. Redirect ke Dashboard sesuai Role Sambil Membawa Karcis
        if ($user->role === 'siswa') {
            return redirect()->intended('/dashboard')->withCookie($karcisBebasOtp);
        } else {
            return redirect()->intended('/dashboard-ortu')->withCookie($karcisBebasOtp);
        }
    }

    
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

    // ==========================================
    // ALUR LUPA PASSWORD (RESET PASSWORD)
    // ==========================================

    // 1. Tampilkan halaman input email
    public function showForgotPasswordForm()
    {
        return view('auth.forget-password');
    }

    // 2. Kirim OTP ke email tersebut
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak ditemukan di sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5) 
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Coba lagi nanti.']);
        }

        session(['reset_email' => $user->email]);

        return redirect()->route('password.otp');
    }

    public function showResetOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-otp', ['email' => session('reset_email')]);
    }

   public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric']);

        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');

        $user = \App\Models\User::where('email', $email)->first();

        if ($user->otp !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        if (\Carbon\Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan minta ulang.']);
        }

        // --- KUNCI PERBAIKANNYA DI SINI ---
        // OTP Benar! Simpan ID user ke session (bukan sekadar status 'true')
        session(['temp_user_id' => $user->id]);
        
        // Hapus OTP agar tidak bisa dipakai 2x
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        return redirect()->route('password.reset');
    }

    // 5. Tampilkan halaman pembuatan password baru
    public function showResetPasswordForm(Request $request)
    {
        $userId = session('temp_user_id'); 

        // Jika tidak ada session, kembalikan ke login
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi OTP tidak valid atau kedaluwarsa.']);
        }

        // Lempar variabel userId ke halaman Blade
        return view('auth.reset-password', compact('userId'));
    }

   public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
            'user_id' => ['required'] // Pastikan user_id dari form tersembunyi terbawa
        ], [
            // ... (pesan error tetap sama)
        ]);

        // AMBIL DATA DARI INPUT TERSEMBUNYI (BUKAN DARI SESSION)
        $userId = $request->user_id; 
            
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Simpan paksa Password Baru ke Database
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Bersihkan session yang tersisa (opsional)
        session()->forget('temp_user_id');

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
    }

    // FUNGSI KHUSUS UBAH PASSWORD DARI PROFIL
    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'
            ]
        ], [
            'password.min' => 'Password terlalu pendek, minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Ambil data user secara paksa dari database
        $user = \App\Models\User::find(Auth::id());

        // Jika koneksi putus
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi terputus saat menyimpan. Silakan login kembali.']);
        }

        // Simpan paksa ke Supabase
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui!');
    }
}