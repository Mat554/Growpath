<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
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
        // Siswa hanya boleh melihat laporannya JIKA sudah di-publish oleh Admin
        $result = ExamResult::where('user_id', Auth::id())
                            ->where('status', 'published')
                            ->latest()
                            ->first();

        if (!$result) {
            return redirect()->route('dashboard')->with('error', 'Laporan Anda belum tersedia atau sedang dievaluasi oleh Admin.');
        }

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);
        $namaPemilik = Auth::user()->name;

        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }
}