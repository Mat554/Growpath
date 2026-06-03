<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use ReportTrait; // For AI analysis

    /**
     * View any student report
     */
    public function view($id)
    {
        $result = ExamResult::with('user')->findOrFail($id);
        $namaPemilik = $result->user->name ?? 'Siswa';

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);

        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }

    /**
     * Publish a student report
     */
    public function publish($id)
    {
        $result = ExamResult::findOrFail($id);
        $result->update(['status' => 'published']);

        return redirect()->back()->with('success', 'Laporan berhasil di-publish!');
    }
}