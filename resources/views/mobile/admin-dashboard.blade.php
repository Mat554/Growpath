<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/admin/dashboard.js'])

    <style>
        * {
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }
        html, body {
            overflow-x: hidden;
            overscroll-behavior: none;
        }
        .section { display: none; }
        .section.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        /* Prevent horizontal scroll */
        .no-scroll-x {
            overflow-x: hidden;
        }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans min-h-screen pb-20">

    <!-- MOBILE HEADER -->
    <header class="bg-white px-4 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-gear text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-[#4A90E2] text-lg">Admin Panel</h1>
                    <p class="text-[10px] text-gray-400">Super Admin</p>
                </div>
            </div>
            <!-- Menu Toggle -->
            <button onclick="toggleMobileMenu()" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-500">
                <i class="ph ph-list text-xl"></i>
            </button>
        </div>
    </header>

    <!-- MOBILE SIDEBAR (Hidden by default) -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black/50 z-50 hidden" onclick="toggleMobileMenu()">
        <div class="absolute right-0 top-0 bottom-0 w-[260px] bg-white p-4 overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-bold text-[#4A90E2]">Menu</h2>
                <button onclick="toggleMobileMenu()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <nav class="space-y-1">
                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Utama</div>
                <button onclick="showSection('overview'); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium text-left">
                    <i class="ph ph-squares-four text-lg"></i> Dashboard
                </button>

                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Tes</div>
                <button onclick="showSection('create'); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl font-medium text-left">
                    <i class="ph ph-plus-circle text-lg"></i> Buat Tes
                </button>
                <button onclick="showSection('publish'); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl font-medium text-left">
                    <i class="ph ph-list-checks text-lg"></i> Kelola Soal
                </button>

                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Fitur Baru</div>
                <button onclick="showSection('publisher-v2'); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl font-medium text-left">
                    <i class="ph ph-package text-lg"></i> Publisher
                </button>

                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Laporan</div>
                <a href="{{ route('admin.monitoring') }}" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl font-medium">
                    <i class="ph ph-monitor-play text-lg"></i> Monitoring
                </a>
                <button onclick="showSection('report'); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-xl font-medium text-left">
                    <i class="ph ph-file-text text-lg"></i> Publish Laporan
                </button>
            </nav>

            <form action="{{ route('logout') }}" method="POST" class="mt-6 pt-4 border-t border-gray-100">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium">
                    <i class="ph ph-sign-out text-lg"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        <!-- Greeting -->
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-gray-800">Dashboard Admin</h2>
            <p class="text-gray-500 text-sm">{{ Auth::user()->name ?? 'Administrator' }}</p>
        </div>

        <!-- Overview Section -->
        <div id="overview" class="section active">
            <!-- Stats -->
            <div class="grid grid-cols-1 gap-3 mb-4">
                <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-users"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalSiswa ?? 0 }}</h3>
                        <p class="text-xs text-gray-500">Total Siswa</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#E8F9F5] text-green-600 rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-question"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalSoal ?? 0 }}</h3>
                        <p class="text-xs text-gray-500">Bank Soal</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#FFF4E5] text-[#FF9F43] rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-file-dashed"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $totalLaporan ?? 0 }}</h3>
                        <p class="text-xs text-gray-500">Laporan Masuk</p>
                    </div>
                </div>
            </div>

            <!-- RIASEC Chart -->
            <div class="bg-white p-4 rounded-2xl shadow-sm mb-4">
                <h3 class="text-sm font-bold text-[#4A90E2] mb-4">Rata-rata Skor RIASEC (%)</h3>
                <div class="space-y-3">
                    @foreach(['R' => ['color' => '#EF4444'], 'I' => ['color' => '#3B82F6'], 'A' => ['color' => '#FACC15'], 'S' => ['color' => '#22C55E'], 'E' => ['color' => '#A855F7'], 'C' => ['color' => '#06B6D4']] as $key => $info)
                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="font-bold">{{ $key }}</span>
                            <span>{{ $riasecAvg[$key] ?? 0 }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full">
                            <div class="h-2 rounded-full" style="background-color: {{ $info['color'] }}; width: {{ $riasecAvg[$key] ?? 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Code Distribution -->
            <div class="bg-white p-4 rounded-2xl shadow-sm mb-4">
                <h3 class="text-sm font-bold text-[#4A90E2] mb-3">Distribusi Kode</h3>
                <div class="space-y-2">
                    @forelse($codeDistribution as $code)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="bg-[#4A90E2] text-white text-xs font-bold px-2 py-1 rounded">{{ $code->dominant_code }}</span>
                        <span class="text-sm font-medium">{{ $code->total }}</span>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 text-xs py-4">Belum ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Create Section -->
        <div id="create" class="section">
            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-[#4A90E2]"></i> Buat Pertanyaan RIASEC
                </h3>

                @if(session('success'))
                    <div class="mb-3 p-2 bg-green-50 text-green-600 text-xs rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.question.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Teks Pertanyaan</label>
                        <textarea name="question_text" rows="3" placeholder="Ketik pertanyaan..." required
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2]"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">R</span></label>
                            <input type="text" name="opt_r" placeholder="Realistic" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">I</span></label>
                            <input type="text" name="opt_i" placeholder="Investigative" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">A</span></label>
                            <input type="text" name="opt_a" placeholder="Artistic" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">S</span></label>
                            <input type="text" name="opt_s" placeholder="Social" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">E</span></label>
                            <input type="text" name="opt_e" placeholder="Enterprising" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">C</span></label>
                            <input type="text" name="opt_c" placeholder="Conventional" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#4A90E2]">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#4A90E2] text-white rounded-xl font-semibold text-sm">
                        <i class="ph-fill ph-floppy-disk mr-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>

        <!-- Publish Section -->
        <div id="publish" class="section">
            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-list-checks text-[#4A90E2]"></i> Manajemen Soal
                </h3>
                <div id="questionTable" class="space-y-2">
                    <p class="text-center text-gray-400 text-xs py-8">Memuat data...</p>
                </div>
            </div>
        </div>

        <!-- Publisher V2 Section -->
        <div id="publisher-v2" class="section">
            <div class="bg-white p-4 rounded-2xl shadow-sm mb-4">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-file-code text-[#4A90E2]"></i> Konfigurasi Card
                </h3>

                <div class="mb-3">
                    <label class="block text-xs font-bold text-gray-600 mb-1">Judul Tes</label>
                    <input type="text" id="cardTitle" placeholder="Misal: Tes Minat Bakat X" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-[#4A90E2] focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Target Kelas</label>
                        <select id="cardClass" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white">
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Waktu (Menit)</label>
                        <input type="number" id="cardTime" placeholder="60" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:border-[#4A90E2] focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Mulai</label>
                        <input type="date" id="cardDateStart" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm text-gray-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Berakhir</label>
                        <input type="date" id="cardDateEnd" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm text-gray-600">
                    </div>
                </div>

                <div class="bg-gray-50 p-3 rounded-xl mb-4 flex justify-between items-center">
                    <span class="text-xs font-medium">Total Soal:</span>
                    <span id="totalSelected" class="bg-[#4A90E2] text-white px-2 py-0.5 rounded-full text-xs font-bold">0</span>
                </div>

                <div id="publisherList" class="max-h-[200px] overflow-y-auto mb-4 space-y-2">
                    <p class="text-center text-gray-400 text-xs py-4">Memuat Bank Soal...</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button onclick="startBetaTest()" class="py-3 bg-[#FF9F43] text-white rounded-xl font-semibold text-xs">
                        <i class="ph-fill ph-flask mr-1"></i> Beta Test
                    </button>
                    <button onclick="window.compileCard(event)" class="py-3 bg-[#4A90E2] text-white rounded-xl font-semibold text-xs">
                        <i class="ph-fill ph-rocket-launch mr-1"></i> Publish
                    </button>
                </div>
            </div>
        </div>

        <!-- Report Section -->
        <div id="report" class="section">
            <div class="bg-white p-4 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-file-text text-[#4A90E2]"></i> Publish Laporan
                </h3>
                <div class="space-y-3">
                    @forelse($pendingReports ?? [] as $report)
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-sm">{{ $report->user->name ?? 'Siswa' }}</span>
                            <span class="text-[#4A90E2] font-bold text-sm">{{ $report->dominant_code }}</span>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.laporan.view', $report->id) }}" target="_blank" class="flex-1 py-1.5 bg-gray-200 text-gray-600 rounded-lg text-xs text-center font-medium">
                                Lihat
                            </a>
                            <form action="{{ route('admin.laporan.publish', $report->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-1.5 bg-[#2ECC71] text-white rounded-lg text-xs font-semibold">
                                    Publish
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 text-xs py-8">Semua laporan sudah dipublish!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>

    <!-- Beta Test Modal -->
    <div id="betaModal" class="fixed inset-0 bg-black/60 z-[100] hidden justify-center items-center p-4">
        <div class="bg-white w-full max-w-md rounded-2xl p-5 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-lg">Simulasi RIASEC</h3>
                    <small class="text-gray-400">Mode Preview</small>
                </div>
                <button onclick="closeBetaTest()" class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <div id="simQuizArea">
                <div class="text-sm font-semibold mb-4" id="simQText">Pertanyaan...</div>
                <div class="space-y-2" id="simOptions"></div>
                <div class="flex justify-between items-center mt-4">
                    <span id="simProgress" class="text-xs text-gray-400">Soal 1 / ?</span>
                    <button onclick="nextSimQuestion()" class="px-4 py-2 bg-[#4A90E2] text-white rounded-xl text-xs font-semibold">
                        Lanjut
                    </button>
                </div>
            </div>

            <div id="simResultArea" class="hidden text-center">
                <div class="bg-gray-50 p-4 rounded-xl mb-4">
                    <p class="text-xs text-gray-500 mb-2">Kode Kepribadian:</p>
                    <span id="finalDominance" class="text-2xl font-extrabold text-[#4A90E2] tracking-widest">-</span>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="closeBetaTest()" class="py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-semibold">
                        Revisi
                    </button>
                    <button onclick="confirmPublishFromBeta()" class="py-2 bg-[#4A90E2] text-white rounded-xl text-xs font-semibold">
                        Publish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE BOTTOM NAV -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-30">
        <button onclick="showSection('overview')" class="flex flex-col items-center text-[#4A90E2] py-1 px-3">
            <i class="ph-fill ph-squares-four text-lg"></i>
            <span class="text-[8px] font-medium mt-0.5">Dashboard</span>
        </button>
        <button onclick="showSection('create')" class="flex flex-col items-center text-gray-400 py-1 px-3">
            <i class="ph ph-plus-circle text-lg"></i>
            <span class="text-[8px] font-medium mt-0.5">Buat</span>
        </button>
        <button onclick="showSection('publisher-v2')" class="flex flex-col items-center text-gray-400 py-1 px-3">
            <i class="ph ph-package text-lg"></i>
            <span class="text-[8px] font-medium mt-0.5">Publish</span>
        </button>
        <button onclick="showSection('report')" class="flex flex-col items-center text-gray-400 py-1 px-3">
            <i class="ph ph-file-text text-lg"></i>
            <span class="text-[8px] font-medium mt-0.5">Laporan</span>
        </button>
    </nav>

    <script>
        // Data from Laravel - read by mobile/admin/dashboard.js
        window.globalQuestionsData = @json($questions ?? []);
        window.csrfToken = "{{ csrf_token() }}";
    </script>
</body>
</html>