<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Helpers\ViewHelper;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Show exam list (kuesioner)
     */
    public function kuesioner()
    {
        $user = Auth::user();

        // Ambil daftar ID ujian yang sudah dikerjakan
        $completedExamIds = ExamResult::where('user_id', $user->id)
                                ->pluck('exam_id')
                                ->toArray();

        // Ambil SEMUA ujian untuk kelas ini (termasuk yang sudah dikerjakan)
        $allExams = Exam::where('target_class', $user->kelas)
                     ->get();

        // Get completed exams for status display
        $completedExams = ExamResult::where('user_id', $user->id)
                                ->get()
                                ->keyBy('exam_id');

        // Untuk backward compatibility dengan view lama
        $exams = $allExams->filter(function($exam) use ($completedExamIds) {
            return !in_array($exam->id, $completedExamIds);
        });

        $viewName = ViewHelper::resolveView('kuesioner');
        return view($viewName, compact('allExams', 'exams', 'completedExams'));
    }

    /**
     * Start exam
     */
    public function takeExam($id)
    {
        $alreadyTaken = ExamResult::where('user_id', Auth::id())
                            ->where('exam_id', $id)
                            ->exists();

        if ($alreadyTaken) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah menyelesaikan kuesioner ini.');
        }

        $exam = Exam::with('questions')->findOrFail($id);

        return view('tes', [
            'exam' => $exam,
            'questions' => $exam->questions,
            'is_beta' => false,
            'duration' => $exam->duration_minutes
        ]);
    }

    /**
     * Submit exam
     */
    public function submitExam(Request $request, $id)
    {
        $scores = $request->scores;

        ExamResult::create([
            'user_id' => Auth::id(),
            'exam_id' => $id,
            'score_r' => $scores['R'],
            'score_i' => $scores['I'],
            'score_a' => $scores['A'],
            'score_s' => $scores['S'],
            'score_e' => $scores['E'],
            'score_c' => $scores['C'],
            'dominant_code' => $request->dominant_code,
            'status' => 'published',
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
    }

    /**
     * Mark exam popup as shown (called via AJAX when popup is closed)
     */
    public function dismissExamPopup(Request $request, $id)
    {
        session()->put('exam_popup_shown_for_' . $id, true);

        return response()->json(['success' => true]);
    }

    /**
     * Get latest valid exam for direct start
     */
    public function getLatestExam()
    {
        $user = Auth::user();

        // Ambil daftar ID ujian yang sudah dikerjakan
        $completedExamIds = ExamResult::where('user_id', $user->id)
                                ->pluck('exam_id')
                                ->toArray();

        // Ambil test valid terbaru yang belum dikerjakan
        $today = Carbon::now()->startOfDay();
        $latestExam = Exam::where('target_class', $user->kelas)
                    ->whereNotIn('id', $completedExamIds)
                    ->get()
                    ->filter(function($exam) use ($today) {
                        $endDate = $exam->exam_end_date
                            ? Carbon::parse($exam->exam_end_date)->startOfDay()
                            : Carbon::parse($exam->exam_date)->startOfDay();
                        return $endDate->greaterThanOrEqualTo($today);
                    })
                    ->sortByDesc('exam_date')
                    ->first();

        if ($latestExam) {
            // Tandai popup sudah ditampilkan
            session()->put('exam_popup_shown_for_' . $latestExam->id, true);

            return response()->json([
                'success' => true,
                'redirect_url' => route('exam.take', $latestExam->id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada test yang tersedia'
        ]);
    }
}