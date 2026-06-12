<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/student/dashboard.js'])
    <style>
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-15deg); }
        }
        .animate-ring {
            animation: ring 0.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> 
            <span class="text-xl font-bold text-gray-800 tracking-tight">Grow<span class="text-[#4A90E2]">path</span></span>
        </div>

        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
            <a href="{{ route('kuesioner') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Test
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer border-none bg-transparent text-left">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-4 md:p-8 pb-24 md:pb-8 overflow-y-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Halo, {{ Auth::user()->name }}! 👋
                </h2>
                <p class="text-gray-500 text-sm mt-1">Selamat datang di portal penentuan minat bakat.</p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
                
                <div class="relative z-50" id="notificationDropdown">
                    <button onclick="toggleNotifications()" class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-[#4A90E2] transition-colors relative cursor-pointer border-none focus:outline-none">
                        <i id="bellIcon" class="ph-fill ph-bell text-xl {{ (count($pendingParents ?? []) > 0 || ($newExamCount ?? 0) > 0) ? 'text-[#4A90E2] animate-ring' : '' }}"></i>

                        @if(count($pendingParents ?? []) > 0 || ($newExamCount ?? 0) > 0)
                            <span id="notifBadge" class="absolute top-2.5 right-3 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </button>

                    <div id="notificationMenu" class="hidden absolute left-0 md:left-auto md:right-0 mt-3 w-[calc(100vw-2rem)] max-w-[360px] bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-left md:origin-top-right">
                        <div class="p-4 border-b border-gray-100 bg-[#F8FAFC]">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <i class="ph-fill ph-bell-ringing text-[#4A90E2]"></i> Notifikasi
                            </h3>
                        </div>

                        <div class="max-h-[350px] overflow-y-auto">

                            {{-- Notifikasi Soal Baru --}}
                            @if(($newExamCount ?? 0) > 0)
                                <div class="p-4 border-b border-blue-100 bg-[#EBF5FF]/30 flex flex-col gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#EBF5FF] text-[#4A90E2] flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="ph-fill ph-exam text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 leading-snug">
                                                <strong class="text-[#4A90E2]">{{ $newExamCount }}</strong> test baru tersedia untuk kelas {{ Auth::user()->kelas }}!
                                            </p>
                                            @if($latestExam)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Test terbaru: <strong>{{ $latestExam->title }}</strong>
                                                </p>
                                            @endif
                                            <span class="text-xs text-gray-400 mt-1 block">Baru saja</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('kuesioner') }}" class="py-2 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-lg font-semibold text-xs text-center transition-all">
                                        Lihat Test
                                    </a>
                                </div>
                            @endif

                            @foreach($pendingParents ?? [] as $pending)
                                <div class="p-4 border-b border-orange-100 bg-[#FFF4E5]/30 flex flex-col gap-3">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-[#FFF4E5] text-[#FF9F43] flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="ph-fill ph-user-plus text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 leading-snug">
                                                <strong class="text-[#FF9F43]">{{ $pending->name }}</strong> meminta izin untuk memantau laporan test Anda sebagai Wali.
                                            </p>
                                            <span class="text-xs text-gray-400 mt-1 block">
                                                {{ $pending->updated_at ? $pending->updated_at->diffForHumans() : 'Baru saja' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 sm:ml-13">
                                        <form action="{{ route('koneksi.approve', $pending->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 bg-[#2ECC71] hover:bg-[#27ae60] text-white rounded-lg font-semibold text-xs transition-all cursor-pointer">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('koneksi.reject', $pending->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 rounded-lg font-semibold text-xs transition-all cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($connectedParents ?? [] as $parent)
                                <div class="p-4 border-b border-gray-50 flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#EBF5FF] text-[#4A90E2] flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="ph-fill ph-users text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800 leading-snug">
                                            Terhubung dengan Wali: <strong class="text-[#4A90E2]">{{ $parent->name }}</strong>.
                                        </p>
                                        <form action="{{ route('koneksi.revoke', $parent->id) }}" method="POST" onsubmit="return confirm('Lepas koneksi dengan orang tua ini?')">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-red-500 font-semibold hover:underline mt-2 bg-transparent border-none p-0 cursor-pointer">
                                                Lepas Koneksi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($pendingParents ?? []) == 0 && count($connectedParents ?? []) == 0)
                                <div class="p-8 text-center flex flex-col items-center justify-center">
                                    <i class="ph ph-bell-slash text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-gray-500 text-sm">Belum ada notifikasi baru.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                  
                <div class="bg-white px-5 py-2.5 rounded-full shadow-sm flex items-center gap-2.5 text-sm font-semibold text-[#4A90E2] truncate">
                    <i class="ph-fill ph-student text-lg"></i>
                    <span>{{ Auth::user()->kelas ?? 'Siswa' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @php
                $sortedExams = collect($exams)->sortByDesc(function($exam) use ($completedExams) {
                    $result = isset($completedExams) ? collect($completedExams)->firstWhere('exam_id', $exam->id) : null;
                    $isCompleted = $result !== null;
                    $examDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
                    $today = \Carbon\Carbon::now()->startOfDay();
                    
                    if ($isCompleted) return 1; 
                    if ($today->gt($examDate)) return 0; 
                    if ($today->lt($examDate)) return 2; 
                    return 3; 
                });
            @endphp

            @forelse ($sortedExams as $exam)
                @php
                    $result = isset($completedExams) ? collect($completedExams)->firstWhere('exam_id', $exam->id) : null;
                    $isCompleted = $result !== null;
                    $examDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
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
                            <span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] md:text-xs font-bold uppercase border border-green-100">
                                Selesai
                            </span>
                        @elseif($isLocked)
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] md:text-xs font-bold uppercase border border-gray-200">
                                Terkunci
                            </span>
                        @elseif($isOverdue)
                            <span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-[10px] md:text-xs font-bold uppercase border border-red-100">
                                Overdue
                            </span>
                        @else
                            <span class="px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] md:text-xs font-bold uppercase border border-orange-100">
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
                            <i class="ph-fill ph-calendar {{ ($isOverdue && !$isCompleted) ? 'text-red-500' : 'text-[#4A90E2]' }}"></i> 
                            Jadwal: {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                        </div>
                    </div>
                    
                    @if($isCompleted)
                        <button disabled class="mt-auto w-full py-3 bg-[#E8F9F5] text-[#2ECC71] border border-[#2ECC71]/30 rounded-xl font-semibold text-sm cursor-not-allowed">
                            Test Selesai
                        </button>
                    @elseif($isLocked)
                        <button disabled class="mt-auto w-full py-3 bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                            Belum Dimulai
                        </button>
                    @elseif($isOverdue)
                        <button onclick="alert('Jadwal sudah terlewat. Silakan hubungi Admin.')" class="mt-auto w-full py-3 bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 rounded-xl font-semibold text-sm transition-all cursor-pointer">
                            Contact Admin
                        </button>
                    @else
                        <a href="{{ route('exam.take', $exam->id) }}" class="mt-auto w-full py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30 text-center block">
                            Mulai Kerjakan
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
                <form action="{{ route('tips') }}" method="POST" class="mt-auto w-full">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-white border border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all cursor-pointer">
                        Lihat Tips
                    </button>
                </form>
            </div>

            @php
                $hasAnyCompletedExam = count($completedExamIds ?? []) > 0;
            @endphp

            <div class="bg-white p-6 rounded-[18px] border border-gray-100 transition-all duration-300 flex flex-col {{ $hasAnyCompletedExam ? 'shadow-[0_5px_20px_rgba(0,0,0,0.05)] hover:-translate-y-1' : 'shadow-sm' }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $hasAnyCompletedExam ? 'bg-[#EBF5FF] text-[#4A90E2]' : 'bg-orange-100 text-orange-400' }}">
                        <i class="ph-fill ph-files"></i>
                    </div>

                    @if($hasAnyCompletedExam)
                        <span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] md:text-xs font-bold uppercase border border-green-100">
                            Tersedia
                        </span>
                    @else
                        <span class="px-3 py-1 bg-orange-100 text-orange-500 rounded-full text-[10px] md:text-xs font-bold uppercase border border-orange-200">
                            Belum Tersedia
                        </span>
                    @endif
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">Laporan Hasil</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    @if($hasAnyCompletedExam)
                        Laporan hasil tes minat dan bakat Anda sudah tersedia! Klik tombol di bawah untuk melihat.
                    @else
                        Anda belum mengerjakan tes. Silakan kerjakan test terlebih dahulu untuk melihat laporan hasil.
                    @endif
                </p>

                @if($hasAnyCompletedExam)
                    <a href="{{ route('laporan') }}" class="mt-auto w-full py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm text-center transition-all shadow-sm">
                        <i class="ph ph-eye mr-1"></i> Lihat Laporan
                    </a>
                @else
                    <a href="{{ route('kuesioner') }}" class="mt-auto w-full py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl font-semibold text-sm text-center transition-all shadow-sm">
                        <i class="ph ph-clipboard-text mr-1"></i> Kerjakan Test
                    </a>
                @endif
            </div>
            
        </div>
    </main>

<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around items-center z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-[#4A90E2]">
            <i class="ph-fill ph-squares-four text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-user text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Profil</span>
        </a>
        <a href="{{ route('kuesioner') }}" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-clipboard-text text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Tes</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 flex">
            @csrf
            <button type="submit" class="flex flex-col items-center text-red-400 hover:text-red-500 bg-transparent border-none p-0 cursor-pointer">
                <i class="ph ph-sign-out text-2xl"></i>
                <span class="text-[10px] font-medium mt-1">Keluar</span>
            </button>
        </form>
    </div>

</body>

<script>
    function toggleNotifications() {
        const menu = document.getElementById('notificationMenu');

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            menu.classList.remove('opacity-100', 'scale-100');
            menu.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                menu.classList.add('hidden');
            }, 200);
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const menu = document.getElementById('notificationMenu');

        if (!dropdown.contains(event.target) && !menu.classList.contains('hidden')) {
            toggleNotifications();
        }
    });
</script>

{{-- POPUP NOTIFIKASI SOAL BARU --}}
@if(($newExamCount ?? 0) > 0)
<div id="newExamModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeExamModal()"></div>
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden transform transition-all duration-300" style="animation: modalSlideUp 0.4s ease-out;">
        {{-- Header Gradient --}}
        <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] p-6 text-center relative overflow-hidden">
            <div class="absolute top-[-30%] right-[-20%] w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
            <div class="absolute bottom-[-30%] left-[-20%] w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="ph-fill ph-exam text-4xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-1">Test Baru!</h3>
                <p class="text-white/80 text-sm">{{ $newExamCount }} tes baru tersedia</p>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-6">
            @if($latestExam)
            <div class="bg-[#F8FAFC] rounded-xl p-4 mb-5 border border-gray-100">
                <p class="text-sm text-gray-500 mb-1">Test terbaru:</p>
                <h4 class="font-semibold text-gray-800 text-lg mb-2">{{ $latestExam->title }}</h4>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <i class="ph ph-clock text-[#4A90E2]"></i>
                        {{ $latestExam->duration_minutes }} menit
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="ph ph-calendar text-[#4A90E2]"></i>
                        {{ Carbon\Carbon::parse($latestExam->exam_date)->format('d M Y') }}
                    </span>
                </div>
            </div>
            @endif

            <p class="text-gray-600 text-sm text-center mb-5">
                Selesaikan test untuk mengetahui minat dan bakat Anda!
            </p>

            <div class="flex gap-3">
                <button onclick="closeExamModal()" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold transition-all cursor-pointer">
                    Nanti Saja
                </button>
                <a href="{{ route('kuesioner') }}" class="flex-1 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-center transition-all shadow-lg shadow-[#4A90E2]/30">
                    Mulai Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampilkan popup saat halaman load
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('newExamModal');
        if (modal) {
            modal.style.opacity = '1';
            modal.querySelector('.relative').style.transform = 'translateY(0)';
        }
    });

    function closeExamModal() {
        const modal = document.getElementById('newExamModal');
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }
</script>

<style>
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endif
</html>