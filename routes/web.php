<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\ReportController as StudentReport;
use App\Http\Controllers\Student\ProfileController as StudentProfile;
use App\Http\Controllers\Parent\DashboardController as ParentDashboard;
use App\Http\Controllers\Parent\ReportController as ParentReport;
use App\Http\Controllers\Parent\ProfileController as ParentProfile;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ExamController as AdminExam;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\MonitoringController;
use App\Helpers\ViewHelper;

// =========================================================
// 1. RUTE AWAL & UMUM
// =========================================================
Route::get('/', function () {
    return redirect('/login');
});

// Proses Logout (Bisa diakses dari mana saja asal login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/tips', [StudentDashboard::class, 'tipsbelajar'])->name('tips');

// Mobile View Toggle Routes (available for all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/mobile/toggle', function () {
        $enabled = ViewHelper::toggleMobileView();
        return redirect()->back()->with('mobile_view', $enabled);
    })->name('mobile.toggle');

    Route::get('/mobile/enable', function () {
        ViewHelper::toggleMobileView(true);
        return redirect()->back();
    })->name('mobile.enable');

    Route::get('/mobile/disable', function () {
        ViewHelper::toggleMobileView(false);
        return redirect()->back();
    })->name('mobile.disable');
});

// Verifikasi OTP (Dengan rate limiting)
Route::middleware(['throttle.otp'])->group(function () {
    Route::post('/otp-resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});
Route::get('/otp-verification', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp-verification', [AuthController::class, 'verifyOtp'])->name('otp.check');

// =========================================================
// 2. RUTE GUEST (HANYA UNTUK YANG BELUM LOGIN)
// =========================================================
Route::middleware(['guest'])->group(function () {

    // Login & Register
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

    // Login Admin
    Route::get('/admin', function () {
        return view('admin.admin-login');
    })->name('admin.login');

    // Alur Lupa Password (OTP)
    Route::get('/forgot-password', [PasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetOtp'])->name('password.email');
    Route::get('/forgot-password/otp', [PasswordController::class, 'showResetOtpForm'])->name('password.otp');
    Route::post('/forgot-password/otp', [PasswordController::class, 'verifyResetOtp'])->name('password.otp.verify');
    Route::get('/reset-password', [PasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'updatePassword'])->name('password.update');
});


// =========================================================
// 3. RUTE TERPROTEKSI (WAJIB LOGIN)
// =========================================================
Route::middleware(['auth'])->group(function () {

    // -----------------------------------------------------
    // A. AREA ADMIN (Dengan middleware admin)
    // -----------------------------------------------------
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin-dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/laporan/{id}', [AdminReport::class, 'view'])->name('admin.laporan.view');
        Route::post('/admin/laporan/{id}/publish', [AdminReport::class, 'publish'])->name('admin.laporan.publish');

        Route::post('/admin-dashboard/question', [QuestionController::class, 'store'])->name('admin.question.store');
        Route::post('/admin-dashboard/question/{id}/toggle', [QuestionController::class, 'toggleStatus'])->name('admin.question.toggle');
        Route::post('/admin-dashboard/question/{id}/remove', [QuestionController::class, 'removeFromActive'])->name('admin.question.remove');
        Route::post('/admin-dashboard/question/{id}/delete', [QuestionController::class, 'destroy'])->name('admin.question.destroy');
        Route::post('/admin-dashboard/question/{id}/class', [QuestionController::class, 'updateClass'])->name('admin.question.updateClass');
        Route::get('/admin/beta-test-preview', [AdminExam::class, 'betaTestPreview'])->name('admin.beta.preview');
        Route::post('/admin/beta-store-results', [AdminExam::class, 'storeBetaResults'])->name('admin.beta.store');
        Route::get('/admin/beta-report-preview', [AdminExam::class, 'betaReportPreview'])->name('admin.beta.report');
        Route::post('/admin-dashboard/beta-test', [AdminExam::class, 'betaTest'])->name('admin.beta.test');
        Route::post('/admin-dashboard/publish', [AdminExam::class, 'publish'])->name('admin.publish.exam');

        Route::get('/admin/monitoring', [MonitoringController::class, 'index'])->name('admin.monitoring');
        Route::get('/admin/api/monitoring', [MonitoringController::class, 'data'])->name('admin.api.monitoring');
    });

    // -----------------------------------------------------
    // B. AREA SISWA (Dengan middleware siswa)
    // -----------------------------------------------------
    Route::middleware(['siswa'])->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
        Route::get('/profile', [StudentProfile::class, 'show'])->name('profile');
        Route::get('/kuesioner', [ExamController::class, 'kuesioner'])->name('kuesioner');

        Route::get('/exam/{id}', [ExamController::class, 'takeExam'])->name('exam.take');
        Route::post('/exam/{id}/submit', [ExamController::class, 'submitExam'])->name('exam.submit');

        Route::get('/laporan', [StudentReport::class, 'show'])->name('laporan');
        Route::get('/tes', [StudentDashboard::class, 'tipsbelajar'])->name('tes');

        // Koneksi Orang Tua
        Route::post('/koneksi/revoke/{id}', [StudentProfile::class, 'revokeKoneksi'])->name('koneksi.revoke');
        Route::post('/koneksi/approve/{id}', [StudentProfile::class, 'approveKoneksi'])->name('koneksi.approve');
        Route::post('/koneksi/reject/{id}', [StudentProfile::class, 'rejectKoneksi'])->name('koneksi.reject');

        // Avatar
        Route::post('/profile/avatar', [StudentProfile::class, 'updateAvatar'])->name('profile.update.avatar');
    });

    // -----------------------------------------------------
    // C. AREA ORANG TUA (Dengan middleware ortu)
    // -----------------------------------------------------
    Route::middleware(['ortu'])->group(function () {
        Route::get('/dashboard-ortu', [ParentDashboard::class, 'index'])->name('dashboard.ortu');
        Route::get('/profile-ortu', [ParentProfile::class, 'show'])->name('profile.ortu');
        Route::get('/ortu/laporan', [ParentReport::class, 'show'])->name('laporan.ortu');

        Route::post('/koneksi/revoke-ortu', [ParentProfile::class, 'revokeKoneksi'])->name('koneksi.revoke.ortu');
        Route::post('/koneksi/connect-ortu', [ParentProfile::class, 'connectKoneksi'])->name('koneksi.connect.ortu');

        Route::post('/profile/ortu/update-avatar', [ParentProfile::class, 'updateAvatar'])->name('profile.ortu.update-avatar');
    });

    // -----------------------------------------------------
    // D. KEAMANAN & PENGATURAN AKUN (Sudah Login - semua role)
    // -----------------------------------------------------
    Route::get('/profil/ubah-password', [PasswordController::class, 'showProfilePasswordForm'])->name('profile.ubah-password');
    Route::post('/profil/ubah-password/simpan', [PasswordController::class, 'updateProfilePassword'])->name('profile.password.update');
});