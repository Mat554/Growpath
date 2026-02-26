<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kuesioner - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-[#F4F7F6] flex h-screen overflow-hidden text-[#333]">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> Growpath
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
            <a href="{{ route('kuesioner') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Kuesioner
            </a>
        </nav>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer border-none bg-transparent text-left">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <div class="p-4 md:p-8 pb-0 md:pb-0 shrink-0">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Daftar Kuesioner</h2>
                    <p class="text-gray-500 text-sm mt-1">Selesaikan tes minat bakat yang telah ditugaskan kepada Anda.</p>
                </div>
                
                <div class="w-full md:w-auto relative">
                    <input type="text" placeholder="Cari kuesioner..." class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all">
                    <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                </div>
            </div>

            <div class="flex gap-2 border-b border-gray-200 pb-px overflow-x-auto">
                <button class="px-5 py-2.5 text-sm font-semibold text-[#4A90E2] border-b-2 border-[#4A90E2] whitespace-nowrap">Semua Kuesioner</button>
                <button class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">Belum Dikerjakan</button>
                <button class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 whitespace-nowrap">Selesai</button>
            </div>
        </div>

        <div class="flex-1 p-4 md:p-8 pt-6 md:pt-6 overflow-y-auto pb-24 md:pb-8">
            <div class="flex flex-col gap-4">

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-14 h-14 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="ph-fill ph-exam"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Tes Minat Bakat (RIASEC) Gelombang 1</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-clock text-gray-400"></i> Waktu: 60 Menit</span>
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar text-gray-400"></i> Batas: 30 Feb 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <a href="{{ route('tes') }}" class="block w-full md:w-auto px-8 py-3.5 text-center bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30">
                            Mulai Kerjakan
                        </a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-14 h-14 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="ph-fill ph-brain"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Tes Kepribadian (MBTI) Sesi 2</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-clock text-gray-400"></i> Waktu: 45 Menit</span>
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar text-gray-400"></i> Batas: 15 Mar 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <a href="{{ route('tes') }}" class="block w-full md:w-auto px-8 py-3.5 text-center bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30">
                            Mulai Kerjakan
                        </a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-14 h-14 bg-[#E8F9F5] text-[#2ECC71] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Evaluasi Gaya Belajar</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-square-offset text-gray-400"></i> Penilaian Tercatat</span>
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar-check text-gray-400"></i> Diselesaikan: 15 Jan 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <a href="{{ route('laporan') }}" class="block w-full md:w-auto px-8 py-3.5 text-center bg-white border-2 border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-14 h-14 bg-[#E8F9F5] text-[#2ECC71] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Kuesioner Peminatan Jurusan Kuliah</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-square-offset text-gray-400"></i> Penilaian Tercatat</span>
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar-check text-gray-400"></i> Diselesaikan: 12 Jan 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <a href="{{ route('laporan') }}" class="block w-full md:w-auto px-8 py-3.5 text-center bg-white border-2 border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all">
                            Lihat Laporan
                        </a>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-14 h-14 bg-[#E8F9F5] text-[#2ECC71] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Kuesioner Evaluasi Fasilitas Belajar</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-square-offset text-gray-400"></i> Penilaian Tercatat</span>
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar-check text-gray-400"></i> Diselesaikan: 5 Jan 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <button disabled class="block w-full md:w-auto px-8 py-3.5 text-center bg-gray-50 border border-gray-200 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                            Telah Dikirim
                        </button>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center opacity-75 grayscale-[20%] transition-all">
                    <div class="w-14 h-14 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center text-3xl shrink-0">
                        <i class="ph-fill ph-lock-key"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Survei Peminatan Ekskul</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-clock text-gray-400"></i> Waktu: 15 Menit</span>
                            <span class="flex items-center gap-1.5 text-red-400 font-medium"><i class="ph-fill ph-warning-circle text-red-400"></i> Kedaluwarsa: 10 Jan 2026</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <button disabled class="w-full md:w-auto px-8 py-3.5 text-center bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                            Sesi Berakhir
                        </button>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center opacity-75 grayscale-[20%] transition-all">
                    <div class="w-14 h-14 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center text-3xl shrink-0">
                        <i class="ph-fill ph-lock-key"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">Kuesioner Pemetaan Hobi</h3>
                            <span class="hidden md:inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5"><i class="ph-fill ph-clock text-gray-400"></i> Waktu: 20 Menit</span>
                            <span class="flex items-center gap-1.5 text-red-400 font-medium"><i class="ph-fill ph-warning-circle text-red-400"></i> Kedaluwarsa: 1 Des 2025</span>
                        </div>
                        <div class="mt-3 md:hidden">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                        </div>
                    </div>
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        <button disabled class="w-full md:w-auto px-8 py-3.5 text-center bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                            Sesi Berakhir
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-400 hover:text-[#4A90E2] transition-colors">
            <i class="ph-fill ph-squares-four text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <a href="{{ route('kuesioner') }}" class="flex flex-col items-center text-[#4A90E2]">
            <i class="ph-fill ph-clipboard-text text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Tes</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-400 hover:text-[#4A90E2] transition-colors">
            <i class="ph-fill ph-user text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Profil</span>
        </a>
    </div>

</body>
</html>