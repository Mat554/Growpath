<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Daftar Kuesioner - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/student/kuesioner.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        html, body { overflow-x: hidden; overscroll-behavior: none; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans min-h-screen pb-20">

    <!-- MOBILE HEADER -->
    <header class="bg-white px-4 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-500">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="font-bold text-gray-800 text-lg">Daftar Test</h1>
                    <p class="text-[10px] text-gray-400 -mt-0.5">Tes Minat Bakat</p>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        <!-- Filter Tabs -->
        <div class="flex gap-2 mb-4 overflow-x-auto -mx-4 px-4 pb-2">
            <button onclick="filterKuesioner('semua', this)" class="tab-btn active px-4 py-2 text-xs font-semibold text-[#4A90E2] bg-[#EBF5FF] rounded-full whitespace-nowrap">Semua</button>
            <button onclick="filterKuesioner('belum', this)" class="tab-btn px-4 py-2 text-xs font-medium text-gray-500 bg-white rounded-full whitespace-nowrap border border-gray-200">Belum</button>
            <button onclick="filterKuesioner('selesai', this)" class="tab-btn px-4 py-2 text-xs font-medium text-gray-500 bg-white rounded-full whitespace-nowrap border border-gray-200">Selesai</button>
        </div>

        <!-- Exam List -->
        <div class="space-y-3" id="kuesionerContainer">

            @forelse ($exams as $exam)
            @php
                $result = isset($completedExams) ? $completedExams->get($exam->id) : null;
                $isCompleted = $result !== null;
                $isPublished = $isCompleted && $result->status === 'published';

                $startDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
                $endDate = $exam->exam_end_date ? \Carbon\Carbon::parse($exam->exam_end_date)->startOfDay() : $startDate;
                $today = \Carbon\Carbon::now()->startOfDay();
                $isLocked  = $today->lt($startDate);
                $isOverdue = $today->gt($endDate);

                $kategoriFilter = $isCompleted ? 'selesai' : 'belum';
            @endphp

            <div class="kuesioner-card bg-white p-4 rounded-2xl shadow-sm border border-gray-100 {{ ($isOverdue && !$isCompleted) ? 'opacity-75' : '' }}" data-kategori="{{ $kategoriFilter }}">
                <div class="flex items-start gap-3 mb-3">
                    @if($isCompleted)
                        <div class="w-10 h-10 bg-[#E8F9F5] text-[#2ECC71] rounded-xl flex items-center justify-center text-2xl shrink-0">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                    @elseif($isLocked || ($isOverdue && !$isCompleted))
                        <div class="w-10 h-10 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center text-2xl shrink-0">
                            <i class="ph-fill ph-lock-key"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-2xl shrink-0">
                            <i class="ph-fill ph-exam"></i>
                        </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $exam->title }}</h3>

                        @if($isCompleted)
                            <span class="inline-block px-2 py-0.5 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold">Selesai</span>
                        @elseif($isLocked)
                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold">Belum Dibuka</span>
                        @elseif($isOverdue)
                            <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold">Ditutup</span>
                        @else
                            <span class="inline-block px-2 py-0.5 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold">Belum Dikerjakan</span>
                        @endif

                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-[10px] text-gray-500 mt-2">
                            @if($isCompleted)
                                <span class="flex items-center gap-1"><i class="ph-fill ph-calendar-check"></i> {{ $result->created_at->format('d M Y') }}</span>
                            @else
                                <span class="flex items-center gap-1"><i class="ph-fill ph-clock"></i> {{ $exam->duration_minutes }} menit</span>
                                <span class="flex items-center gap-1 {{ ($isOverdue && !$isCompleted) ? 'text-red-400' : '' }}">
                                    <i class="ph-fill {{ ($isOverdue && !$isCompleted) ? 'ph-warning-circle text-red-400' : 'ph-calendar' }}"></i>
                                    {{ $exam->exam_end_date ? \Carbon\Carbon::parse($exam->exam_end_date)->format('d M') : \Carbon\Carbon::parse($exam->exam_date)->format('d M') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-2">
                    @if($isPublished)
                        <button disabled class="w-full py-2.5 bg-gray-50 border border-gray-200 text-gray-400 rounded-xl text-xs font-semibold cursor-not-allowed">
                            Laporan Dikirim ke Wali
                        </button>
                    @elseif($isCompleted)
                        <button disabled class="w-full py-2.5 bg-gray-50 border border-gray-200 text-gray-400 rounded-xl text-xs font-semibold cursor-not-allowed">
                            Telah Dikirim (Review)
                        </button>
                    @elseif($isLocked)
                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 rounded-xl text-xs font-semibold cursor-not-allowed">
                            Belum Dibuka
                        </button>
                    @elseif($isOverdue)
                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 rounded-xl text-xs font-semibold cursor-not-allowed">
                            Sesi Berakhir
                        </button>
                    @else
                        <a href="{{ route('exam.take', $exam->id) }}" class="block w-full py-2.5 bg-[#4A90E2] text-white rounded-xl text-xs font-semibold text-center">
                            Mulai Kerjakan
                        </a>
                    @endif
                </div>
            </div>

            @empty
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-14 h-14 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mx-auto mb-3">
                    <i class="ph-fill ph-clipboard-text"></i>
                </div>
                <h3 class="font-semibold text-gray-700 mb-1">Belum Ada Test</h3>
                <p class="text-gray-400 text-xs">Test akan muncul setelah dipublikasikan.</p>
            </div>
            @endforelse

        </div>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-squares-four text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Home</span>
        </a>
        <a href="{{ route('kuesioner') }}" class="flex flex-col items-center text-[#4A90E2] py-1 px-4">
            <i class="ph-fill ph-clipboard-text text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Tes</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-user text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Profil</span>
        </a>
    </nav>

</body>
</html>