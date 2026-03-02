<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController; // <--- Pastikan ini ada

Route::get('/', function () {
    return redirect('/login');
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
    
    // ==========================================
    // 1. RUTE ADMIN
    // ==========================================
    // Menghapus duplikasi, sekarang hanya memanggil DashboardController
    Route::get('/admin-dashboard', [DashboardController::class, 'dashboardAdmin'])->name('admin.dashboard');
    
    // Rute Validasi & Laporan Admin
    Route::get('/admin/laporan/{id}', [DashboardController::class, 'laporanAdmin'])->name('admin.laporan.view');
    Route::post('/admin/laporan/{id}/publish', [DashboardController::class, 'publishLaporan'])->name('admin.laporan.publish');
    
    // Rute Soal & Beta Test (Memakai AdminController)
    Route::post('/admin-dashboard/question', [AdminController::class, 'storeQuestion'])->name('admin.question.store');
    Route::post('/admin-dashboard/question/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.question.toggle');
    Route::post('/admin-dashboard/beta-test', [AdminController::class, 'betaTestPreview'])->name('admin.beta.test');
    Route::post('/admin-dashboard/publish', [AdminController::class, 'publishExam'])->name('admin.publish.exam');
    
    // Rute Monitoring
    Route::get('/admin/monitoring', [AdminController::class, 'monitoringView'])->name('admin.monitoring');
    Route::get('/admin/api/monitoring', [AdminController::class, 'getMonitoringData'])->name('admin.api.monitoring');

    // ==========================================
    // 2. RUTE SISWA
    // ==========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/kuesioner', [DashboardController::class, 'kuesioner'])->name('kuesioner');
    Route::get('/exam/{id}', [DashboardController::class, 'takeExam'])->name('exam.take');
    Route::post('/exam/{id}/submit', [DashboardController::class, 'submitExam'])->name('exam.submit');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/tes', [DashboardController::class, 'tes'])->name('tes');

    // ==========================================
    // 3. RUTE ORANG TUA
    // ==========================================
    Route::get('/dashboard-ortu', [DashboardController::class, 'ortu'])->name('dashboard.ortu');
    Route::get('/profile-ortu', [DashboardController::class, 'profileOrtu'])->name('profile.ortu');
    Route::get('/ortu/laporan', [DashboardController::class, 'laporanOrtu'])->name('laporan.ortu');

});