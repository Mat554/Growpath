<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    /**
     * Show monitoring view
     */
    public function index()
    {
        // Get all exams for the filter dropdown
        $activeExams = Exam::orderBy('exam_date', 'desc')->get();
        return view('admin.monitoring', compact('activeExams'));
    }

    /**
     * Get monitoring data (API endpoint)
     */
    public function data(Request $request)
    {
        // Rate limit this endpoint
        $examId = $request->query('exam_id');

        $query = ExamResult::with(['user', 'exam'])
                    ->orderBy('created_at', 'desc');

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        $results = $query->get();

        $monitoringData = [];

        foreach ($results as $result) {
            $status = 'Selesai';
            $progress = 'Selesai';

            $monitoringData[] = [
                'student_name' => $result->user->name ?? 'Unknown',
                'student_class' => $result->user->kelas ?? '-',
                'exam_title' => $result->exam->title ?? 'Unknown Exam',
                'status' => $status,
                'progress' => $progress,
            ];
        }

        return response()->json($monitoringData);
    }
}