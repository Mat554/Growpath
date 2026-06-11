<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Helpers\ViewHelper;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Show exam list (kuesioner)
     */
    public function kuesioner()
    {
        $user = Auth::user();

        $completedExams = ExamResult::where('user_id', $user->id)
                                ->get()
                                ->keyBy('exam_id');

        $exams = Exam::where('target_class', $user->kelas)
                     ->orderBy('exam_date', 'asc')
                     ->get();

        $viewName = ViewHelper::resolveView('kuesioner');
        return view($viewName, compact('exams', 'completedExams'));
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
}