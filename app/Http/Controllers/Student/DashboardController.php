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

        // 3. Filter hanya test yang masih VALID (belum expired)
        $today = Carbon::now()->startOfDay();
        $validExams = $pendingExams->filter(function($exam) use ($today) {
            $endDate = $exam->exam_end_date ? Carbon::parse($exam->exam_end_date)->startOfDay() : Carbon::parse($exam->exam_date)->startOfDay();
            return $endDate->greaterThanOrEqualTo($today);
        });

        // 4. Urutkan: Aktif Hari Ini dulu, kemudian upcoming
        $activeToday = $validExams->filter(function($exam) use ($today) {
            return Carbon::parse($exam->exam_date)->startOfDay()->equalTo($today);
        });

        $upcoming = $validExams->filter(function($exam) use ($today) {
            return Carbon::parse($exam->exam_date)->startOfDay()->greaterThan($today);
        })->sortBy('exam_date');

        $sortedPending = $activeToday->concat($upcoming);

        // 5. Ambil 1 test teratas
        $nextExam = $sortedPending->first();

        // 6. Bungkus hasilnya ke dalam koleksi
        $exams = $nextExam ? collect([$nextExam]) : collect();

        // 7. Ambil data ujian yang selesai
        $completedExams = ExamResult::where('user_id', Auth::id())->get();

        // 8. Ambil data koneksi orang tua
        $pendingParents = Auth::user()->parents()->where('child_connection_status', 'pending')->get();
        $connectedParents = Auth::user()->parents()->where('child_connection_status', 'approved')->get();

        // 9. Hitung test VALID terbaru yang belum dikerjakan (hanya yang belum expired)
        $newExamCount = $validExams->count();
        $latestExam = $validExams->sortByDesc('exam_date')->first();

        // 10. Cek apakah popup sudah pernah ditampilkan di session ini
        $showExamPopup = false;
        if ($latestExam && !session()->has('exam_popup_shown_for_' . $latestExam->id)) {
            $showExamPopup = true;
        }

        $viewName = ViewHelper::resolveView('dashboard');
        return view($viewName, compact(
            'exams', 'completedExams', 'completedExamIds',
            'connectedParents', 'pendingParents', 'newExamCount', 'latestExam', 'showExamPopup'
        ));
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