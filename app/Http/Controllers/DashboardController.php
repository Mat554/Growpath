<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Support\Facades\Http; // <-- PENTING: Tambahkan ini untuk memanggil API

class DashboardController extends Controller
{
    // Halaman Dashboard Siswa
    public function index()
    {
        $user = Auth::user();

        $today = \Carbon\Carbon::now()->startOfDay();

          $completedExamIds = \App\Models\ExamResult::where('user_id', $user->id)
                                ->where('status', 'published')
                                ->pluck('exam_id')
                                ->toArray();

        // 1. Ambil daftar ujian sesuai kelas
        $exams = \App\Models\Exam::where('target_class', $user->kelas)
                    ->orderBy('exam_date', 'asc') 
                    ->take(1)
                    ->get();

        // 3. Kirim ke view
        return view('dashboard', compact('exams', 'completedExamIds'));
    }

    public function kuesioner()
    {
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        // 1. Ambil riwayat ujian, JANGAN LUPA TITIK KOMA (;) DI AKHIR BARIS INI
        $completedExams = \App\Models\ExamResult::where('user_id', $user->id)
                                ->get()
                                ->keyBy('exam_id');

        // 2. Ambil semua jadwal ujian
        $exams = \App\Models\Exam::where('target_class', $user->kelas)
                     ->orderBy('exam_date', 'asc')
                     ->get();

        // 3. Kirim ke view
        return view('kuesioner', compact('exams', 'completedExams'));
    }

   public function laporanOrtu()
    {
        // 1. Cek Keamanan
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        // 2. Cari Data Anak
        $anak = \App\Models\User::where('user_code', Auth::user()->child_id_code)
                                ->where('role', 'siswa')
                                ->first();

        if (!$anak) {
            return redirect()->route('dashboard.ortu')->with('error', 'Data anak tidak ditemukan.');
        }

        // 3. Ambil laporan anak yang SUDAH DI-PUBLISH oleh Admin
        $result = \App\Models\ExamResult::where('user_id', $anak->id)
                                        ->where('status', 'published')
                                        ->latest()
                                        ->first();

        if (!$result) {
            return redirect()->route('dashboard.ortu')->with('error', 'Laporan anak Anda sedang direview oleh Admin atau belum tersedia.');
        }

        // 4. Panggil Gemini AI
        $aiData = $this->generateGeminiAnalysis($result->dominant_code);

        // 5. Kirim data ke tampilan
        $namaPemilik = $anak->name;
        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }

    // 1. Menampilkan Soal ke Siswa
    public function takeExam($id)
    {
        // CEK KEAMANAN
        $alreadyTaken = ExamResult::where('user_id', Auth::id())
                            ->where('exam_id', $id)
                            ->exists();
                            
        if ($alreadyTaken) {
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
            'status' => 'review',
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('laporan')]);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // max:2048 memastikan batas 2MB dari sisi server
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && \Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                \Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Simpan avatar baru
            $fileName = time() . '.' . $request->avatar->extension();  
            $request->avatar->storeAs('avatars', $fileName, 'public');

            // Update database (Pastikan Anda sudah menambahkan kolom 'avatar' di tabel users)
            $user->update(['avatar' => $fileName]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    // 3. Menampilkan Halaman Laporan (SISWA)
    public function laporan()
    {
        $result = ExamResult::where('user_id', Auth::id())->latest()->first();
        
        if (!$result) {
            return redirect()->route('dashboard')->with('error', 'Selesaikan kuesioner terlebih dahulu!');
        }
        
        // Panggil Gemini AI
        $aiData = $this->generateGeminiAnalysis($result->dominant_code);

        $namaPemilik = Auth::user()->name;

        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }


   public function ortu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = \App\Models\User::where('user_code', Auth::user()->child_id_code)
                                ->where('role', 'siswa')
                                ->first();

        $hasilTesAnak = collect(); 

        if ($anak) {
            $hasilTesAnak = \App\Models\ExamResult::where('user_id', $anak->id)->get();
        }

        return view('ortu.ortu-dashboard', compact('anak', 'hasilTesAnak')); 
    }

    public function profile()
    {
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

    public function tes()
    {
        return view('tes');
    }

    // --- 1. DASHBOARD ADMIN UTAMA ---
    public function dashboardAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        
        $totalSiswa = \App\Models\User::where('role', 'siswa')->count();
        $totalSoal = \App\Models\Exam::count(); 
        $totalLaporan = \App\Models\ExamResult::count();

        $pendingReports = \App\Models\ExamResult::with('user')->where('status', 'review')->get();
        $questions = \App\Models\Question::all(); 

        return view('admin.admin-dashboard', compact('totalSiswa', 'totalSoal', 'totalLaporan', 'pendingReports', 'questions'));
    }

    // --- 2. ADMIN MELIHAT LAPORAN SISWA ---
    public function laporanAdmin($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $result = \App\Models\ExamResult::with('user')->findOrFail($id);
        $namaPemilik = $result->user->name ?? 'Siswa'; 
        
        // Panggil Gemini AI
        $aiData = $this->generateGeminiAnalysis($result->dominant_code);
        
        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }

    // --- 3. ADMIN PUBLISH LAPORAN ---
    public function publishLaporan($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $result = \App\Models\ExamResult::findOrFail($id);
        $result->update(['status' => 'published']); 

        return redirect()->back()->with('success', 'Laporan berhasil di-publish!');
    }
    
   // =========================================================================
    // FUNGSI ASISTEN PRIBADI UNTUK MEMANGGIL GEMINI AI (VERSI DETEKTIF)
    // =========================================================================
    // =========================================================================
    // FUNGSI ASISTEN PRIBADI UNTUK MEMANGGIL GEMINI AI (FINAL VERSION)
    // =========================================================================
    private function generateGeminiAnalysis($kodeDominan)
    {
        $prompt = "Kamu adalah pakar karir. Analisis kode dominan RIASEC: '{$kodeDominan}'. 
                   Balas HANYA dengan format JSON persis seperti ini tanpa format markdown atau teks lain: 
                   {
                       \"judul\": \"Nama Kepribadian (contoh: The Organizers)\", 
                       \"deskripsi\": \"Penjelasan singkat 2 kalimat tentang karakter ini.\", 
                       \"jurusan\": [\"Jurusan 1\", \"Jurusan 2\", \"Jurusan 3\", \"Jurusan 4\", \"Jurusan 5\"]
                   }";

        try {
            // Memanggil model 'gemini-2.5-flash' yang resmi tersedia untuk API Key kamu
            $response = Http::withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . env('GEMINI_API_KEY'), [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $geminiText = $response->json('candidates.0.content.parts.0.text');
                
                // Pembersihan karakter ekstra agar JSON tidak rusak
                $geminiText = str_replace(['```json', '```', "\n", "\r"], '', $geminiText); 
                
                return json_decode(trim($geminiText), true);
            }
        } catch (\Exception $e) {
        }

        return null;
    }
}