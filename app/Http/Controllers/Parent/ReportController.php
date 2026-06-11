<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use ReportTrait; // Share AI analysis logic

    /**
     * Show child's report
     */
    public function show()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = User::where('user_code', Auth::user()->child_id_code)
                    ->where('role', 'siswa')
                    ->first();

        if (!$anak) {
            return redirect()->route('dashboard.ortu')->with('error', 'Data anak tidak ditemukan.');
        }

        $result = ExamResult::where('user_id', $anak->id)
                            ->latest()
                            ->first();

        if (!$result) {
            return redirect()->route('dashboard.ortu')->with('error', 'Laporan anak Anda belum tersedia.');
        }

        // Get exam to determine question count (max score)
        $exam = Exam::with('questions')->find($result->exam_id);
        $maxScore = $exam ? $exam->questions->count() : 60;

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);
        $namaPemilik = $anak->name;

        return view('laporan', compact('result', 'namaPemilik', 'aiData', 'maxScore'));
    }
}