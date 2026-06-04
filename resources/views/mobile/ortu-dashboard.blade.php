<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Orang Tua - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/parent/dashboard.js'])

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
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        html, body { overflow-x: hidden; overscroll-behavior: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        .bar-fill { transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
        .tab-btn { transition: all 0.2s ease; }
        .tab-btn.active { background: #EBF5FF; color: #4A90E2; border-color: #4A90E2; }
    </style>
</head>

<body class="bg-[#F4F7F6] font-sans min-h-screen pb-24">

    <!-- MOBILE HEADER -->
    <header class="bg-white px-4 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-[#4A90E2] to-[#6DD5FA] rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-brain text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-gray-800 text-base">Growpath</h1>
                    <p class="text-[9px] text-gray-400">Wali Murid</p>
                </div>
            </div>
            <a href="{{ route('profile.ortu') }}" class="w-9 h-9 bg-gray-50 rounded-xl flex items-center justify-center">
                <i class="ph ph-user text-gray-500"></i>
            </a>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        <!-- Greeting -->
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Halo, {{ Auth::user()->name }}!</h2>
            <p class="text-xs text-gray-500">
                @if(isset($anak) && $anak)
                    Monitoring: {{ $anak->name }}
                @else
                    Hubungkan akun anak
                @endif
            </p>
        </div>

        @if(isset($result) && $result)
            <!-- Hero Result Card -->
            <div class="bg-gradient-to-br from-[#4A90E2] to-[#5BA3F5] rounded-2xl p-5 text-white mb-4 relative overflow-hidden">
                <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-3">
                        <div id="domCode" class="text-4xl font-black tracking-wider">---</div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold leading-tight">{{ $aiData['judul'] ?? 'Profil Minat' }}</p>
                            <p class="text-[10px] opacity-80 mt-0.5">{{ $anak->name ?? 'Siswa' }}</p>
                        </div>
                    </div>
                    <p class="text-[10px] opacity-70">{{ $aiData['deskripsi'] ?? '' }}</p>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="flex gap-1 mb-4 overflow-x-auto -mx-4 px-4 pb-2">
                <button onclick="switchTab('scores')" class="tab-btn active px-4 py-2 text-xs font-semibold rounded-full border border-gray-200 whitespace-nowrap" id="tabScores">
                    Skor
                </button>
                <button onclick="switchTab('chart')" class="tab-btn px-4 py-2 text-xs font-medium rounded-full border border-gray-200 whitespace-nowrap text-gray-500" id="tabChart">
                    Grafik
                </button>
                <button onclick="switchTab('jurusan')" class="tab-btn px-4 py-2 text-xs font-medium rounded-full border border-gray-200 whitespace-nowrap text-gray-500" id="tabJurusan">
                    Jurusan
                </button>
                <button onclick="switchTab('kampus')" class="tab-btn px-4 py-2 text-xs font-medium rounded-full border border-gray-200 whitespace-nowrap text-gray-500" id="tabKampus">
                    Kampus
                </button>
                <button onclick="switchTab('tips')" class="tab-btn px-4 py-2 text-xs font-medium rounded-full border border-gray-200 whitespace-nowrap text-gray-500" id="tabTips">
                    Tips
                </button>
            </div>

            <!-- Tab Content Container -->
            <div id="tabContent">
                <!-- Scores Tab (Default) -->
                <div id="contentScores" class="tab-content">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="space-y-3">
                            @php $colors = ['R' => '#EF4444', 'I' => '#3B82F6', 'A' => '#FACC15', 'S' => '#22C55E', 'E' => '#A855F7', 'C' => '#64748B']; @endphp
                            @php $names = ['R' => 'Realistic', 'I' => 'Investigative', 'A' => 'Artistic', 'S' => 'Social', 'E' => 'Enterprising', 'C' => 'Conventional']; @endphp
                            @foreach(['R', 'I', 'A', 'S', 'E', 'C'] as $key)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $colors[$key] }}">
                                    {{ $key }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[11px] text-gray-600">{{ $names[$key] }}</span>
                                        <span id="score{{ $key }}" class="text-sm font-bold text-gray-800">0</span>
                                    </div>
                                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div id="bar{{ $key }}" class="bar-fill h-full rounded-full" style="background-color: {{ $colors[$key] }}"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Chart Tab -->
                <div id="contentChart" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="h-[200px] bg-gray-50 rounded-xl p-3">
                            <canvas id="riasecChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Jurusan Tab -->
                <div id="contentJurusan" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        @if(isset($aiData['jurusan']) && count($aiData['jurusan']) > 0)
                            <div class="space-y-2">
                                @foreach($aiData['jurusan'] as $jurusan)
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl">
                                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="ph-fill ph-check text-white text-[10px]"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $jurusan }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <i class="ph ph-graduation-cap text-3xl mb-2"></i>
                                <p class="text-xs">Belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Kampus Tab -->
                <div id="contentKampus" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        @if(isset($aiData['kampus']) && count($aiData['kampus']) > 0)
                            <div class="space-y-2">
                                @foreach($aiData['kampus'] as $kampus)
                                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl">
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                        <i class="ph-fill ph-map-pin text-white text-[10px]"></i>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $kampus }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <i class="ph ph-buildings text-3xl mb-2"></i>
                                <p class="text-xs">Belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips Tab -->
                <div id="contentTips" class="tab-content hidden">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        @if(isset($aiData['tips']) && count($aiData['tips']) > 0)
                            <div class="space-y-2">
                                @foreach($aiData['tips'] as $tips)
                                <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-xl">
                                    <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center flex-shrink-0">
                                        <i class="ph-fill ph-lightbulb text-white text-[10px]"></i>
                                    </div>
                                    <span class="text-sm text-gray-700 leading-relaxed">{{ $tips }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-400">
                                <i class="ph ph-lightbulb text-3xl mb-2"></i>
                                <p class="text-xs">Belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <button onclick="downloadPDF()" class="w-full mt-4 py-3.5 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-2xl font-semibold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                <i class="ph-bold ph-download-simple text-lg"></i>
                Download PDF
            </button>

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-clipboard-text text-3xl text-gray-300"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Belum Ada Laporan</h3>
                <p class="text-xs text-gray-500 mb-5">
                    Anak Anda belum menyelesaikan kuesioner atau belum terhubung dengan akun siswa.
                </p>
                @if(!isset($anak) || !$anak)
                    <a href="{{ route('profile.ortu') }}" class="inline-block px-5 py-2.5 bg-[#4A90E2] text-white rounded-xl text-xs font-semibold">
                        Hubungkan Akun
                    </a>
                @endif
            </div>
        @endif
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-50 safe-area-bottom">
        <a href="{{ route('dashboard.ortu') }}" class="flex flex-col items-center text-[#4A90E2] py-1 px-4">
            <i class="ph-fill ph-squares-four text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Home</span>
        </a>
        <a href="{{ route('profile.ortu') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-user text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Profil</span>
        </a>
    </nav>

    <!-- PDF Overlay -->
    <div id="pdf-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:99999; justify-content:center; align-items:center;">
        <div style="background:white; border-radius:16px; padding:2rem; text-align:center;">
            <div style="width:40px; height:40px; border:3px solid #EBF5FF; border-top-color:#4A90E2; border-radius:50%; animation:spin 0.8s linear infinite; margin:0 auto 1rem;"></div>
            <p class="font-semibold text-sm">Menyiapkan PDF...</p>
        </div>
    </div>

</body>
</html>