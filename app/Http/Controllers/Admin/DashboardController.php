<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $totalSiswa = User::where('role', 'siswa')->count();
        $totalSoal = Question::count(); // Bank soal
        $totalLaporan = ExamResult::count();

        $pendingReports = ExamResult::with('user')->where('status', 'review')->get();
        $questions = Question::all();

        // === Rata-rata Skor RIASEC ===
        $avgScores = ExamResult::selectRaw('
            AVG(score_r) as avg_r,
            AVG(score_i) as avg_i,
            AVG(score_a) as avg_a,
            AVG(score_s) as avg_s,
            AVG(score_e) as avg_e,
            AVG(score_c) as avg_c
        ')->first();

        $totalAnswers = $avgScores->avg_r + $avgScores->avg_i + $avgScores->avg_a
                     + $avgScores->avg_s + $avgScores->avg_e + $avgScores->avg_c;

        $riasecAvg = [];
        foreach (['R','I','A','S','E','C'] as $key) {
            $avg = $avgScores->{'avg_'.strtolower($key)} ?? 0;
            $riasecAvg[$key] = $totalAnswers > 0 ? round(($avg / $totalAnswers) * 100, 1) : 0;
        }

        // === Distribusi Kode Dominan ===
        $codeDistribution = ExamResult::select('dominant_code', DB::raw('count(*) as total'))
            ->whereNotNull('dominant_code')
            ->groupBy('dominant_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // === Distribusi Per Kelas ===
        $classDistribution = User::where('role', 'siswa')
            ->select('kelas', DB::raw('count(*) as total'))
            ->whereNotNull('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        // === Recent Exam Results (last 5) ===
        $recentResults = ExamResult::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // === Active Exam Count ===
        $activeExamCount = Exam::where('exam_date', '>=', now()->startOfDay())->count();

        // === Published vs Review status ===
        $publishedCount = ExamResult::where('status', 'published')->count();
        $reviewCount = ExamResult::where('status', 'review')->count();

        return view('admin.admin-dashboard', compact(
            'totalSiswa', 'totalSoal', 'totalLaporan', 'pendingReports',
            'questions', 'riasecAvg', 'codeDistribution', 'classDistribution',
            'recentResults', 'activeExamCount', 'publishedCount', 'reviewCount'
        ));
    }
}