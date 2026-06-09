<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    use ReportTrait; // Use real AI like parent report

    /**
     * Beta test preview page - dedicated page for admin to test the student experience
     */
    public function betaTestPreview(Request $request)
    {
        $questionIds = Session::get('beta_question_ids', []);

        // If stored as JSON string, decode it
        if (is_string($questionIds)) {
            $questionIds = json_decode($questionIds, true) ?? [];
        }

        $duration = Session::get('beta_duration', 60);
        $cardTitle = Session::get('beta_title', 'Beta Test Preview');

        if (empty($questionIds)) {
            return redirect()->route('admin.dashboard')->with('error', 'Tidak ada soal yang dipilih untuk Beta Test.');
        }

        // Ensure questionIds are integers
        $questionIds = array_map('intval', $questionIds);

        $questions = Question::whereIn('id', $questionIds)->get();

        if ($questions->isEmpty()) {
            return redirect()->route('admin.dashboard')->with('error', 'Soal tidak ditemukan.');
        }

        return view('admin.beta-test-preview', [
            'questions' => $questions,
            'duration' => $duration,
            'cardTitle' => $cardTitle
        ]);
    }

    /**
     * Beta test - store question IDs in session and redirect to preview page
     */
    public function betaTest(Request $request)
    {
        // Handle both string (comma-separated) and array input
        if (is_string($request->question_ids)) {
            $questionIds = array_filter(explode(',', $request->question_ids));
        } else {
            $questionIds = $request->question_ids ?? [];
        }

        // Ensure we have integers
        $questionIds = array_map('intval', $questionIds);
        $questionIds = array_values($questionIds); // Re-index

        if (empty($questionIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada soal yang dipilih.'
            ], 422);
        }

        $duration = $request->duration ?? 60;
        $title = $request->title ?? 'Beta Test Preview';

        // Validate question IDs
        $validQuestions = Question::whereIn('id', $questionIds)->get();
        if ($validQuestions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada soal yang valid.'
            ], 422);
        }

        // Store in session for the preview page (as array, not JSON string)
        Session::put('beta_question_ids', $questionIds);
        Session::put('beta_duration', $duration);
        Session::put('beta_title', $title);

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.beta.preview')
        ]);
    }

    /**
     * Beta test report preview - show parent-style report for beta test results
     */
    public function betaReportPreview(Request $request)
    {
        $scores = Session::get('beta_scores', []);
        $dominantCode = Session::get('beta_dominant_code', '');
        $questionIds = Session::get('beta_question_ids', []);

        if (empty($scores)) {
            return redirect()->route('admin.beta.preview')->with('error', 'Tidak ada hasil beta test.');
        }

        // Dynamic max score = question count (number of questions taken)
        $questionCount = is_array($questionIds) ? count($questionIds) : 0;
        $maxScore = $questionCount > 0 ? $questionCount : 60;

        // Generate REAL AI data like parent report
        $aiData = $this->generateOllamaAnalysis($dominantCode);

        return view('admin.beta-report-preview', [
            'scores' => $scores,
            'dominantCode' => $dominantCode,
            'aiData' => $aiData,
            'maxScore' => $maxScore
        ]);
    }

    /**
     * Store beta test results in session
     */
    public function storeBetaResults(Request $request)
    {
        $scores = $request->scores ?? [];
        $dominantCode = $request->dominant_code ?? '';

        Session::put('beta_scores', $scores);
        Session::put('beta_dominant_code', $dominantCode);

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.beta.report')
        ]);
    }

    /**
     * Publish exam
     */
    public function publish(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'target_class' => 'required|string',
            'duration_minutes' => 'required|integer',
            'exam_date' => 'required|date',
            'exam_end_date' => 'nullable|date|after_or_equal:exam_date',
            'question_ids' => 'required|array'
        ]);

        // Validasi: Pastikan semua question_ids valid
        $validQuestionIds = Question::whereIn('id', $request->question_ids)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        if (count($validQuestionIds) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada soal aktif yang valid.'
            ], 422);
        }

        // Simpan Ujian
        $exam = Exam::create([
            'title' => $request->title,
            'target_class' => $request->target_class,
            'duration_minutes' => $request->duration_minutes,
            'exam_date' => $request->exam_date,
            'exam_end_date' => $request->exam_end_date,
        ]);

        // Hubungkan ke Soal-soal (Many-to-Many)
        $exam->questions()->attach($validQuestionIds);

        return response()->json([
            'success' => true,
            'message' => 'Tes berhasil di-publish ke siswa!'
        ]);
    }
}