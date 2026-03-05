<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> Growpath
        </div>

        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
            <a href="{{ route('kuesioner') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Kuesioner
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Halo, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang di portal penentuan minat bakat.</p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                <button onclick="alert('Belum ada notifikasi baru.')" class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-[#4A90E2] transition-colors relative cursor-pointer">
                    <i class="ph-fill ph-bell text-xl"></i>
                    <span class="absolute top-2.5 right-3 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>

                <div class="bg-white px-5 py-2.5 rounded-full shadow-sm flex items-center gap-2.5 text-sm font-semibold text-[#4A90E2]">
                    <i class="ph-fill ph-student text-lg"></i>
                    <span>{{ Auth::user()->kelas ?? 'Siswa' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
          @forelse ($exams as $exam)
            @php

                $isCompleted = in_array($exam->id, $completedExamIds);
                $examDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
            
                
                $isOverdue = \Carbon\Carbon::parse($exam->exam_date)->endOfDay()->isPast();
                $today = \Carbon\Carbon::now()->startOfDay();
                $isLocked  = $today->lt($examDate);
                $isOverdue = $today->gt($examDate); 
            @endphp
            
           <div class="bg-white p-6 rounded-[18px] shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-exam"></i>
                    </div>
                    
                    @if($isCompleted)
                        <span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase border border-green-100">
                            Selesai
                        </span>
                    @elseif($isLocked)
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase border border-gray-200">
                            Terkunci
                        </span>
                    @elseif($isOverdue)
                        <span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-xs font-bold uppercase border border-red-100">
                            Overdue
                        </span>
                    @else
                        <span class="px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase border border-orange-100">
                            Belum Tes
                        </span>
                    @endif
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $exam->title }}</h3>
                
                <div class="text-gray-500 text-sm leading-relaxed mb-6 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-clock text-[#4A90E2]"></i> Waktu: {{ $exam->duration_minutes }} Menit
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-calendar {{ $isOverdue && !$isCompleted ? 'text-red-500' : ($isLocked && !$isCompleted ? 'text-gray-400' : 'text-[#4A90E2]') }}"></i> 
                        Jadwal: {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                    </div>
                </div>
                
                @if($isCompleted)
                    <button disabled class="mt-auto w-full py-3 bg-[#E8F9F5] text-[#2ECC71] border border-[#2ECC71]/30 rounded-xl font-semibold text-sm cursor-not-allowed text-center block">
                        Kuesioner Selesai
                    </button>
                @elseif($isLocked)
                    <button disabled class="mt-auto w-full py-3 bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed text-center block">
                        Belum Dimulai
                    </button>
                @elseif($isOverdue)
                    <button onclick="alert('Jadwal ujian ini sudah terlewat. Silakan hubungi Admin sekolah Anda.')" class="mt-auto w-full py-3 bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 rounded-xl font-semibold text-sm transition-all text-center block cursor-pointer">
                        Contact Admin
                    </button>
                @else
                    <a href="{{ route('exam.take', $exam->id) }}" class="mt-auto w-full py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30 text-center block">
                        Mulai Kuesioner
                    </a>
                @endif
            </div>

            @empty
            <div class="bg-white p-6 rounded-[18px] shadow-sm border border-gray-100 border-dashed flex flex-col justify-center items-center text-center opacity-70">
                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-3xl mb-3">
                    <i class="ph-fill ph-check-circle"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">Semua Beres!</h3>
                <p class="text-gray-400 text-sm">Belum ada jadwal tes untuk kelas Anda.</p>
            </div>
            @endforelse

            <div class="bg-white p-6 rounded-[18px] shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-[#FFF4E5] text-[#FF9F43] rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-lightbulb"></i>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Tips Belajar</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Dapatkan rekomendasi metode belajar yang efektif sesuai dengan hasil tes gaya berpikir Anda.
                </p>
                <button onclick="alert('Selesaikan tes terlebih dahulu!')" class="mt-auto w-full py-3 bg-white border border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all">
                    Lihat Tips
                </button>
            </div>

          @php
                $hasAnyCompletedExam = count($completedExamIds) > 0;
            @endphp

            <div class="bg-white p-6 rounded-[18px] border border-gray-100 transition-all duration-300 flex flex-col {{ $hasAnyCompletedExam ? 'shadow-[0_5px_20px_rgba(0,0,0,0.05)] hover:-translate-y-1' : 'shadow-sm opacity-60' }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $hasAnyCompletedExam ? 'bg-[#EBF5FF] text-[#4A90E2]' : 'bg-gray-100 text-gray-400' }}">
                        <i class="ph-fill ph-files"></i>
                    </div>
                    
                    @if($hasAnyCompletedExam)
                        <span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase border border-green-100">
                            Tersedia
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-xs font-bold uppercase border border-gray-200">
                            Terkunci
                        </span>
                    @endif
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Laporan Hasil</h3>
                <div class="bg-white p-6 rounded-[18px] border border-gray-100 shadow-sm flex flex-col relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-gray-100 text-gray-500 rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-lock-key"></i>
                    </div>
                    
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase border border-gray-200">
                        Khusus Wali
                    </span>
                </div>
                
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Hasil analisis kuesioner Anda akan dievaluasi oleh sekolah dan dikirimkan langsung ke dashboard Orang Tua/Wali Anda.
                </p>
                
                <button disabled class="mt-auto w-full py-3 bg-gray-50 border border-gray-200 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed text-center block">
                    Akses Terkunci
                </button>
            </div>
    </main>

    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around z-50">
        <a href="#" class="flex flex-col items-center text-[#4A90E2]">
            <i class="ph-fill ph-squares-four text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-clipboard-text text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Tes</span>
        </a>
       <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-user text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Profil</span>
        </a>
    </div>

</body>
</html>