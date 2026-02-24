<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamResult;

class DashboardController extends Controller
{
    // Halaman Dashboard Siswa
 public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil daftar ujian sesuai kelas
        $exams = Exam::where('target_class', $user->kelas)
                     ->orderBy('created_at', 'desc')
                     ->get();

        // 2. Ambil kumpulan ID ujian yang SUDAH dikerjakan oleh siswa ini
        $completedExamIds = \App\Models\ExamResult::where('user_id', $user->id)
                                ->pluck('exam_id')
                                ->toArray();

        // 3. Kirim ke view (tambahkan variabel $completedExamIds)
        return view('dashboard', compact('exams', 'completedExamIds'));
    }

    // 1. Menampilkan Soal ke Siswa
    public function takeExam($id)
    {
        // CEK KEAMANAN: Apakah siswa ini sudah pernah mengerjakan ujian ini?
        $alreadyTaken = \App\Models\ExamResult::where('user_id', Auth::id())
                            ->where('exam_id', $id)
                            ->exists();
                            
        if ($alreadyTaken) {
            // Jika sudah, lempar dia kembali ke halaman laporan
            return redirect()->route('laporan')->with('error', 'Anda sudah menyelesaikan kuesioner ini.');
        }

        $exam = Exam::with('questions')->findOrFail($id);
        
        return view('tes', [
            'exam' => $exam,
            'questions' => $exam->questions,
            'is_beta' => false,
            'duration' => $exam->duration_minutes
        ]);
    }

    // 2. Menerima Jawaban dari Javascript dan Simpan ke DB
    public function submitExam(Request $request, $id)
    {
        $scores = $request->scores;

        ExamResult::create([
            'user_id' => Auth::id(),
            'exam_id' => $id,
            'score_r' => $scores['R'],
            'score_i' => $scores['I'],
            'score_a' => $scores['A'],
            'score_s' => $scores['S'],
            'score_e' => $scores['E'],
            'score_c' => $scores['C'],
            'dominant_code' => $request->dominant_code,
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('laporan')]);
    }

    // 3. Menampilkan Halaman Laporan
    public function laporan()
    {
        // Ambil hasil ujian terakhir milik siswa ini
        $result = ExamResult::where('user_id', Auth::id())->latest()->first();
        
        if (!$result) {
            return redirect()->route('dashboard')->with('error', 'Selesaikan kuesioner terlebih dahulu!');
        }

        return view('laporan', compact('result'));
    }


   public function ortu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = \App\Models\User::where('user_code', Auth::user()->child_id_code)
                                ->where('role', 'siswa')
                                ->first();

        // UBAH BARIS INI:
        // Jika nama filenya 'ortu-dashboard.blade.php' di dalam folder views
        return view('ortu.ortu-dashboard', compact('anak')); 
    }
    // Tambahkan fungsi ini di bawah fungsi index() yang sudah ada
    public function profile()
    {
        // Pastikan hanya user yang login yang bisa akses
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('profile');
    }

    public function profileOrtu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }
        return view('ortu.ortu-profile');
    }

    public function dashboardAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('admin.admin-dashboard');
    }

    
    
}