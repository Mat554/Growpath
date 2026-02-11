<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Animasi Transisi Tab */
        .section { display: none; animation: fadeIn 0.4s ease-out; }
        .section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Custom Scrollbar untuk List Soal */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #aaa; }
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
            
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Kuesioner</div>
            <button onclick="showSection('create')" id="nav-create" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-plus-circle text-lg"></i> Buat Kuesioner
            </button>
            <button onclick="showSection('publish')" id="nav-publish" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-list-checks text-lg"></i> Kelola Soal
            </button>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Fitur Baru</div>
            <button onclick="showSection('publisher-v2')" id="nav-publisher-v2" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-package text-lg"></i> Publisher & Beta
            </button>

            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Laporan</div>
            <button onclick="showSection('monitoring')" id="nav-monitoring" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-monitor-play text-lg"></i> Monitoring
            </button>
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
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-[#EBF5FF] text-[#4A90E2] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-users"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="statUsers">120</h3>
                        <p class="text-gray-500 text-sm">Total Siswa</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-[#E8F9F5] text-[#2ECC71] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-question"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800" id="statQuestions">0</h3>
                        <p class="text-gray-500 text-sm">Bank Soal</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-[#FFF4E5] text-[#FF9F43] rounded-2xl flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-file-dashed"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">5</h3>
                        <p class="text-gray-500 text-sm">Laporan Pending</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-info text-[#4A90E2]"></i> Panduan Admin
                </div>
                <div class="flex items-center gap-4 mb-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                    <i class="ph-fill ph-number-circle-one text-3xl text-[#4A90E2]"></i>
                    <div class="text-sm text-gray-600"><strong>Buat Soal RIASEC:</strong> Input 6 opsi jawaban mewakili (Realistic, Investigative, Artistic, Social, Enterprising, Conventional).</div>
                </div>
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border-l-4 border-[#4A90E2]">
                    <i class="ph-fill ph-number-circle-two text-3xl text-[#4A90E2]"></i>
                    <div class="text-sm text-gray-600"><strong>Beta Test & Publish:</strong> Simulasi ujian akan menghitung dominasi 3 kode teratas (misal: "SEC").</div>
                </div>
            </div>
        </div>

        <div id="create" class="section">
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                <div class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-[#4A90E2]"></i> Buat Pertanyaan RIASEC
                </div>
                <form id="createForm">
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teks Pertanyaan (Studi Kasus / Pilihan)</label>
                        <textarea id="qText" rows="2" placeholder="Contoh: Kegiatan apa yang paling Anda sukai di waktu luang?" required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10"></textarea>
                    </div>
                    
                    <small class="block mb-4 text-gray-500 text-xs">Isi opsi jawaban sesuai kategori RIASEC:</small>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">R</span>ealistic</label>
                            <input type="text" id="optR" placeholder="Contoh: Memperbaiki mesin" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">I</span>nvestigative</label>
                            <input type="text" id="optI" placeholder="Contoh: Membaca jurnal sains" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">A</span>rtistic</label>
                            <input type="text" id="optA" placeholder="Contoh: Melukis" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">S</span>ocial</label>
                            <input type="text" id="optS" placeholder="Contoh: Mengajar teman" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">E</span>nterprising</label>
                            <input type="text" id="optE" placeholder="Contoh: Menjual produk" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] bg-gray-50 focus:bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Opsi <span class="text-[#4A90E2]">C</span>onventional</label>
                            <input type="text" id="optC" placeholder="Contoh: Menyusun arsip" required
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
                            
                            <button onclick="compileCard()" class="py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all flex justify-center items-center gap-2">
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
                            <tr>
                                <td class="p-4 border-b border-gray-50">Siti Aminah</td>
                                <td class="p-4 border-b border-gray-50 text-[#4A90E2] font-bold">SEC</td>
                                <td class="p-4 border-b border-gray-50 text-sm text-gray-600">S:25, E:20, C:18</td>
                                <td class="p-4 border-b border-gray-50"><span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase">Review</span></td>
                                <td class="p-4 border-b border-gray-50 text-right">
                                    <button onclick="alert('Laporan diterbitkan!')" class="px-3 py-1.5 bg-[#4A90E2] text-white rounded-lg text-xs font-semibold hover:bg-[#357ABD]">Publish</button>
                                </td>
                            </tr>
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
        // ==========================================
        // 1. Navigation Logic
        // ==========================================
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));
            // Reset nav buttons style
            document.querySelectorAll('aside button').forEach(el => {
                el.classList.remove('bg-[#EBF5FF]', 'text-[#4A90E2]');
                el.classList.add('text-gray-500', 'hover:bg-gray-50');
            });

            // Show target section
            document.getElementById(sectionId).classList.add('active');
            
            // Highlight nav button
            const navBtn = document.getElementById('nav-' + sectionId);
            navBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
            navBtn.classList.add('bg-[#EBF5FF]', 'text-[#4A90E2]');

            if(sectionId === 'publish' || sectionId === 'overview') {
                loadQuestions();
            }
            if(sectionId === 'publisher-v2') {
                loadForPublisher();
            }
        }

        // ==========================================
        // 2. Dummy Data & Logic (Frontend Only)
        // ==========================================
        // Simulasi Database Lokal
        let globalQuestionsData = [
            { id: 1, question_text: "Apa yang Anda lakukan saat ada waktu luang?", opt_r: "Memperbaiki motor", opt_i: "Baca buku sains", opt_a: "Melukis", opt_s: "Ngobrol sama teman", opt_e: "Jualan online", opt_c: "Merapikan kamar", is_active: true },
            { id: 2, question_text: "Pelajaran favorit Anda?", opt_r: "Fisika/Olahraga", opt_i: "Matematika/Biologi", opt_a: "Seni Budaya", opt_s: "Sosiologi", opt_e: "Ekonomi", opt_c: "Akuntansi", is_active: true },
            { id: 3, question_text: "Anda lebih suka bekerja dengan...", opt_r: "Alat & Mesin", opt_i: "Ide & Teori", opt_a: "Warna & Desain", opt_s: "Manusia", opt_e: "Target & Uang", opt_c: "Data & Angka", is_active: false },
        ];
        
        // Load Questions Table
        function loadQuestions() {
            const tbody = document.getElementById('questionTable');
            tbody.innerHTML = '';
            document.getElementById('statQuestions').innerText = globalQuestionsData.length;

            if (globalQuestionsData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-gray-400">Belum ada soal.</td></tr>';
            } else {
                globalQuestionsData.forEach(q => {
                    const statusBadge = q.is_active 
                        ? `<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase">Tayang</span>` 
                        : `<span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase">Draft</span>`;
                    
                    const btnClass = q.is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-[#4A90E2] hover:bg-[#357ABD]';
                    const btnText = q.is_active ? 'Tarik' : 'Publish';

                    tbody.innerHTML += `
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                            <td class="p-4 text-sm text-gray-700">${q.question_text}</td>
                            <td class="p-4">${statusBadge}</td>
                            <td class="p-4 text-right">
                                <button onclick="alert('Demo: Ubah Status')" class="px-3 py-1.5 ${btnClass} text-white rounded-lg text-xs font-semibold transition-all">
                                    ${btnText}
                                </button>
                            </td>
                        </tr>`;
                });
            }
        }

        // ===============================================================
        // 3. PUBLISHER V2 LOGIC
        // ===============================================================
        let selectedQuestionIds = new Set();

        function loadForPublisher() {
            const container = document.getElementById('publisherList');
            container.innerHTML = '';

            globalQuestionsData.forEach(q => {
                const isSelected = selectedQuestionIds.has(q.id);
                const activeClass = isSelected ? 'border-[#4A90E2] bg-[#EBF5FF]' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-[#4A90E2]';
                const checkIconColor = isSelected ? 'text-[#4A90E2]' : 'text-gray-300';
                const iconClass = isSelected ? 'ph-fill ph-check-square' : 'ph ph-square';

                const itemHtml = `
                <div class="p-4 rounded-xl border mb-3 cursor-pointer transition-all flex gap-4 items-start ${activeClass}" onclick="toggleSelection(${q.id})">
                    <i class="${iconClass} text-2xl ${checkIconColor} mt-0.5"></i>
                    <div class="flex-1">
                        <div class="font-semibold text-sm text-gray-800 mb-2">${q.question_text}</div>
                        <div class="flex flex-wrap gap-1">
                            <span class="px-1.5 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">R: ${q.opt_r}</span>
                            <span class="px-1.5 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">S: ${q.opt_s}</span>
                            <span class="px-1.5 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">...</span>
                        </div>
                    </div>
                </div>`;
                container.innerHTML += itemHtml;
            });
            document.getElementById('totalSelected').innerText = selectedQuestionIds.size + " Item";
        }

        function toggleSelection(id) {
            if (selectedQuestionIds.has(id)) selectedQuestionIds.delete(id);
            else selectedQuestionIds.add(id);
            loadForPublisher();
        }

        // ===============================================================
        // 4. BETA TEST / SIMULASI LOGIC
        // ===============================================================
        let betaQuestions = [];
        let currentBetaIndex = 0;
        let betaAnswers = { R:0, I:0, A:0, S:0, E:0, C:0 };
        let tempSelectedOption = null;

        function startBetaTest() {
            if(selectedQuestionIds.size === 0) { alert("Pilih minimal 1 soal untuk simulasi!"); return; }
            
            betaQuestions = globalQuestionsData.filter(q => selectedQuestionIds.has(q.id));
            currentBetaIndex = 0;
            betaAnswers = { R:0, I:0, A:0, S:0, E:0, C:0 };
            
            document.getElementById('simResultArea').classList.add('hidden');
            document.getElementById('simQuizArea').classList.remove('hidden');
            document.getElementById('betaModal').classList.remove('hidden');
            document.getElementById('betaModal').classList.add('flex');
            
            renderSimQuestion();
        }

        function renderSimQuestion() {
            const q = betaQuestions[currentBetaIndex];
            document.getElementById('simQText').innerText = q.question_text;
            document.getElementById('simProgress').innerText = `Soal ${currentBetaIndex + 1} / ${betaQuestions.length}`;
            
            tempSelectedOption = null;
            const container = document.getElementById('simOptions');
            container.innerHTML = '';

            const options = [
                { type: 'R', text: q.opt_r }, { type: 'I', text: q.opt_i },
                { type: 'A', text: q.opt_a }, { type: 'S', text: q.opt_s },
                { type: 'E', text: q.opt_e }, { type: 'C', text: q.opt_c }
            ];

            options.forEach(opt => {
                // Tailwind Button for Options
                const btn = document.createElement('div'); 
                btn.className = 'p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-[#4A90E2] transition-all flex items-center gap-3 group';
                btn.innerHTML = `
                    <span class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg text-xs font-bold text-gray-500 group-hover:bg-[#4A90E2] group-hover:text-white transition-colors">${opt.type}</span> 
                    <span class="text-sm text-gray-700 font-medium">${opt.text}</span>`;
                
                btn.onclick = () => {
                    // Reset styles
                    Array.from(container.children).forEach(c => {
                        c.className = 'p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-blue-50 hover:border-[#4A90E2] transition-all flex items-center gap-3 group';
                        c.querySelector('span:first-child').className = 'w-8 h-8 flex items-center justify-center bg-gray-100 rounded-lg text-xs font-bold text-gray-500 group-hover:bg-[#4A90E2] group-hover:text-white transition-colors';
                    });
                    // Active Style
                    btn.className = 'p-4 border-2 border-[#4A90E2] bg-[#EBF5FF] rounded-xl cursor-pointer flex items-center gap-3';
                    btn.querySelector('span:first-child').className = 'w-8 h-8 flex items-center justify-center bg-[#4A90E2] rounded-lg text-xs font-bold text-white';
                    
                    tempSelectedOption = opt.type;
                };
                container.appendChild(btn);
            });
        }

        function nextSimQuestion() {
            if(!tempSelectedOption) { alert("Pilih jawaban dulu!"); return; }
            betaAnswers[tempSelectedOption]++;

            if(currentBetaIndex < betaQuestions.length - 1) {
                currentBetaIndex++;
                renderSimQuestion();
            } else {
                showBetaResult();
            }
        }

        function showBetaResult() {
            document.getElementById('simQuizArea').classList.add('hidden');
            document.getElementById('simResultArea').classList.remove('hidden');

            document.getElementById('scoreR').innerText = betaAnswers.R;
            document.getElementById('scoreI').innerText = betaAnswers.I;
            document.getElementById('scoreA').innerText = betaAnswers.A;
            document.getElementById('scoreS').innerText = betaAnswers.S;
            document.getElementById('scoreE').innerText = betaAnswers.E;
            document.getElementById('scoreC').innerText = betaAnswers.C;

            let entries = Object.entries(betaAnswers).sort((a, b) => b[1] - a[1]);
            let top3 = entries.slice(0, 3).map(entry => entry[0]);
            document.getElementById('finalDominance').innerText = top3.join(" ");
        }

        function closeBetaTest() {
            document.getElementById('betaModal').classList.add('hidden');
            document.getElementById('betaModal').classList.remove('flex');
        }

        function compileCard() {
            alert("✅ Data tersimpan! (Simulasi)");
        }
        function confirmPublishFromBeta() {
            closeBetaTest();
            compileCard();
        }

        // Init Load
        loadQuestions();
    </script>
</body>
</html>