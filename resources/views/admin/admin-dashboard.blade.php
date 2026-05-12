<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin-dashboard.js'])

    <style>
        /* Animasi Transisi Tab */
        .section { display: none; animation: fadeIn 0.4s ease-out; }
        .section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Custom Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f8f9fa; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden text-[#333]">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-200 p-6 z-50 shadow-[0_0_20px_rgba(0,0,0,0.03)]">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-8">
            <i class="ph-fill ph-gear text-2xl"></i> Admin Panel
        </div>

        <div class="flex-1 flex flex-col gap-1 overflow-y-auto custom-scroll pr-2">
            
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Utama</div>
            <button onclick="showSection('overview')" id="nav-overview" class="w-full flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </button>
            
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Tes</div>
            <button onclick="showSection('create')" id="nav-create" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-plus-circle text-lg"></i> Buat Tes
            </button>
            <button onclick="showSection('publish')" id="nav-publish" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-list-checks text-lg"></i> Kelola Soal
            </button>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Fitur Baru</div>
            <button onclick="showSection('publisher-v2')" id="nav-publisher-v2" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-package text-lg"></i> Publisher & Beta
            </button>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Laporan</div>
            <a href="{{ route('admin.monitoring') }}" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-monitor-play text-lg"></i> Monitoring
            </a>
            <button onclick="showSection('report')" id="nav-report" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-file-text text-lg"></i> Publish Laporan
            </button>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-4">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto bg-[#F4F7F6]">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Dashboard Admin</h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang kembali, {{ Auth::user()->name ?? 'Administrator' }}.</p>
            </div>
            <div class="bg-white px-5 py-2.5 rounded-full shadow-sm flex items-center gap-2.5 text-sm font-semibold text-[#4A90E2]">
                <i class="ph-fill ph-user-gear text-lg"></i> Super Admin
            </div>
        </div>

        <div id="overview" class="section active">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 border border-gray-100">
                    <div class="w-16 h-16 bg-[#EBF5FF] text-[#4A90E2] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-users"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="statUsers">{{ $totalSiswa ?? 0 }}</h3>
                        <p class="text-gray-500 text-sm">Total Siswa</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 border border-gray-100">
                    <div class="w-16 h-16 bg-[#E8F9F5] text-[#16A34A] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-question"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="statQuestions">{{ $totalSoal ?? 0 }}</h3>
                        <p class="text-gray-500 text-sm">Bank Soal</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5 border border-gray-100">
                    <div class="w-16 h-16 bg-[#FFF4E5] text-[#FF9F43] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-file-dashed"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="statReports">{{ $totalLaporan ?? 0 }}</h3>
                        <p class="text-gray-500 text-sm">Laporan Masuk</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm mb-6 border border-gray-100" style="border-radius: 20px;">
                <h3 class="text-lg font-bold mb-6 flex items-center gap-2" style="color: #4A90E2;">
                    Rata-rata Skor RIASEC (%)
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-4">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Realistic (R)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #EF4444; width: 16.4%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">16.4%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Investigative (I)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #3B82F6; width: 16.3%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">16.3%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Artistic (A)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #FACC15; width: 15.6%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">15.6%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Social (S)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #22C55E; width: 17.5%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">17.5%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Enterprising (E)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #A855F7; width: 16.6%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">16.6%</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-800 w-32">Conventional (C)</span>
                        <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                            <div class="h-3 rounded-full" style="background-color: #06B6D4; width: 17.6%"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-800 w-12 text-right">17.6%</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1.5rem; margin-bottom: 2rem; align-items: stretch; flex-wrap: nowrap; overflow-x: auto;">
                
                <div class="bg-white p-6 shadow-sm border border-gray-100 flex flex-col" style="flex: 1; min-width: 300px; border-radius: 20px; height: 340px;">
                    <h3 class="text-lg font-bold mb-4" style="color: #4A90E2;">Distribusi Kode Hasil</h3>
                    
                    <div class="flex-1 overflow-y-auto custom-scroll pr-2 border border-gray-100" style="border-radius: 8px;">
                        <table class="w-full text-left border-collapse">
                            <thead style="background-color: #EBF5FF; position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th class="p-3 font-bold text-sm" style="color: #333;">Kode RIASEC</th>
                                    <th class="p-3 font-bold text-sm text-right" style="color: #333;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-50">
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">SCR</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">245</td>
                                </tr>
                                <tr class="border-b border-gray-50">
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">SEC</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">242</td>
                                </tr>
                                <tr class="border-b border-gray-50">
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">RIA</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">173</td>
                                </tr>
                                <tr class="border-b border-gray-50">
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">RSC</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">160</td>
                                </tr>
                                <tr class="border-b border-gray-50">
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">SCE</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">152</td>
                                </tr>
                                <tr>
                                    <td class="p-3"><span class="text-white font-bold text-xs tracking-wide inline-block" style="background-color: #4A90E2; padding: 4px 12px; border-radius: 6px;">RIS</span></td>
                                    <td class="p-3 text-right text-gray-800 font-medium text-sm">146</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 shadow-sm border border-gray-100 flex flex-col" style="flex: 1; min-width: 300px; border-radius: 20px; height: 340px;">
                    <h3 class="text-lg font-bold mb-6" style="color: #4A90E2;">Distribusi Kelas</h3>
                    
                    <div class="flex flex-col gap-8 flex-1 justify-center pb-2">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-gray-800 w-28">Kelas 10</span>
                            <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                                <div class="h-3 rounded-full" style="background-color: #4A90E2; width: 85%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800 w-12 text-right">3426</span>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-gray-800 w-28">Kelas 11</span>
                            <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                                <div class="h-3 rounded-full" style="background-color: #4A90E2; width: 40%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800 w-12 text-right">1518</span>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-gray-800 w-28">Kelas 12</span>
                            <div class="flex-1 rounded-full h-3" style="background-color: #F3F4F6;">
                                <div class="h-3 rounded-full" style="background-color: #4A90E2; width: 55%"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-800 w-12 text-right">2098</span>
                        </div>
                    </div>
                </div>

            </div>
            
          <div class="bg-white p-8 shadow-sm border border-gray-100" style="border-radius: 20px;">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-info text-[#4A90E2]"></i> Panduan Admin
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                        <i class="ph-fill ph-number-circle-one text-3xl text-[#4A90E2] mt-0.5"></i>
                        <div class="text-sm text-gray-600">
                            <strong class="text-gray-800 block mb-1">1. Buat Soal RIASEC</strong>
                            Input teks pertanyaan beserta 6 opsi jawaban yang mewakili masing-masing kategori (Realistic, Investigative, Artistic, Social, Enterprising, Conventional).
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                        <i class="ph-fill ph-number-circle-two text-3xl text-[#4A90E2] mt-0.5"></i>
                        <div class="text-sm text-gray-600">
                            <strong class="text-gray-800 block mb-1">2. Beta Test & Publish</strong>
                            Konfigurasi kartu ujian dan lakukan simulasi (Beta Test) untuk memastikan perhitungan 3 kode dominan berjalan lancar sebelum di-Publish.
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                        <i class="ph-fill ph-number-circle-three text-3xl text-[#4A90E2] mt-0.5"></i>
                        <div class="text-sm text-gray-600">
                            <strong class="text-gray-800 block mb-1">3. Monitoring Pengerjaan</strong>
                            Pantau status siswa yang sedang mengerjakan ujian secara real-time pada menu Monitoring untuk melihat progres penyelesaian soal.
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                        <i class="ph-fill ph-number-circle-four text-3xl text-[#4A90E2] mt-0.5"></i>
                        <div class="text-sm text-gray-600">
                            <strong class="text-gray-800 block mb-1">4. Validasi & Publish Laporan</strong>
                            Review detail skor dan kode kepribadian final siswa. Klik Publish agar laporan dan rekomendasi jurusan dapat diakses oleh siswa dan orang tua.
                        </div>
                    </div>
                </div>
            </div>

        <div id="create" class="section">
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-[#4A90E2]"></i> Buat Pertanyaan RIASEC
                </div>
               @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 text-green-600 text-sm rounded-lg border border-green-100 flex items-center gap-2">
                        <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
                    </div>
                @endif

                <form id="createForm" method="POST" action="{{ route('admin.question.store') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teks Pertanyaan (Studi Kasus / Pilihan)</label>
                        <textarea name="question_text" id="qText" rows="2" placeholder="Contoh: Kegiatan apa yang paling Anda sukai di waktu luang?" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10"></textarea>
                    </div>
                    
                    <small class="block mb-4 text-gray-500 text-xs">Isi opsi jawaban sesuai kategori RIASEC:</small>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">R</span>ealistic</label>
                            <input type="text" name="opt_r" id="optR" placeholder="Contoh: Memperbaiki mesin" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">I</span>nvestigative</label>
                            <input type="text" name="opt_i" id="optI" placeholder="Contoh: Membaca jurnal sains" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">A</span>rtistic</label>
                            <input type="text" name="opt_a" id="optA" placeholder="Contoh: Melukis" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">S</span>ocial</label>
                            <input type="text" name="opt_s" id="optS" placeholder="Contoh: Mengajar teman" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">E</span>nterprising</label>
                            <input type="text" name="opt_e" id="optE" placeholder="Contoh: Menjual produk" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">C</span>onventional</label>
                            <input type="text" name="opt_c" id="optC" placeholder="Contoh: Menyusun arsip" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                    </div>

                    <div class="mt-8 text-right">
                        <button type="submit" class="px-6 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30 flex items-center gap-2 ml-auto">
                            <i class="ph-fill ph-floppy-disk text-lg"></i> Simpan RIASEC
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div id="publish" class="section">
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-list-checks text-[#4A90E2]"></i> Manajemen Soal
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm w-3/5">Pertanyaan</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Status</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="questionTable">
                            <tr><td colspan="3" class="p-8 text-center text-gray-400">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="publisher-v2" class="section">
            <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6">
                
                <div>
                    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500"><i class="ph-fill ph-info"></i> Pilih soal untuk kartu ujian.</span>
                    </div>

                    <div id="publisherList" class="custom-scroll max-h-[600px] overflow-y-auto pr-2">
                        <div class="text-center p-8 text-gray-400">Memuat Bank Soal...</div>
                    </div>
                </div>

                <div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-t-[6px] border-[#4A90E2] sticky top-0">
                        <div class="text-lg font-semibold text-gray-800 mb-4 pb-4 border-b border-gray-100 flex items-center gap-2">
                            <i class="ph-fill ph-file-code text-[#4A90E2]"></i> Konfigurasi Card
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Judul Tes</label>
                            <input type="text" id="cardTitle" placeholder="Misal: Tes Minat Bakat X" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#4A90E2] focus:outline-none">
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Target Kelas</label>
                                <select id="cardClass" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#4A90E2] bg-white">
                                    <option value="10">Kelas 10</option>
                                    <option value="11">Kelas 11</option>
                                    <option value="12">Kelas 12</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Waktu (Menit)</label>
                                <input type="number" id="cardTime" placeholder="60" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#4A90E2] focus:outline-none">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Tanggal Pelaksanaan</label>
                            <input type="date" id="cardDate" class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#4A90E2] focus:outline-none text-gray-600">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl mb-6 flex justify-between items-center">
                            <strong class="text-sm text-gray-700">Total Soal:</strong>
                            <span id="totalSelected" class="bg-[#4A90E2] text-white px-3 py-1 rounded-full text-xs font-bold">0 Item</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="startBetaTest()" class="py-3 bg-[#FF9F43] hover:bg-orange-500 text-white rounded-xl font-semibold text-sm transition-all flex justify-center items-center gap-2">
                                <i class="ph-fill ph-flask"></i> Beta Test
                            </button>
                            
                          <button onclick="window.compileCard(event)" class="py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all flex justify-center items-center gap-2">
                                <i class="ph-fill ph-rocket-launch"></i> Publish
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="monitoring" class="section">
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-monitor-play text-[#4A90E2]"></i> Monitoring Pengerjaan Siswa
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Nama Siswa</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Kelas</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Status</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Progres</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="p-4 border-b border-gray-50">Budi Santoso</td>
                                <td class="p-4 border-b border-gray-50">12 IPA 1</td>
                                <td class="p-4 border-b border-gray-50"><span class="px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase">Sedang Mengerjakan</span></td>
                                <td class="p-4 border-b border-gray-50 text-gray-600">Soal 5/20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

       <div id="report" class="section">
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-file-text text-[#4A90E2]"></i> Validasi & Publish Laporan
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Nama Siswa</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Kode Dominan</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Detail Skor (Top 3)</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Status</th>
                                <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm text-right">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse($pendingReports ?? [] as $report)
                            <tr>
                                <td class="p-4 border-b border-gray-50">{{ $report->user->name ?? 'Siswa' }}</td>
                                <td class="p-4 border-b border-gray-50 text-[#4A90E2] font-bold">{{ $report->dominant_code }}</td>
                                <td class="p-4 border-b border-gray-50 text-sm text-gray-600">S:{{ $report->score_s }}, E:{{ $report->score_e }}, C:{{ $report->score_c }}</td>
                                <td class="p-4 border-b border-gray-50">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs font-bold uppercase">Review</span>
                                </td>
                                <td class="p-4 border-b border-gray-50 flex justify-end gap-2">
                                    <a href="{{ route('admin.laporan.view', $report->id) }}" target="_blank" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                                        Lihat
                                    </a>
                                    
                                    <form action="{{ route('admin.laporan.publish', $report->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-[#2ECC71] text-white rounded-lg text-xs font-semibold hover:bg-green-600 transition-all shadow-sm">
                                            Publish
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400">
                                    <i class="ph-fill ph-check-circle text-4xl mb-2 text-gray-300"></i><br>
                                    Semua laporan sudah divalidasi dan di-publish!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    
    <div id="betaModal" class="fixed inset-0 bg-black/60 z-[100] hidden justify-center items-center backdrop-blur-sm transition-all">
        <div class="bg-white w-[95%] max-w-[700px] p-8 rounded-2xl shadow-2xl relative max-h-[90vh] overflow-y-auto">
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 m-0">Simulasi RIASEC</h3>
                    <small class="text-gray-400">Mode Pratinjau (Preview)</small>
                </div>
                <div class="bg-[#FFF4E5] text-[#FF9F43] px-3 py-1 rounded-full text-sm font-bold flex items-center gap-1">
                    <i class="ph-fill ph-timer"></i> <span id="simTimer">00:00</span>
                </div>
            </div>

            <div id="simQuizArea">
                <div class="text-lg font-semibold text-gray-800 mb-6" id="simQText">Pertanyaan...</div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="simOptions">
                    </div>
                
                <div class="mt-8 flex justify-between items-center">
                    <span id="simProgress" class="text-gray-400 text-sm">Soal 1 / ?</span>
                    <button onclick="nextSimQuestion()" class="px-5 py-2.5 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all flex items-center gap-2">
                        Lanjut <i class="ph-bold ph-caret-right"></i>
                    </button>
                </div>
            </div>

            <div id="simResultArea" class="hidden text-center">
                <div class="bg-gray-50 p-6 rounded-2xl mb-6">
                    <i class="ph-fill ph-seal-check text-5xl text-[#2ECC71] mb-2 inline-block"></i>
                    <h3 class="text-xl font-bold text-gray-800">Simulasi Selesai</h3>
                    <p class="text-sm text-gray-500 mb-4">Profil minat RIASEC Anda:</p>
                    
                    <div class="grid grid-cols-6 gap-2 mb-4">
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreR">0</div><div class="text-[10px] text-gray-400">R</div></div>
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreI">0</div><div class="text-[10px] text-gray-400">I</div></div>
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreA">0</div><div class="text-[10px] text-gray-400">A</div></div>
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreS">0</div><div class="text-[10px] text-gray-400">S</div></div>
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreE">0</div><div class="text-[10px] text-gray-400">E</div></div>
                        <div class="bg-white p-2 rounded border border-gray-100"><div class="text-lg font-bold text-[#4A90E2]" id="scoreC">0</div><div class="text-[10px] text-gray-400">C</div></div>
                    </div>
                    
                    <div class="bg-white p-4 rounded-xl border border-dashed border-gray-300">
                        <div class="text-sm text-gray-500 mb-1">Kode Kepribadian (Top 3):</div>
                        <span id="finalDominance" class="text-2xl font-extrabold text-[#4A90E2] tracking-[4px]">-</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button onclick="closeBetaTest()" class="py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold text-sm transition-all flex justify-center items-center gap-2">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i> Revisi
                    </button>
                    <button onclick="confirmPublishFromBeta()" class="py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all flex justify-center items-center gap-2">
                        <i class="ph-fill ph-check-circle"></i> Publish
                    </button>
                </div>
            </div>

        </div>
    </div>

  <script>
        // 1. Kirim data soal ke Javascript
        window.globalQuestionsData = @json($questions ?? []);
        
        // 2. Kirim data session tab
        window.activeTabSession = "{{ session('tab') ?? '' }}";
        
        // 3. Kirim Token CSRF
        window.csrfToken = "{{ csrf_token() }}";

        // ==========================================
        // INISIALISASI TIGA GRAFIK CHART.JS
        // ==========================================
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. Grafik Garis (Kiri): User Activity
            const ctxActivity = document.getElementById('userActivityChart');
            if (ctxActivity) {
                new Chart(ctxActivity.getContext('2d'), {
                    type: 'line', 
                    data: {
                        labels: ['8 May', '9 May', '10 May', '11 May', '12 May', '13 May', '14 May', '15 May'],
                        datasets: [{
                            label: 'Users completed',
                            data: [480, 550, 600, 1000, 1050, 1100, 1180, 1200], 
                            borderColor: '#4A90E2', 
                            backgroundColor: '#4A90E2',
                            pointBackgroundColor: '#4A90E2',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            borderWidth: 2,
                            tension: 0.4 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 1250, 
                                ticks: { stepSize: 250 },
                                border: { display: false },
                                grid: { color: '#f0f0f0' }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 2. Grafik Lingkaran (Tengah): Progress Tes Aktif (Siswa Selesai)
            const ctxCompletion = document.getElementById('completionChart');
            if (ctxCompletion) {
                new Chart(ctxCompletion.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Remaining'],
                        datasets: [{
                            data: [8, 92], // 8% Selesai, 92% Belum
                            backgroundColor: ['#4A90E2', '#F3F4F6'],
                            borderWidth: 0,
                            cutout: '75%' 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
            }

            // 3. Grafik Lingkaran (Kanan): Status Total Tes (Berjalan vs Lainnya)
            const ctxActiveTest = document.getElementById('activeTestChart');
            if (ctxActiveTest) {
                new Chart(ctxActiveTest.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Berjalan', 'Lainnya'],
                        datasets: [{
                            data: [3, 7], // 3 Berjalan, 7 Selesai/Draft
                            backgroundColor: ['#FF9F43', '#F3F4F6'], // Warna Oranye untuk "Berjalan"
                            borderWidth: 0,
                            cutout: '75%' 
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { enabled: false } }
                    }
                });
            }
        });
    </script>
</body>
</html>
