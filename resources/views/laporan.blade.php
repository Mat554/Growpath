<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* CSS Khusus Print */
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .shadow-lg, .shadow-sm { box-shadow: none !important; }
            .report-card { border: none; max-width: 100%; box-shadow: none; }
            .bar-bg { background-color: #f3f4f6 !important; } /* Paksa warna abu saat print */
        }
        
        /* Animasi Bar Chart */
        .bar-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
        
        /* Animasi Fade In */
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans flex justify-center items-start min-h-screen p-5 text-[#333] pt-10">

    <div class="bg-white w-full max-w-[900px] rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden report-card mb-10 relative">
        
        <div id="loading" class="absolute inset-0 bg-white z-50 flex flex-col justify-center items-center rounded-[20px]">
            <i class="ph ph-spinner ph-spin text-4xl text-[#4A90E2] mb-3"></i>
            <p class="text-gray-500 font-medium">Menganalisis jawaban Anda...</p>
        </div>

        <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] p-8 md:p-10 text-white relative overflow-hidden">
            <div class="absolute top-[-50%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-[-50%] left-[-10%] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-1">Laporan Hasil Analisis</h1>
                    <p class="opacity-90 text-sm">Tes Minat Bakat (RIASEC)</p>
                </div>
                <div class="text-right hidden md:block">
                    <div class="text-2xl font-bold">{{ Auth::user()->name ?? 'Pengunjung' }}</div>
                    <div class="text-sm opacity-80" id="testDate">-</div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10">
            
            <div class="animate-fade-in" style="animation-delay: 0.2s;">
                <div class="bg-[#EBF5FF] border-l-[6px] border-[#4A90E2] p-6 rounded-r-xl mb-8 shadow-sm flex flex-col md:flex-row gap-6 items-center">
                    
                    <div class="text-center min-w-[120px]">
                        <div class="text-xs text-[#4A90E2] font-bold uppercase tracking-wider mb-1">Kode Dominan</div>
                        <div id="domCode" class="text-4xl md:text-5xl font-extrabold text-[#4A90E2] tracking-widest">---</div>
                    </div>

                    <div class="flex-1 text-center md:text-left border-t md:border-t-0 md:border-l border-blue-100 pt-4 md:pt-0 md:pl-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-2" id="domTitle">Menganalisis...</h3>
                        <p class="text-gray-600 text-sm leading-relaxed" id="domDesc">
                            Sistem sedang menghitung skor jawaban Anda untuk menentukan kepribadian karir yang paling cocok.
                        </p>
                    </div>
                </div>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.4s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="ph-fill ph-chart-bar text-[#4A90E2]"></i> Rincian Skor Potensi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Realistic</span>
                            <span id="scoreR" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barR" class="bar-fill h-full bg-red-500 rounded-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Investigative</span>
                            <span id="scoreI" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barI" class="bar-fill h-full bg-blue-500 rounded-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Artistic</span>
                            <span id="scoreA" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barA" class="bar-fill h-full bg-yellow-400 rounded-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span> Social</span>
                            <span id="scoreS" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barS" class="bar-fill h-full bg-green-500 rounded-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Enterprising</span>
                            <span id="scoreE" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barE" class="bar-fill h-full bg-purple-500 rounded-full"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2 text-sm font-medium">
                            <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-500"></span> Conventional</span>
                            <span id="scoreC" class="text-gray-900 font-bold">0</span>
                        </div>
                        <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                            <div id="barC" class="bar-fill h-full bg-gray-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 animate-fade-in" style="animation-delay: 0.6s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="ph-fill ph-student text-[#4A90E2]"></i> Rekomendasi Jurusan
                </h3>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-2" id="rekomendasiList">
                        <li>Memuat rekomendasi...</li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4 justify-center no-print animate-fade-in" style="animation-delay: 0.8s;">
                <button onclick="window.location.href='{{ route('dashboard') }}'" class="px-6 py-3 border border-gray-300 text-gray-600 hover:border-[#4A90E2] hover:text-[#4A90E2] hover:bg-blue-50 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                    <i class="ph-bold ph-house"></i> Kembali
                </button>
                <button onclick="window.print()" class="px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak PDF
                </button>
            </div>

        </div>
    </div>

    <script>
        // Simulasi Data Hasil Tes (Mockup Frontend)
        // Di aplikasi nyata, data ini diambil dari API backend berdasarkan ID User
     // MENGAMBIL DATA ASLI DARI DATABASE (Dikirim oleh Controller)
        const mockResult = {
            created_at: "{{ $result->created_at }}",
            scores: { 
                R: {{ $result->score_r }}, 
                I: {{ $result->score_i }}, 
                A: {{ $result->score_a }}, 
                S: {{ $result->score_s }}, 
                E: {{ $result->score_e }}, 
                C: {{ $result->score_c }} 
            }, 
            dominant_code: "{{ $result->dominant_code }}"
        };
        // Database Deskripsi Sederhana
        const descriptions = {
            'R': { title: "Realistic (The Doers)", desc: "Anda praktis, mandiri, dan suka bekerja dengan alat atau mesin. Anda lebih suka aktivitas fisik dan bekerja di luar ruangan." },
            'I': { title: "Investigative (The Thinkers)", desc: "Anda analitis, intelektual, dan suka memecahkan masalah. Anda menikmati belajar, meneliti, dan bekerja dengan ide-ide kompleks." },
            'A': { title: "Artistic (The Creators)", desc: "Anda kreatif, ekspresif, dan orisinal. Anda menyukai kebebasan untuk mengekspresikan diri melalui seni, musik, atau tulisan." },
            'S': { title: "Social (The Helpers)", desc: "Anda ramah, penyabar, dan suka membantu orang lain. Anda menikmati mengajar, merawat, dan berinteraksi sosial." },
            'E': { title: "Enterprising (The Persuaders)", desc: "Anda ambisius, energik, dan suka memimpin. Anda pandai berbicara dan suka mempengaruhi orang lain." },
            'C': { title: "Conventional (The Organizers)", desc: "Anda teratur, teliti, dan suka bekerja dengan data. Anda menghargai struktur dan aturan yang jelas." }
        };

        // Database Rekomendasi Jurusan (Berdasarkan Huruf Pertama)
        const recommendations = {
            'R': ["Teknik Mesin", "Teknik Sipil", "Arsitektur", "Pertanian", "Otomotif"],
            'I': ["Kedokteran", "Farmasi", "Ilmu Komputer", "Psikologi", "Biologi"],
            'A': ["Desain Komunikasi Visual", "Sastra", "Seni Musik", "Jurnalisme", "Arsitektur"],
            'S': ["Pendidikan/Keguruan", "Keperawatan", "Hubungan Internasional", "Komunikasi", "Psikologi"],
            'E': ["Manajemen Bisnis", "Hukum", "Ilmu Politik", "Pemasaran", "Perhotelan"],
            'C': ["Akuntansi", "Administrasi Negara", "Perpustakaan", "Statistika", "Manajemen Informatika"]
        };

        function renderReport() {
            // Simulasi Loading 1 Detik
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';

                // 1. Tanggal
                const dateObj = new Date(mockResult.created_at);
                document.getElementById('testDate').innerText = dateObj.toLocaleDateString('id-ID', { 
                    day: 'numeric', month: 'long', year: 'numeric' 
                });

                // 2. Kode Dominan
                const code = mockResult.dominant_code;
                const primaryType = code.charAt(0); // Huruf pertama (paling dominan)

                document.getElementById('domCode').innerText = code;
                document.getElementById('domTitle').innerText = descriptions[primaryType].title;
                document.getElementById('domDesc').innerText = descriptions[primaryType].desc;

                // 3. Update Grafik
                // Asumsi skor maksimal untuk display bar adalah 15 (sesuaikan dengan jumlah soal)
                const maxDisplayScore = 15; 
                updateBar('barR', 'scoreR', mockResult.scores.R, maxDisplayScore);
                updateBar('barI', 'scoreI', mockResult.scores.I, maxDisplayScore);
                updateBar('barA', 'scoreA', mockResult.scores.A, maxDisplayScore);
                updateBar('barS', 'scoreS', mockResult.scores.S, maxDisplayScore);
                updateBar('barE', 'scoreE', mockResult.scores.E, maxDisplayScore);
                updateBar('barC', 'scoreC', mockResult.scores.C, maxDisplayScore);

                // 4. Rekomendasi Jurusan
                const list = document.getElementById('rekomendasiList');
                list.innerHTML = "";
                // Gabungkan rekomendasi dari 2 huruf teratas
                const recs1 = recommendations[code.charAt(0)] || [];
                const recs2 = recommendations[code.charAt(1)] || [];
                // Ambil unik dan slice 5 teratas
                const combinedRecs = [...new Set([...recs1, ...recs2])].slice(0, 6);
                
                combinedRecs.forEach(jurusan => {
                    const li = document.createElement('li');
                    li.innerText = jurusan;
                    list.appendChild(li);
                });

            }, 1000);
        }

        function updateBar(barId, textId, score, max) {
            let percentage = (score / max) * 100;
            if(percentage > 100) percentage = 100;
            
            const bar = document.getElementById(barId);
            const text = document.getElementById(textId);
            
            text.innerText = `${score} Poin`;
            // Trigger animasi CSS
            setTimeout(() => {
                bar.style.width = percentage + "%";
            }, 100);
        }

        // Jalankan render
        renderReport();
    </script>
</body>
</html>