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

            <div class="flex gap-2 border-b border-gray-200 pb-px overflow-x-auto" id="filterTabs">
                <button onclick="filterKuesioner('semua', this)" class="tab-btn active px-5 py-2.5 text-sm font-semibold text-[#4A90E2] border-b-2 border-[#4A90E2] whitespace-nowrap transition-all">Semua Kuesioner</button>
                <button onclick="filterKuesioner('belum', this)" class="tab-btn px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap transition-all">Belum Dikerjakan</button>
                <button onclick="filterKuesioner('selesai', this)" class="tab-btn px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent whitespace-nowrap transition-all">Selesai</button>
            </div>
        </div>

        <div class="flex-1 p-4 md:p-8 pt-6 md:pt-6 overflow-y-auto pb-24 md:pb-8">
            <div class="flex flex-col gap-4" id="kuesionerContainer">

                @forelse ($exams as $exam)
                @php
                    // Logika Status Kuesioner
                    $result = isset($completedExams) ? $completedExams->get($exam->id) : null;
                    $isCompleted = $result !== null;
                    $isPublished = $isCompleted && $result->status === 'published';
                    
                    $examDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
                    $today = \Carbon\Carbon::now()->startOfDay();
                    $isLocked  = $today->lt($examDate);
                    $isOverdue = $today->gt($examDate); 

                    // Menentukan tag kategori untuk filter tab
                    $kategoriFilter = $isCompleted ? 'selesai' : 'belum';
                @endphp

                <div class="kuesioner-card bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-5 items-start md:items-center hover:-translate-y-1 transition-transform duration-300 {{ ($isOverdue && !$isCompleted) ? 'opacity-75 grayscale-[20%]' : '' }}" data-kategori="{{ $kategoriFilter }}">
                    
                    @if($isCompleted)
                        <div class="w-14 h-14 bg-[#E8F9F5] text-[#2ECC71] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                    @elseif($isLocked || ($isOverdue && !$isCompleted))
                        <div class="w-14 h-14 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center text-3xl shrink-0">
                            <i class="ph-fill ph-lock-key"></i>
                        </div>
                    @else
                        <div class="w-14 h-14 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-3xl shrink-0 shadow-sm">
                            <i class="ph-fill ph-exam"></i>
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1.5">
                            <h3 class="text-lg font-bold text-gray-800">{{ $exam->title }}</h3>
                            
                            @if($isCompleted)
                                <span class="hidden md:inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                            @elseif($isLocked)
                                <span class="hidden md:inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dibuka</span>
                            @elseif($isOverdue)
                                <span class="hidden md:inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                            @else
                                <span class="hidden md:inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap gap-x-5 gap-y-2 text-sm text-gray-500">
                            @if($isCompleted)
                                <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-square-offset text-gray-400"></i> Penilaian Tercatat</span>
                                <span class="flex items-center gap-1.5"><i class="ph-fill ph-calendar-check text-gray-400"></i> Diselesaikan: {{ $result->created_at->format('d M Y') }}</span>
                            @else
                                <span class="flex items-center gap-1.5"><i class="ph-fill ph-clock text-gray-400"></i> Waktu: {{ $exam->duration_minutes }} Menit</span>
                                <span class="flex items-center gap-1.5 {{ ($isOverdue && !$isCompleted) ? 'text-red-400 font-medium' : '' }}">
                                    <i class="ph-fill {{ ($isOverdue && !$isCompleted) ? 'ph-warning-circle text-red-400' : 'ph-calendar text-gray-400' }}"></i> 
                                    {{ ($isOverdue && !$isCompleted) ? 'Kedaluwarsa:' : 'Batas:' }} {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 md:hidden">
                            @if($isCompleted)
                                <span class="inline-block px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                            @elseif($isLocked)
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dibuka</span>
                            @elseif($isOverdue)
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase tracking-wider">Ditutup</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase tracking-wider">Belum Dikerjakan</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="w-full md:w-auto mt-4 md:mt-0 shrink-0">
                        @if($isPublished)
                            <button disabled class="block w-full md:w-auto px-8 py-3.5 text-center bg-gray-50 border border-gray-200 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                                Laporan Dikirim ke Wali
                            </button>
                        @elseif($isCompleted)
                            <button disabled class="block w-full md:w-auto px-8 py-3.5 text-center bg-gray-50 border border-gray-200 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                                Telah Dikirim (Review)
                            </button>
                        @elseif($isLocked)
                            <button disabled class="block w-full md:w-auto px-8 py-3.5 text-center bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                                Belum Dibuka
                            </button>
                        @elseif($isOverdue)
                            <button disabled class="block w-full md:w-auto px-8 py-3.5 text-center bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                                Sesi Berakhir
                            </button>
                        @else
                            <a href="{{ route('exam.take', $exam->id) }}" class="block w-full md:w-auto px-8 py-3.5 text-center bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30">
                                Mulai Kerjakan
                            </a>
                        @endif
                    </div>
                </div>
                
                @empty
                <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 border-dashed flex flex-col items-center justify-center text-center opacity-70">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-4xl mb-4">
                        <i class="ph-fill ph-clipboard-text"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Kuesioner</h3>
                    <p class="text-gray-400 text-sm max-w-sm">Daftar kuesioner akan muncul di sini setelah sekolah mempublikasikan jadwal untuk Anda.</p>
                </div>
                @endforelse

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

    <script>
        function filterKuesioner(kategori, btnElement) {
            // 1. Reset desain semua tombol tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'text-[#4A90E2]', 'border-[#4A90E2]', 'font-semibold');
                btn.classList.add('text-gray-500', 'border-transparent', 'font-medium');
            });
            
            // 2. Beri warna biru pada tombol yang sedang diklik
            btnElement.classList.add('active', 'text-[#4A90E2]', 'border-[#4A90E2]', 'font-semibold');
            btnElement.classList.remove('text-gray-500', 'border-transparent', 'font-medium');

            // 3. Sembunyikan atau tampilkan kartu berdasarkan atribut data-kategori
            const cards = document.querySelectorAll('.kuesioner-card');
            cards.forEach(card => {
                if (kategori === 'semua' || card.getAttribute('data-kategori') === kategori) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>