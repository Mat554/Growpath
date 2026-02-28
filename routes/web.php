<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController; // <--- Pastikan ini ada

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/cek-file', function () {
    $manifestPath = public_path('build/manifest.json');
    $buildPath = public_path('build');
    
    return response()->json([
        '1_status_manifes' => file_exists($manifestPath) ? '✅ MANIFES DITEMUKAN!' : '❌ MANIFES HILANG',
        '2_lokasi_pencarian' => $manifestPath,
        '3_isi_folder_public' => file_exists(public_path()) ? scandir(public_path()) : 'Tidak ada folder public',
        '4_isi_folder_build' => file_exists($buildPath) ? scandir($buildPath) : '❌ Folder build tidak terbawa ke Vercel!'
    ]);
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
    Route::get('/ortu/laporan', [DashboardController::class, 'laporanOrtu'])->name('laporan.ortu');

    Route::get('/exam/{id}', [DashboardController::class, 'takeExam'])->name('exam.take');
    Route::post('/exam/{id}/submit', [DashboardController::class, 'submitExam'])->name('exam.submit');
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/tes',[DashboardController::class, 'tes'])->name('tes');

    Route::post('/admin-dashboard/beta-test', [AdminController::class, 'betaTestPreview'])->name('admin.beta.test');
    Route::post('/admin-dashboard/publish', [AdminController::class, 'publishExam'])->name('admin.publish.exam');
// Rute Dashboard Admin (Sekarang diarahkan ke controller)
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('dashboard.admin');
    Route::post('/admin-dashboard/question', [AdminController::class, 'storeQuestion'])->name('admin.question.store');
    Route::post('/admin-dashboard/question/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('admin.question.toggle');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Untuk Siswa
    Route::get('/dashboard-ortu', [DashboardController::class, 'ortu'])->name('dashboard.ortu'); // Untuk Ortu
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/kuesioner', [DashboardController::class, 'kuesioner'])->name('kuesioner');

    // Existing Admin Routes...
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// NEW: Routes for Monitoring
    Route::get('/admin/monitoring', [AdminController::class, 'monitoringView'])->name('admin.monitoring');
    Route::get('/admin/api/monitoring', [AdminController::class, 'getMonitoringData'])->name('admin.api.monitoring');


    // Rute untuk Orang Tua
    Route::get('/profile-ortu', [DashboardController::class, 'profileOrtu'])->name('profile.ortu');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});