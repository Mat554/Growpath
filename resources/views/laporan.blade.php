<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/student/report.js'])

    <script>
        // Data from Controller - passed to JS file via window variables
        window.reportData = {
            created_at: "{{ $result->created_at }}",
            scores: {
                R: {{ $result->score_r }},
                I: {{ $result->score_i }},
                A: {{ $result->score_a }},
                S: {{ $result->score_s }},
                E: {{ $result->score_e }},
                C: {{ $result->score_c }}
            },
            dominant_code: "{{ $result->dominant_code }}",
            max_score: {{ $maxScore ?? 60 }}
        };
        window.userRole = "{{ Auth::user()->role ?? 'guest' }}";
    </script>

    <style>
        /* CSS Khusus Print */
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
            .shadow-lg, .shadow-sm { box-shadow: none !important; }
            .report-card { border: none; max-width: 100%; box-shadow: none; }
            .bar-bg { background-color: #f3f4f6 !important; } 
        }
        
        /* Animasi Bar Chart (Dari Desain Lama) */
        .bar-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
        
        /* Animasi Fade In */
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans flex justify-center items-start min-h-screen p-5 text-[#333] pt-10">

    <div id="report-content" class="bg-white w-full max-w-[900px] rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden report-card mb-10 relative">
        
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
                    <div class="text-2xl font-bold">{{ $namaPemilik ?? 'Pengunjung' }}</div>
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
                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $aiData['judul'] ?? 'Menunggu Hasil...' }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ $aiData['deskripsi'] ?? 'Sistem sedang memproses hasil kepribadian Anda.' }}
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

            <div class="mt-10 animate-fade-in" style="animation-delay: 0.5s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="ph-fill ph-chart-line text-[#4A90E2]"></i> Analisis Visual
                </h3>
                <div class="w-full h-[320px] relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <canvas id="riasecChart"></canvas>
                </div>
            </div>

            <div class="mt-10 animate-fade-in" style="animation-delay: 0.6s;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                    <i class="ph-fill ph-student text-[#4A90E2]"></i> Rekomendasi Jurusan
                </h3>
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                        @if(isset($aiData['jurusan']))
                            @foreach($aiData['jurusan'] as $jurusan)
                                <li>{{ $jurusan }}</li>
                            @endforeach
                        @else
                            <li>Gagal memuat rekomendasi jurusan dari AI.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 animate-fade-in" style="animation-delay: 0.7s;">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                        <i class="ph-fill ph-buildings text-[#4A90E2]"></i> Rekomendasi Kampus
                    </h3>
                    <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100 h-full">
                        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                            @if(isset($aiData['kampus']))
                                @foreach($aiData['kampus'] as $kampus)
                                    <li>{{ $kampus }}</li>
                                @endforeach
                            @else
                                <li class="text-gray-500 italic">Belum ada rekomendasi kampus.</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                        <i class="ph-fill ph-lightbulb text-[#FF9F43]"></i> Tips Belajar
                    </h3>
                    <div class="bg-orange-50/50 rounded-xl p-5 border border-orange-100 h-full">
                        <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                            @if(isset($aiData['tips']))
                                @foreach($aiData['tips'] as $tips)
                                    <li>{{ $tips }}</li>
                                @endforeach
                            @else
                                <li class="text-gray-500 italic">Belum ada tips belajar.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div id="action-buttons" class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4 justify-center no-print animate-fade-in" style="animation-delay: 0.8s;">
                @php
                    $role = Auth::user()->role;
                    if ($role === 'admin') {
                        $ruteKembali = route('admin.dashboard');
                    } elseif ($role === 'ortu') {
                        $ruteKembali = route('dashboard.ortu');
                    } else {
                        $ruteKembali = route('dashboard');
                    }
                @endphp

                <button onclick="window.location.href='{{ $ruteKembali }}'" class="px-6 py-3 border border-gray-300 text-gray-600 hover:border-[#4A90E2] hover:text-[#4A90E2] hover:bg-blue-50 rounded-xl font-semibold transition-all flex items-center justify-center gap-2">
                    <i class="ph-bold ph-house"></i> Kembali
                </button>

                @if(in_array(Auth::user()->role, ['admin', 'ortu']))
                <button onclick="downloadPDF()" class="px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-download-simple"></i> Download PDF
                </button>
                @endif
            </div>

        </div>
    </div>

</body>
</html>