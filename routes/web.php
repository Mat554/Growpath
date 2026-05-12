<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

// =========================================================
// 1. RUTE AWAL & UMUM
// =========================================================
Route::get('/', function () {
    return redirect('/login');
});

// Proses Logout (Bisa diakses dari mana saja asal login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Verifikasi OTP (Biasanya berada di area abu-abu antara login dan guest)
Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
Route::get('/otp-verification', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verification', [AuthController::class, 'verifyOtp'])->name('otp.check');


// =========================================================
// 2. RUTE GUEST (HANYA UNTUK YANG BELUM LOGIN)
// =========================================================
Route::middleware(['guest'])->group(function () {
    
    // Login & Register Siswa
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    // Login Admin
    Route::get('/admin', function () {
        return view('admin.admin-login'); 
    })->name('admin.login');

    // Alur Lupa Password (OTP)
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('/forgot-password/otp', [AuthController::class, 'showResetOtpForm'])->name('password.otp');
    Route::post('/forgot-password/otp', [AuthController::class, 'verifyResetOtp'])->name('password.otp.verify');
    Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

});


// =========================================================
// 3. RUTE TERPROTEKSI (WAJIB LOGIN)
// =========================================================
Route::middleware(['auth'])->group(function () {
    
    // -----------------------------------------------------
    // A. AREA ADMIN
    // -----------------------------------------------------
    Route::get('/admin-dashboard', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');
    Route::get('/admin/laporan/{id}', [DashboardController::class, 'laporanAdmin'])->name('admin.laporan.view');
    Route::post('/admin/laporan/{id}/publish', [DashboardController::class, 'publishLaporan'])->name('admin.laporan.publish');
    
    Route::post('/admin-dashboard/question', [AdminController::class, 'storeQuestion'])->name('admin.question.store');
    Route::post('/admin-dashboard/question/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.question.toggle');
    Route::post('/admin-dashboard/beta-test', [AdminController::class, 'betaTestPreview'])->name('admin.beta.test');
    Route::post('/admin-dashboard/publish', [AdminController::class, 'publishExam'])->name('admin.publish.exam');
    
    Route::get('/admin/monitoring', [AdminController::class, 'monitoringView'])->name('admin.monitoring');
    Route::get('/admin/api/monitoring', [AdminController::class, 'getMonitoringData'])->name('admin.api.monitoring');

    // -----------------------------------------------------
    // B. AREA SISWA
    // -----------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/kuesioner', [DashboardController::class, 'kuesioner'])->name('kuesioner');
    Route::post('/koneksi/revoke/{id}', [DashboardController::class, 'revokeKoneksi'])->name('koneksi.revoke');
    Route::post('/koneksi/approve/{id}', [DashboardController::class, 'approveKoneksi'])->name('koneksi.approve');
    Route::post('/koneksi/reject/{id}', [DashboardController::class, 'rejectKoneksi'])->name('koneksi.reject');
    
    Route::get('/exam/{id}', [DashboardController::class, 'takeExam'])->name('exam.take');
    Route::post('/exam/{id}/submit', [DashboardController::class, 'submitExam'])->name('exam.submit');
    
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/tes', [DashboardController::class, 'tes'])->name('tes');
    Route::post('/profile/avatar', [DashboardController::class, 'updateAvatar'])->name('profile.update.avatar');

    // -----------------------------------------------------
    // C. AREA ORANG TUA
    // -----------------------------------------------------
    Route::get('/dashboard-ortu', [DashboardController::class, 'ortu'])->name('dashboard.ortu');
    Route::get('/profile-ortu', [DashboardController::class, 'profileOrtu'])->name('profile.ortu');
    Route::get('/ortu/laporan', [DashboardController::class, 'laporanOrtu'])->name('laporan.ortu');
    Route::post('/koneksi/revoke-ortu', [DashboardController::class, 'revokeKoneksiOrtu'])->name('koneksi.revoke.ortu');
    Route::post('/koneksi/connect-ortu', [DashboardController::class, 'connectKoneksiOrtu'])->name('koneksi.connect.ortu');
    Route::post('/profile/ortu/update-avatar', [DashboardController::class, 'updateAvatarOrtu'])->name('profile.ortu.update-avatar');

    // -----------------------------------------------------
    // D. KEAMANAN & PENGATURAN AKUN (Sudah Login)
    // -----------------------------------------------------
    Route::get('/profil/ubah-password', function () {
        // Mengirimkan penanda isProfileUpdate agar Form mengarah ke rute yang benar
        return view('auth.reset-password', ['isProfileUpdate' => true]); 
    })->name('profile.ubah-password');

    // Pastikan ini memanggil 'updateProfilePassword' agar tidak bertabrakan dengan reset password tamu
    Route::post('/profil/ubah-password/simpan', [AuthController::class, 'updateProfilePassword'])->name('profile.password.update');
    
});