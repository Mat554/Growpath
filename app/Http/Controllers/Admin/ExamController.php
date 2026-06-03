<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Beta test preview
     */
    public function betaTest(Request $request)
    {
        $questionIds = explode(',', $request->question_ids);
        $questions = Question::whereIn('id', $questionIds)->get();

        // Default 60 minutes if not specified
        $duration = $request->duration ?? 60;

        return view('tes', [
            'questions' => $questions,
            'is_beta' => true,
            'duration' => $duration
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