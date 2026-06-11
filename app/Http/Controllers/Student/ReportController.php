<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use ReportTrait; // Share AI analysis logic with Parent

    /**
     * Show student report
     */
    public function show()
    {
        // Siswa bisa melihat laporan setelah mengerjakan tes
        $result = ExamResult::where('user_id', Auth::id())
                            ->latest()
                            ->first();

        if (!$result) {
            return redirect()->route('dashboard')->with('error', 'Anda belum mengerjakan tes. Silakan kerjakan kuesioner terlebih dahulu.');
        }

        // Get exam to determine question count (max score)
        $exam = Exam::with('questions')->find($result->exam_id);
        $maxScore = $exam ? $exam->questions->count() : 60; // Default to 60 if not found

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);
        $namaPemilik = Auth::user()->name;

        return view('laporan', compact('result', 'namaPemilik', 'aiData', 'maxScore'));
    }
}