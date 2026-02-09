<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // <--- Pastikan ini ada

Route::get('/', function () {
    return view('welcome');
});

// 1. LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']); 

// 2. REGISTER (Ini yang menyebabkan error Anda)
// Pastikan ->name('register') ada di sini!
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// 3. LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. OTP VERIFICATION
Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::get('/otp-verification', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verification', [AuthController::class, 'verifyOtp'])->name('otp.check');

// 4. DASHBOARD (Contoh setelah login)
Route::get('/dashboard', function () {
    return "Halo Siswa! (Halaman Dashboard)";
})->middleware('auth');

Route::get('/dashboard-ortu', function () {
    return "Halo Orang Tua! (Halaman Dashboard)";
})->middleware('auth');