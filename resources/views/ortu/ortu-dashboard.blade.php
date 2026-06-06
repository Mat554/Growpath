<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/parent/dashboard.js', 'resources/js/parent/dashboard-report.js'])

    @if(isset($result) && $result)
    <script>
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
            dominant_code: "{{ $result->dominant_code }}"
        };
    </script>
    @endif

    <style>
        /* ===== PDF DOWNLOAD OVERLAY ===== */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes pulse-text {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        #pdf-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 99999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            pointer-events: none;
        }
        #pdf-overlay.active {
            display: flex;
            pointer-events: all;
        }
        #pdf-overlay-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
            min-width: 300px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
        }
        #pdf-spinner {
            width: 52px;
            height: 52px;
            border: 4px solid #EBF5FF;
            border-top-color: #4A90E2;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        #pdf-overlay-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        #pdf-overlay-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: -0.5rem 0 0;
            animation: pulse-text 1.5s ease-in-out infinite;
            font-family: 'Poppins', sans-serif;
        }
        #pdf-progress-track {
            width: 100%;
            height: 6px;
            background: #f3f4f6;
            border-radius: 99px;
            overflow: hidden;
        }
        #pdf-progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, #4A90E2, #6DD5FA);
            border-radius: 99px;
            transition: width 0.4s ease;
        }

        /* ===== NOTIFICATION ANIMATION ===== */
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-15deg); }
        }
        .animate-ring {
            animation: ring 0.5s ease-in-out infinite;
        }

        /* ===== REPORT ANIMATIONS ===== */
        .bar-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden text-[#333]">

    {{-- PDF Overlay: direct child of body --}}
    <div id="pdf-overlay">
        <div id="pdf-overlay-card">
            <div id="pdf-spinner"></div>
            <p id="pdf-overlay-title">Menyiapkan PDF...</p>
            <p id="pdf-overlay-subtitle">Mohon tunggu sebentar</p>
            <div id="pdf-progress-track">
                <div id="pdf-progress-bar"></div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20 shadow-[0_0_20px_rgba(0,0,0,0.03)]">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> 
            <span class="text-xl font-bold text-gray-800 tracking-tight">Grow<span class="text-[#4A90E2]">path</span></span>
        </div>
        
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all shadow-sm">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-8 overflow-y-auto">

        {{-- Header --}}
        <div class="dashboard-header flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Halo, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang di portal pemantauan minat bakat.</p>
            </div>
            <div class="flex items-center justify-end gap-4 ml-auto">
                {{-- Notification Bell --}}
                <div class="relative" id="notificationDropdown">
                    <button onclick="toggleNotifications()" class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-[#4A90E2] transition-colors relative cursor-pointer border border-gray-100 focus:outline-none">
                        <i id="bellIcon" class="ph-fill ph-bell text-xl {{ isset($anak) && $anak ? 'text-[#4A90E2] animate-ring' : '' }}"></i>
                        @if(isset($anak) && $anak)
                            <span id="notifBadge" class="absolute top-2.5 right-3 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </button>
                    <div id="notificationMenu" class="hidden absolute right-0 mt-3 w-[320px] bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] border border-gray-100 z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                        <div class="p-4 border-b border-gray-100 bg-[#F8FAFC]">
                            <h3 class="font-bold text-gray-800 text-sm">Notifikasi</h3>
                        </div>
                        <div class="max-h-[300px] overflow-y-auto">
                            @if(isset($anak) && $anak)
                                <div class="p-4 border-b border-gray-50 flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#EBF5FF] text-[#4A90E2] flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="ph-fill ph-student text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800 leading-snug">
                                            Terhubung dengan: <strong class="text-[#4A90E2]">{{ $anak->name }}</strong>.
                                        </p>
                                        <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" onsubmit="return confirm('Berhenti memantau akun anak ini?')">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-red-500 font-semibold hover:underline mt-2 bg-transparent border-none p-0 cursor-pointer">
                                                Lepas Koneksi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="p-8 text-center text-gray-400 text-sm">Tidak ada notifikasi</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-white px-5 py-2.5 rounded-full shadow-sm border border-gray-100 flex items-center gap-2.5 text-sm font-semibold text-[#4A90E2]">
                    <i class="ph-fill ph-users text-lg"></i>
                    <span>Wali Murid</span>
                </div>
            </div>
        </div>

        {{-- Report Content --}}
        <div class="w-full flex justify-center">
            @if(isset($result) && $result)
                <div id="report-content" class="bg-white w-full max-w-[900px] rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden report-card mb-10 relative">

                    {{-- Loading Spinner --}}
                    <div id="loading" class="absolute inset-0 bg-white z-50 flex flex-col justify-center items-center rounded-[20px]">
                        <i class="ph ph-spinner ph-spin text-4xl text-[#4A90E2] mb-3"></i>
                        <p class="text-gray-500 font-medium">Menganalisis data laporan...</p>
                    </div>

                    {{-- Blue Header --}}
                    <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] p-8 md:p-10 text-white relative overflow-hidden">
                        <div class="absolute top-[-50%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-[-50%] left-[-10%] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="relative z-10 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold mb-1">Laporan Hasil Analisis</h1>
                                <p class="opacity-90 text-sm">Tes Minat Bakat (RIASEC) - {{ $anak->name ?? 'Siswa' }}</p>
                            </div>
                            <div class="text-right hidden md:block">
                                <div class="text-2xl font-bold">{{ $anak->name ?? $namaPemilik ?? 'Siswa' }}</div>
                                <div class="text-sm opacity-80" id="testDate">-</div>
                            </div>
                        </div>
                    </div>

                    {{-- Report Body --}}
                    <div class="p-8 md:p-10">

                        {{-- Kode Dominan Card --}}
                        <div class="animate-fade-in" style="animation-delay: 0.2s;">
<div class="bg-[#EBF5FF] border-l-[6px] border-[#4A90E2] p-6 mb-8 shadow-sm flex flex-col md:flex-row gap-6 items-center -mx-8 md:-mx-10 px-8 md:px-10">                                <div class="text-center min-w-[120px]">
                                    <div class="text-xs text-[#4A90E2] font-bold uppercase tracking-wider mb-1">Kode Dominan</div>
                                    <div id="domCode" class="text-4xl md:text-5xl font-extrabold text-[#4A90E2] tracking-widest">---</div>
                                </div>
                                <div class="flex-1 text-center md:text-left border-t md:border-t-0 md:border-l border-blue-100 pt-4 md:pt-0 md:pl-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $aiData['judul'] ?? 'Menunggu Hasil...' }}</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ $aiData['deskripsi'] ?? 'Sistem sedang memproses hasil kepribadian anak Anda.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Score Bars --}}
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

                        {{-- Chart --}}
                        <div class="mt-10 animate-fade-in" style="animation-delay: 0.5s;">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="ph-fill ph-chart-line text-[#4A90E2]"></i> Analisis Visual
                            </h3>
                            <div class="w-full h-[320px] relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <canvas id="riasecChart"></canvas>
                            </div>
                        </div>

                        {{-- Jurusan --}}
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

                        {{-- Kampus & Tips --}}
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

                        {{-- Download Button --}}
                        <div id="action-buttons" class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4 justify-center no-print animate-fade-in" style="animation-delay: 0.8s;">
                            <button onclick="downloadPDF()" class="px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-download-simple"></i> Download PDF
                            </button>
                        </div>

                    </div>
                </div>

            @else
                <div class="bg-white w-full max-w-[900px] p-10 rounded-[20px] shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-4xl mb-4">
                        <i class="ph-fill ph-file-dashed"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Belum Tersedia</h3>
                    <p class="text-gray-500 text-sm max-w-[400px]">
                        Saat ini anak Anda belum menyelesaikan kuesioner minat bakat, atau Anda belum terhubung dengan akun siswa. Laporan akan otomatis muncul di sini setelah tes diselesaikan.
                    </p>
                </div>
            @endif
        </div>
    </main>

    {{-- Mobile Nav --}}
    <div class="mobile-nav fixed bottom-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around z-50">
        <a href="{{ route('dashboard.ortu') }}" class="flex flex-col items-center text-[#4A90E2]">
            <i class="ph-fill ph-squares-four text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <a href="{{ route('profile.ortu') }}" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-user text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Profil</span>
        </a>
    </div>

    </body>
</html>