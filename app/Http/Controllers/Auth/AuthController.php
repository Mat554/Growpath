<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // =========================================================
    // 1. LOGIN & OTP FLOW
    // =========================================================

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Process login request
     */
    public function login(Request $request)
    {
        // A. Validasi Input
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:siswa,ortu,admin'],
        ]);

        // B. Cari User
        $user = User::where('email', $request->email)->first();

        // C. Cek Password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        // D. Cek Kesesuaian Role
        if ($user->role !== $request->role) {
            $roleName = ucfirst($user->role);
            return back()->withErrors([
                'email' => "Akun ini terdaftar sebagai $roleName. Silakan pindah ke tab $roleName.",
            ])->withInput();
        }

        // --- LOGIN ADMIN (Tanpa OTP) ---
        if ($user->role === 'admin') {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/admin-dashboard');
        }

        // =========================================================
        // OTP FLOW (Untuk Siswa & Ortu)
        // =========================================================

        // E. Generate OTP
        $otp = rand(100000, 999999);

        // F. Simpan OTP ke Database (10 minutes expiry)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
            'otp_resend_count' => 0
        ]);

        // G. Kirim Email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Cek koneksi SMTP.']);
        }

        // H. Simpan ID sementara di session
        session(['temp_user_id' => $user->id]);

        // I. Arahkan ke Halaman Input OTP
        return redirect()->route('otp.verify');
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        if (!session()->has('temp_user_id')) {
            return redirect('/login');
        }

        $user = User::find(session('temp_user_id'));

        if (!$user) {
            return redirect('/login');
        }

        return view('auth.otp', [
            'email' => $user->email,
            'expired_time' => $user->otp_expires_at ? $user->otp_expires_at->timestamp : 0
        ]);
    }

    /**
     * Resend OTP (Login)
     * - Reuses same OTP if still valid (< 10 minutes)
     * - Max 3 retries, then must wait for OTP to expire
     */
    public function resendOtp(Request $request)
    {
        if (!session()->has('temp_user_id')) {
            return response()->json([
                'success' => false,
                'error' => 'Sesi tidak valid. Silakan login ulang.'
            ], 400);
        }

        $user = User::find(session('temp_user_id'));

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Sesi tidak valid. Silakan login ulang.'
            ], 400);
        }

        // Check if OTP is still valid and resend count
        $isOtpValid = $user->otp && Carbon::now()->lessThan($user->otp_expires_at);
        $resendCount = $user->otp_resend_count ?? 0;

        // If OTP is expired OR user exceeded 3 retries, generate new OTP
        if (!$isOtpValid || $resendCount >= 3) {
            // Generate new OTP
            $otp = rand(100000, 999999);

            // Reset resend count when generating new OTP
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
                'otp_resend_count' => 0
            ]);

            // Send email
            try {
                Mail::to($user->email)->send(new OTPMail($otp));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal mengirim email OTP. Coba lagi nanti.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke email Anda.',
                'expires_in' => 600,
                'remaining_resends' => 3
            ]);
        }

        // OTP is still valid - reuse same OTP and increment count
        $newResendCount = $resendCount + 1;
        $remainingTime = Carbon::now()->diffInSeconds($user->otp_expires_at);

        // Send email with same OTP FIRST - if fails, don't count as a try
        try {
            Mail::to($user->email)->send(new OTPMail($user->otp));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengirim email OTP. Coba lagi nanti.'
            ], 500);
        }

        // Only increment count AFTER successful send
        $user->update([
            'otp_resend_count' => $newResendCount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ulang ke email Anda.',
            'remaining_resends' => max(0, 3 - $newResendCount),
            'expires_in' => $remainingTime
        ]);
    }

    /**
     * Verify OTP code
     */
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

        if (!$user) {
            return redirect('/login');
        }

        // Cek 1: Apakah kodenya sama?
        if ($user->otp !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        // Cek 2: Apakah sudah kadaluarsa?
        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan login ulang.']);
        }

        // --- SUKSES ---

        // 1. Regenerate session BEFORE login (security fix)
        $request->session()->regenerate();

        // 2. Login Resmi Laravel
        Auth::login($user);

        // 3. Hapus data OTP bekas pakai & session sementara
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'otp_resend_count' => 0
        ]);
        session()->forget('temp_user_id');

        // 4. Set OTP bypass cookie (30 minutes - standardized)
        $karcisBebasOtp = cookie('tiket_bebas_otp', 'terverifikasi', 30);

        // 5. Redirect ke Dashboard sesuai Role
        if ($user->role === 'siswa') {
            return redirect()->intended('/dashboard')->withCookie($karcisBebasOtp);
        } else {
            return redirect()->intended('/dashboard-ortu')->withCookie($karcisBebasOtp);
        }
    }

    /**
     * Process logout
     */
    public function logout(Request $request)
    {
        // Clear all cache before logout
        $this->clearAllUserCache();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Clear all user-related cache
     */
    protected function clearAllUserCache(): void
    {
        // Clear Laravel cache
        \Illuminate\Support\Facades\Cache::flush();

        // Clear session data
        session()->flush();

        // Clear system caches
        if (function_exists('exec')) {
            exec('php ' . base_path('artisan') . ' config:clear 2>/dev/null >/dev/null &');
            exec('php ' . base_path('artisan') . ' view:clear 2>/dev/null >/dev/null &');
        }
    }
}
