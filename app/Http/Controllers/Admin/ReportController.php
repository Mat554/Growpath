<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shared\ReportTrait;
use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{
    use ReportTrait; // For AI analysis

    /**
     * Show all reports with search functionality
     */
    public function index(Request $request)
    {
        $query = ExamResult::with(['user', 'user.parents']);

        // Search by student name or parent name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function (Builder $q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('user.parents', function (Builder $q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter by dominant code (hasil tes)
        if ($request->has('hasil') && $request->hasil) {
            $query->where('dominant_code', $request->hasil);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.laporan-index', compact('reports'));
    }

    /**
     * View any student report
     */
    public function view($id)
    {
        $result = ExamResult::with('user.parents')->findOrFail($id);
        $namaPemilik = $result->user->name ?? 'Siswa';

        // Get exam to determine question count (max score)
        $exam = Exam::with('questions')->find($result->exam_id);
        $maxScore = $exam ? $exam->questions->count() : 60;

        $aiData = $this->generateOllamaAnalysis($result->dominant_code);

        return view('laporan', compact('result', 'namaPemilik', 'aiData', 'maxScore'));
    }
}