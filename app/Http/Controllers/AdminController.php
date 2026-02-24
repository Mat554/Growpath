<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamResult;

class AdminController extends Controller
{

public function betaTestPreview(Request $request)
    {
        $questionIds = explode(',', $request->question_ids); 
        $questions = Question::whereIn('id', $questionIds)->get();

        // Tangkap durasi dari request (default 60 jika tidak ada)
        $duration = $request->duration ?? 60;

        return view('tes', [
            'questions' => $questions,
            'is_beta' => true,
            'duration' => $duration // <--- Kirim ke Blade
        ]); 
    }
    // Fungsi 2: Untuk Simpan (Publish) Konfigurasi Card
    public function publishExam(Request $request)
    {
        // 1. Validasi untuk mencegah error SQL
        $request->validate([
            'title' => 'required|string',
            'target_class' => 'required|string',
            'duration_minutes' => 'required|integer',
            'exam_date' => 'required|date',
            'question_ids' => 'required|array'
        ]);

        // 2. Simpan Ujian
        $exam = Exam::create([
            'title' => $request->title,
            'target_class' => $request->target_class,
            'duration_minutes' => $request->duration_minutes,
            'exam_date' => $request->exam_date,
        ]);

        // 3. Hubungkan ke Soal-soal (Many-to-Many)
        $exam->questions()->attach($request->question_ids);

        // 4. Kirim respon sukses ke JS
        return response()->json(['success' => true, 'message' => 'Tes berhasil di-publish ke siswa!']);
    }
    
    // 1. Tampilkan Dashboard beserta Data Soal
 public function dashboard()
    {

    // 1. Ambil data soal untuk kebutuhan tabel (kode yang sudah ada)
        $questions = \App\Models\Question::all();

        // 2. HITUNG STATISTIK REAL-TIME
        // Asumsi: Siswa memiliki kolom 'kelas', sehingga admin tidak ikut terhitung
        $totalSiswa = \App\Models\User::whereNotNull('kelas')->count(); 
        
        // Menghitung total pertanyaan yang ada di Bank Soal
        $totalSoal = \App\Models\Question::count();
        
        // Menghitung total laporan/hasil ujian yang sudah disubmit oleh siswa
        $totalLaporan = \App\Models\ExamResult::count();

        // 3. Kirim semua variabel ke file Blade
        return view('admin.admin-dashboard', compact(
            'questions', 
            'totalSiswa', 
            'totalSoal', 
            'totalLaporan'
        ));

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil data dari database
        $questions = \App\Models\Question::orderBy('created_at', 'desc')->get();
        
        // Kirim data tersebut ke HTML
        return view('admin.admin-dashboard', compact('questions'));
    }
    // 2. Fungsi Simpan Soal (Yang sebelumnya sudah kamu buat)
    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'opt_r' => 'required|string',
            'opt_i' => 'required|string',
            'opt_a' => 'required|string',
            'opt_s' => 'required|string',
            'opt_e' => 'required|string',
            'opt_c' => 'required|string',
        ]);

        Question::create($validated);

        // Tambahkan ->with('tab', 'publish') agar setelah save langsung buka tab Kelola Soal
        return redirect()->route('dashboard.admin')->with('success', 'Soal RIASEC berhasil ditambahkan!')->with('tab', 'publish');
    }

    // 3. FUNGSI BARU: Mengubah status Tayang / Draft
    public function toggleStatus($id)
    {
        $question = Question::findOrFail($id);
        
        // Balikkan statusnya (Jika true jadi false, jika false jadi true)
        $question->is_active = !$question->is_active;
        $question->save();

        // Kembalikan ke dashboard dan buka langsung tab 'publish' (Kelola Soal)
        return redirect()->route('dashboard.admin')->with('success', 'Status soal berhasil diubah!')->with('tab', 'publish');
    }

    public function monitoringView()
    {
        // Get all exams to populate the filter dropdown
        $activeExams = Exam::orderBy('exam_date', 'desc')->get();
        return view('admin.monitoring', compact('activeExams'));
    }

    // 2. The API Endpoint for Live Data Fetching
    public function getMonitoringData(Request $request)
    {
        $examId = $request->query('exam_id');

        // This query joins the Users, Exams, and ExamResults to get a comprehensive view
        // IMPORTANT: You will need to track 'progress' and 'status' in a new table 
        // (e.g., ExamSession) if you want real-time "Soal 5/20" tracking.
        // For now, this is a simulated response based on the ExamResult table.

        $query = \App\Models\ExamResult::with(['user', 'exam'])
                    ->orderBy('created_at', 'desc');

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        $results = $query->get();

        $monitoringData = [];

        foreach ($results as $result) {
            
            // Basic Status Logic based on your requirements
            $status = 'Selesai';
            $progress = 'Selesai';

            // Example of how you might determine "Failed" (e.g., time ran out before finishing)
            // if ($result->score_r == 0 && $result->score_i == 0) {
            //    $status = 'Failed';
            //    $progress = 'Waktu Habis';
            // }

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