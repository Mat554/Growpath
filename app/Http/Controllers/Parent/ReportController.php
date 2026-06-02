<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
use App\Models\User;
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
                            ->where('status', 'published')
                            ->latest()
                            ->first();

        if (!$result) {
            return redirect()->route('dashboard.ortu')->with('error', 'Laporan anak Anda sedang direview oleh Admin atau belum tersedia.');
        }

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);
        $namaPemilik = $anak->name;

        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }
}