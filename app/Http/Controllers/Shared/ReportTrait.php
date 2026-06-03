<?php

namespace App\Http\Controllers\Shared;

use App\Models\ExamResult;
use Illuminate\Support\Facades\Http;

/**
 * Trait for shared report generation functionality
 * Used by Student and Parent report controllers
 */
trait ReportTrait
{
    /**
     * Generate AI analysis using Groq API
     * This method is shared between Student and Parent controllers
     */
    protected function generateOllamaAnalysis($kodeDominan)
    {
        // Sanitize input to prevent prompt injection
        $kodeDominan = $this->sanitizeDominantCode($kodeDominan);

        $prompt = "Kamu adalah pakar pendidikan dan konsultan karir profesional di Indonesia.
                   Analisis kode dominan RIASEC ini: '{$kodeDominan}'.
                   Balas HANYA dengan format JSON persis seperti struktur di bawah ini tanpa teks pengantar, markdown, atau penutup apa pun:
                   {
                       \"judul\": \"Nama Kepribadian (contoh: The Organizers / The Creators)\",
                       \"deskripsi\": \"Penjelasan singkat 2 kalimat yang memotivasi tentang karakter dan potensi utama dari kode ini.\",
                       \"jurusan\": [\"Jurusan Kuliah 1\", \"Jurusan Kuliah 2\", \"Jurusan Kuliah 3\", \"Jurusan Kuliah 4\", \"Jurusan Kuliah 5\"],
                       \"kampus\": [\"Universitas Indonesia (UI) - Nama Fakultas/Jurusan\", \"Institut Teknologi Bandung (ITB) - Nama Fakultas/Jurusan\", \"Universitas Gadjah Mada (UGM) - Nama Fakultas/Jurusan\"],
                       \"tips\": [\"Tips belajar 1 yang disesuaikan dengan gaya belajar kode ini\", \"Tips belajar 2 yang aplikatif\", \"Tips belajar 3\"]
                   }
                   PENTING: Untuk bagian 'kampus', berikan 3-5 nama Universitas/Institut TERBAIK dan NYATA di INDONESIA yang paling cocok dengan jurusan-jurusan tersebut.";

        try {
            $groqUrl = 'https://api.groq.com/openai/v1/chat/completions';
            $apiKey = env('GROQ_API_KEY');

            $response = Http::withToken($apiKey)->timeout(30)->post($groqUrl, [
                'model' => 'llama-3.1-8b-instant',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7
            ]);

            if (!$response->successful()) {
                return [
                    "judul" => "ERROR HTTP " . $response->status(),
                    "deskripsi" => "Gagal menghubungi Groq: " . $response->body(),
                    "jurusan" => ["-", "-", "-"],
                    "kampus" => ["-", "-", "-"],
                    "tips" => ["-", "-", "-"]
                ];
            }

            $aiText = $response->json('choices.0.message.content');

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
                "jurusan" => ["-", "-", "-"],
                "kampus" => ["-", "-", "-"],
                "tips" => ["-", "-", "-"]
            ];

        } catch (\Exception $e) {
            return [
                "judul" => "KONEKSI TERPUTUS",
                "deskripsi" => "Pesan error: " . $e->getMessage(),
                "jurusan" => ["-", "-", "-"],
                "kampus" => ["-", "-", "-"],
                "tips" => ["-", "-", "-"]
            ];
        }
    }

    /**
     * Sanitize dominant code to prevent prompt injection
     */
    protected function sanitizeDominantCode($code)
    {
        // Only allow valid RIASEC characters
        $code = strtoupper(trim($code));

        // Remove any characters that aren't R, I, A, S, E, or C
        $code = preg_replace('/[^RIASEC]/', '', $code);

        // Limit to 6 characters max
        return substr($code, 0, 6);
    }
}
