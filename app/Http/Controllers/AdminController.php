<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;

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
}