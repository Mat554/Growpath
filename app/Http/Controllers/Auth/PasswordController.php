<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    // ==========================================
    // ALUR LUPA PASSWORD (RESET PASSWORD)
    // ==========================================

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forget-password');
    }

    /**
     * Send OTP for password reset
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak ditemukan di sistem kami.'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save to database (5 minutes expiry - standardized)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        // Send email
        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Coba lagi nanti.']);
        }

        // Store email in session for verification
        session(['reset_email' => $user->email]);

        return redirect()->route('password.otp');
    }

    /**
     * Show reset OTP form
     */
    public function showResetOtpForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-otp', compact('email'));
    }

    /**
     * Verify reset OTP
     */
    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric']);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request');
        }

        if ($user->otp !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kadaluarsa. Silakan minta ulang.']);
        }

        // OTP Benar! Simpan ID user ke session
        session(['temp_user_id' => $user->id]);

        // Hapus OTP agar tidak bisa dipakai 2x
        $user->update(['otp' => null, 'otp_expires_at' => null]);

        return redirect()->route('password.reset');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        $userId = session('temp_user_id');

        // Jika tidak ada session, kembalikan ke login
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi OTP tidak valid atau kedaluwarsa.']);
        }

        // Check if this is profile password update
        $isProfileUpdate = $request->query('profile') === '1';

        return view('auth.reset-password', compact('userId', 'isProfileUpdate'));
    }

    /**
     * Update password (guest - from email link)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'confirmed'],
            'user_id' => ['required', 'integer']
        ]);

        $userId = $request->user_id;
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Pengguna tidak ditemukan.']);
        }

        // Save new password
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Clean up session
        session()->forget('temp_user_id');
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login.');
    }

    // ==========================================
    // PROFILE PASSWORD UPDATE (Logged in users)
    // ==========================================

    /**
     * Show profile password change form
     */
    public function showProfilePasswordForm()
    {
        return view('auth.reset-password', ['isProfileUpdate' => true]);
    }

    /**
     * Update profile password (with current password verification)
     */
    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'min:8',
                'regex:/[A-Z]/',  // At least 1 uppercase
                'regex:/[0-9]/',  // At least 1 number
                'confirmed'
            ]
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min' => 'Password terlalu pendek, minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf besar dan 1 angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Get current user
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi terputus. Silakan login kembali.']);
        }

        // Verify current password
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Update password
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        // Redirect based on role
        if ($user->role === 'ortu') {
            return redirect()->route('profile.ortu')->with('success', 'Password berhasil diperbarui!');
        }

        return redirect()->route('profile')->with('success', 'Password berhasil diperbarui!');
    }
}