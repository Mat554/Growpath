<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User; // Tambahkan ini
use App\Models\Question; // Tambahkan ini
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk fitur Hapus Avatar
use Carbon\Carbon; // Tambahkan ini untuk waktu

class DashboardController extends Controller
{
    // =========================================================
    // 1. DASHBOARD & KUESIONER SISWA
    // =========================================================
    
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil daftar ID ujian yang SUDAH dikerjakan siswa ini
        $completedExamIds = ExamResult::where('user_id', $user->id)
                                ->pluck('exam_id')
                                ->toArray();

        // 2. Cari 1 ujian terdekat yang BELUM dikerjakan
        $nextExam = Exam::where('target_class', $user->kelas)
                    ->whereNotIn('id', $completedExamIds) // Kecualikan yang sudah selesai
                    ->orderBy('exam_date', 'asc')         // Urutkan dari jadwal yang paling dekat
                    ->first();                            // Hanya ambil 1 tugas teratas

        // 3. Bungkus hasilnya ke dalam koleksi agar file Blade (@forelse) tetap bisa membacanya
        $exams = $nextExam ? collect([$nextExam]) : collect();

        // 4. Kita tetap butuh data ujian yang selesai untuk membuka gembok kartu "Laporan Hasil"
       $completedExams = ExamResult::where('user_id', Auth::id())->get();

        // Yang masih menunggu persetujuan
        $pendingParents = Auth::user()->parents()->where('child_connection_status', 'pending')->get();
        
        // Yang sudah disetujui
        $connectedParents = Auth::user()->parents()->where('child_connection_status', 'approved')->get();

        // Jangan lupa kirim kedua variabel ini ke view (tambahkan $pendingParents di compact)
        return view('dashboard', compact('exams', 'completedExams', 'completedExamIds', 'connectedParents', 'pendingParents'));

    }

    public function kuesioner()
    {
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('dashboard');
        }

        $user = Auth::user();

        $completedExams = ExamResult::where('user_id', $user->id)
                                ->get()
                                ->keyBy('exam_id');

        $exams = Exam::where('target_class', $user->kelas)
                     ->orderBy('exam_date', 'asc')
                     ->get();

        return view('kuesioner', compact('exams', 'completedExams'));
    }


    // =========================================================
    // 2. ALUR PENGERJAAN TES (SISWA)
    // =========================================================

    public function takeExam($id)
    {
        $alreadyTaken = ExamResult::where('user_id', Auth::id())
                            ->where('exam_id', $id)
                            ->exists();
                            
        if ($alreadyTaken) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah menyelesaikan kuesioner ini.');
        }

        $exam = Exam::with('questions')->findOrFail($id);
        
        return view('tes', [
            'exam' => $exam,
            'questions' => $exam->questions,
            'is_beta' => false,
            'duration' => $exam->duration_minutes
        ]);
    }

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
            'status' => 'review', // Menunggu persetujuan admin
        ]);

        return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
    }

    // =========================================================
    // 3. LAPORAN (SISWA & ORTU)
    // =========================================================

    public function laporan()
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

    public function laporanOrtu()
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

    // =========================================================
    // 4. PROFIL & PENGATURAN
    // =========================================================

    public function profile()
    {
        return view('profile');
    }

    public function updateAvatar(Request $request)
    {
     // 1. Validasi
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. MENGAMBIL DATA USER YANG SEDANG LOGIN (BARIS INI WAJIB ADA)
        $user = Auth::user(); 

        $file = $request->file('avatar');
        
        // 3. Buat nama file super unik
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // 4. Hapus foto lama
        if ($user->avatar && Storage::disk('s3')->exists($user->avatar)) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // 5. Upload foto baru ke Supabase
        Storage::disk('s3')->putFileAs('/', $file, $filename, 'public');

        // 6. Update database
        $user->update([
            'avatar' => $filename
        ]);

        return back()->with('status', 'Foto profil berhasil diperbarui!');
    }




    // =========================================================
    // 5. DASHBOARD ORTU UTAMA
    // =========================================================

    public function ortu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }

        $anak = User::where('user_code', Auth::user()->child_id_code)
                    ->where('role', 'siswa')
                    ->first();

        $hasilTesAnak = collect(); 

        if ($anak) {
            // Ortu hanya melihat laporan yang sudah di publish
            $hasilTesAnak = ExamResult::where('user_id', $anak->id)
                                      ->where('status', 'published')
                                      ->get();
        }

        return view('ortu.ortu-dashboard', compact('anak', 'hasilTesAnak')); 
    }

    public function profileOrtu()
    {
        if (Auth::user()->role !== 'ortu') {
            return redirect()->route('dashboard');
        }
        return view('ortu.ortu-profile');
    }

    public function updateAvatarOrtu(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. MENGAMBIL DATA USER YANG SEDANG LOGIN (BARIS INI WAJIB ADA)
        $user = Auth::user(); 

        $file = $request->file('avatar');
        
        // 3. Buat nama file super unik
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // 4. Hapus foto lama
        if ($user->avatar && Storage::disk('s3')->exists($user->avatar)) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // 5. Upload foto baru ke Supabase
        Storage::disk('s3')->putFileAs('/', $file, $filename, 'public');

        // 6. Update database
        $user->update([
            'avatar' => $filename
        ]);

        return back()->with('status', 'Foto profil berhasil diperbarui!');
    }

    // =========================================================
    // 6. DASHBOARD ADMIN
    // =========================================================

    public function dashboardAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalSoal = Exam::count(); 
        $totalLaporan = ExamResult::count();

        $pendingReports = ExamResult::with('user')->where('status', 'review')->get();
        $questions = Question::all(); 

        return view('admin.admin-dashboard', compact('totalSiswa', 'totalSoal', 'totalLaporan', 'pendingReports', 'questions'));
    }

    public function laporanAdmin($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $result = ExamResult::with('user')->findOrFail($id);
        $namaPemilik = $result->user->name ?? 'Siswa'; 
        
        $aiData = $this->generateOllamaAnalysis($result->dominant_code);
        
        return view('laporan', compact('result', 'namaPemilik', 'aiData'));
    }

    public function publishLaporan($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $result = ExamResult::findOrFail($id);
        $result->update(['status' => 'published']); 

        return redirect()->back()->with('success', 'Laporan berhasil di-publish!');
    }

   private function generateOllamaAnalysis($kodeDominan)
    {
        // Prompt tetap sama persis!
        $prompt = "Kamu adalah pakar karir. Analisis kode dominan RIASEC: '{$kodeDominan}'. 
                   Balas HANYA dengan format JSON persis seperti ini tanpa teks pengantar apa pun: 
                   {
                       \"judul\": \"Nama Kepribadian (contoh: The Organizers)\", 
                       \"deskripsi\": \"Penjelasan singkat 2 kalimat tentang karakter ini.\", 
                       \"jurusan\": [\"Jurusan 1\", \"Jurusan 2\", \"Jurusan 3\", \"Jurusan 4\", \"Jurusan 5\"]
                   }";

        try {
            // Kita arahkan tembakan ke server Groq Cloud
            $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
            $apiKey = env('GROQ_API_KEY'); 

            // Eksekusi tembakan
            $response = Http::withToken($apiKey)->timeout(30)->post($groqUrl, [
                // Menggunakan Llama 3 versi 8B yang super cepat
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                // Paksa AI membalas dalam format JSON
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7
            ]);

            // Jika Gagal
            if (!$response->successful()) {
                return [
                    "judul" => "ERROR HTTP " . $response->status(),
                    "deskripsi" => "Gagal menghubungi Groq: " . $response->body(),
                    "jurusan" => ["-", "-", "-"]
                ];
            }

            // Ambil teks balasan (Format Groq/OpenAI sedikit berbeda cara panggilnya)
            $aiText = $response->json('choices.0.message.content');
            
            // Bersihkan dan ubah JSON ke Array PHP
            if (preg_match('/\{.*\}/s', $aiText, $matches)) {
                $cleanJson = $matches[0];
                $decodedData = json_decode($cleanJson, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decodedData;
                }
            }

            return [
                "judul" => "Format Tidak Dikenali",
                "deskripsi" => "AI membalas, tetapi format JSON rusak.",
                "jurusan" => ["-", "-", "-"]
            ];

        } catch (\Exception $e) {
            return [
                "judul" => "KONEKSI TERPUTUS",
                "deskripsi" => "Pesan error: " . $e->getMessage(),
                "jurusan" => ["-", "-", "-"]
            ];
        }
    }

    // Untuk Siswa melepaskan Orang Tua
    public function revokeKoneksi($parentId)
    {   
    $parent = User::findOrFail($parentId);
    // Jika user yang login adalah siswa dari ortu tersebut, hapus kodenya
    if ($parent->child_id_code == Auth::user()->user_code) {
        $parent->update(['child_id_code' => null]);
        }
    return back()->with('success', 'Koneksi dilepaskan.');
    }

// Untuk Orang Tua melepaskan Anak
        public function revokeKoneksiOrtu()
        {
    Auth::user()->update(['child_id_code' => null]);
    return back()->with('success', 'Koneksi dengan anak dilepaskan.');
        }

        public function connectKoneksiOrtu(Request $request)
    {
        $request->validate([
            'child_code' => 'required|string',
        ]);

        // Cari siswa berdasarkan user_code yang diinput
        $siswa = User::where('user_code', $request->child_code)->first();

        // Jika siswa tidak ditemukan
        if (!$siswa) {
            return back()->with('error', 'User ID Siswa tidak ditemukan. Pastikan ID diketik dengan benar.');
        }

        // Jika ditemukan, simpan ke akun ortu
       // Jika ditemukan, simpan ke akun ortu dengan status PENDING
        Auth::user()->update([
            'child_id_code' => $siswa->user_code,
            'child_connection_status' => 'pending' // <--- Tambahkan baris ini
        ]);

        return back()->with('success', 'Permintaan koneksi telah dikirim ke akun ' . $siswa->name . '. Menunggu persetujuan.');
    }

    public function approveKoneksi($parentId)
    {
        $parent = User::findOrFail($parentId);
        if ($parent->child_id_code == Auth::user()->user_code) {
            $parent->update(['child_connection_status' => 'approved']);
        }
        return back();
    }

    public function rejectKoneksi($parentId)
    {
        $parent = User::findOrFail($parentId);
        if ($parent->child_id_code == Auth::user()->user_code) {
            $parent->update([
                'child_id_code' => null, 
                'child_connection_status' => null
            ]);
        }
        return back();
    }
}