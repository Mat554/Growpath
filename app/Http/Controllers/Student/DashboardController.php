<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Helpers\ViewHelper;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show student dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil daftar ID ujian yang SUDAH dikerjakan siswa ini
        $completedExamIds = ExamResult::where('user_id', $user->id)
                                ->pluck('exam_id')
                                ->toArray();

        // 2. Ambil SEMUA ujian yang belum dikerjakan untuk kelas ini
        $pendingExams = Exam::where('target_class', $user->kelas)
                    ->whereNotIn('id', $completedExamIds)
                    ->get();

        // 3. Pisahkan menjadi 3 kelompok dan urutkan dengan cerdas
        $today = Carbon::now()->startOfDay();

        // a. Aktif Hari Ini (Kasta Tertinggi)
        $activeExams = $pendingExams->filter(function($exam) use ($today) {
            return Carbon::parse($exam->exam_date)->startOfDay()->equalTo($today);
        });

        // b. Mendatang (Kasta Menengah - Diurutkan dari jadwal yang paling DEKAT)
        $upcomingExams = $pendingExams->filter(function($exam) use ($today) {
            return Carbon::parse($exam->exam_date)->startOfDay()->greaterThan($today);
        })->sortBy('exam_date');

        // c. Overdue (Kasta Terendah - Diurutkan dari yang PALING BARU terlewat)
        $overdueExams = $pendingExams->filter(function($exam) use ($today) {
            return Carbon::parse($exam->exam_date)->startOfDay()->lessThan($today);
        })->sortByDesc('exam_date');

        // 4. Gabungkan sesuai kasta
        $sortedPending = $activeExams->concat($upcomingExams)->concat($overdueExams);

        // 5. Ambil 1 kuesioner teratas
        $nextExam = $sortedPending->first();

        // 6. Bungkus hasilnya ke dalam koleksi
        $exams = $nextExam ? collect([$nextExam]) : collect();

        // 7. Ambil data ujian yang selesai
        $completedExams = ExamResult::where('user_id', Auth::id())->get();

        // 8. Ambil data koneksi orang tua
        $pendingParents = Auth::user()->parents()->where('child_connection_status', 'pending')->get();
        $connectedParents = Auth::user()->parents()->where('child_connection_status', 'approved')->get();

        $viewName = ViewHelper::resolveView('dashboard');
        return view($viewName, compact('exams', 'completedExams', 'completedExamIds', 'connectedParents', 'pendingParents'));
    }

    /**
     * Show study tips page
     */
    public function tipsbelajar()
    {
        $viewName = ViewHelper::resolveView('tipsbelajar');
        return view($viewName);
    }
}