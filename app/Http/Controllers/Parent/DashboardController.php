<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Helpers\ViewHelper;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ReportTrait; // For AI analysis

    /**
     * Show parent dashboard
     */
    public function index()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = User::where('user_code', Auth::user()->child_id_code)
                    ->where('role', 'siswa')
                    ->first();

        $hasilTesAnak = collect();
        $result = null;
        $aiData = null;

        if ($anak) {
            // Ortu hanya melihat laporan yang sudah di publish
            $hasilTesAnak = ExamResult::where('user_id', $anak->id)
                                      ->where('status', 'published')
                                      ->get();

            // Ambil 1 laporan terbaru yang SUDAH di-publish
            $result = ExamResult::where('user_id', $anak->id)
                                ->where('status', 'published')
                                ->latest()
                                ->first();

            // Generate AI analysis if report exists
            if ($result) {
                $aiData = $this->generateOllamaAnalysis($result->dominant_code);
            }
        }

        $viewName = ViewHelper::resolveView('ortu.ortu-dashboard');
        return view($viewName, compact('anak', 'hasilTesAnak', 'result', 'aiData'));
    }
}