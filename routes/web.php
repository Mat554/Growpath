<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // <--- Pastikan ini ada

Route::get('/', function () {
    return view('welcome');
});



// 1. LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Rute untuk menampilkan halaman Login Admin
Route::get('/admin', function () {
    return view('admin.admin-login'); 
})->name('admin.login')->middleware('guest');

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





Route::middleware(['auth'])->group(function () {



    Route::get('/admin-dashboard', [DashboardController::class, 'dashboardAdmin'])->name('dashboard.admin'); // Untuk Admin


    
      Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Untuk Siswa
    Route::get('/dashboard-ortu', [DashboardController::class, 'ortu'])->name('dashboard.ortu'); // Untuk Ortu
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');


    // Rute untuk Orang Tua
Route::get('/profile-ortu', [DashboardController::class, 'profileOrtu'])->name('profile.ortu');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});