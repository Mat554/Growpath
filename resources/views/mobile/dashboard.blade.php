<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dashboard Siswa - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/student/dashboard.js'])
    <style>
        * {
            -webkit-tap-highlight-color: transparent;
            box-sizing: border-box;
        }
        html, body {
            overflow-x: hidden;
            overscroll-behavior: none;
        }
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-15deg); }
        }
        .animate-ring {
            animation: ring 0.5s ease-in-out infinite;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
        /* Smooth scrolling */
        .smooth-scroll {
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>

<body class="bg-[#F4F7F6] font-sans min-h-screen pb-20">

    <!-- MOBILE HEADER -->
    <header class="bg-white px-4 py-4 sticky top-0 z-40 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center">
                    <i class="ph-fill ph-brain text-xl"></i>
                </div>
                <div>
                    <h1 class="font-bold text-[#4A90E2] text-lg">Growpath</h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <!-- Notification Bell -->
                <div class="relative" id="notificationDropdown">
                    <button onclick="toggleNotifications()" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-500 relative cursor-pointer border-none active:bg-gray-100">
                        <i id="bellIcon" class="ph-fill ph-bell text-lg {{ count($pendingParents ?? []) > 0 ? 'text-[#4A90E2] animate-ring' : '' }}"></i>
                        @if(count($pendingParents ?? []) > 0)
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>
                    <div id="notificationMenu" class="hidden absolute right-0 mt-2 w-[300px] bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                        <div class="p-3 border-b border-gray-100 bg-[#F8FAFC]">
                            <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                <i class="ph-fill ph-bell-ringing text-[#4A90E2]"></i> Notifikasi
                            </h3>
                        </div>
                        <div class="max-h-[250px] overflow-y-auto">
                            @foreach($pendingParents ?? [] as $pending)
                                <div class="p-3 border-b border-orange-50 bg-[#FFF4E5]/30">
                                    <div class="flex items-start gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-[#FFF4E5] text-[#FF9F43] flex items-center justify-center flex-shrink-0">
                                            <i class="ph-fill ph-user-plus text-sm"></i>
                                        </div>
                                        <p class="text-xs text-gray-800 leading-snug">
                                            <strong class="text-[#FF9F43]">{{ $pending->name }}</strong> meminta izin memantau laporan Anda sebagai Wali.
                                        </p>
                                    </div>
                                    <div class="flex gap-2 ml-10">
                                        <form action="{{ route('koneksi.approve', $pending->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 bg-[#2ECC71] text-white rounded-lg text-xs font-semibold cursor-pointer">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('koneksi.reject', $pending->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full py-1.5 bg-white border border-red-200 text-red-500 rounded-lg text-xs font-semibold cursor-pointer">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @foreach($connectedParents ?? [] as $parent)
                                <div class="p-3 border-b border-gray-50 flex items-start gap-2">
                                    <div class="w-8 h-8 rounded-full bg-[#EBF5FF] text-[#4A90E2] flex items-center justify-center flex-shrink-0">
                                        <i class="ph-fill ph-users text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-800">
                                            Wali: <strong class="text-[#4A90E2]">{{ $parent->name }}</strong>
                                        </p>
                                        <form action="{{ route('koneksi.revoke', $parent->id) }}" method="POST" onsubmit="return confirm('Lepas koneksi?')">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-red-500 font-semibold bg-transparent border-none p-0 cursor-pointer mt-1">
                                                Lepas
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @if(count($pendingParents ?? []) == 0 && count($connectedParents ?? []) == 0)
                                <div class="p-6 text-center">
                                    <i class="ph ph-bell-slash text-3xl text-gray-300 mb-1"></i>
                                    <p class="text-gray-500 text-xs">Belum ada notifikasi.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Avatar -->
                <a href="{{ route('profile') }}" class="w-10 h-10 bg-[#EBF5FF] text-[#4A90E2] rounded-full flex items-center justify-center overflow-hidden">
                    <i class="ph-fill ph-user text-lg"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        <!-- Greeting -->
        <div class="mb-5">
            <h2 class="text-xl font-semibold text-gray-800">
                Halo, {{ Auth::user()->name }}! 👋
            </h2>
            <p class="text-gray-500 text-sm">Portal penentuan minat bakat</p>
            <div class="mt-2 inline-flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm text-xs font-semibold text-[#4A90E2]">
                <i class="ph-fill ph-student"></i>
                {{ Auth::user()->kelas ?? 'Siswa' }}
            </div>
        </div>

        <!-- Exam Cards -->
        <div class="space-y-4">

            @php
                // Urutkan kuesioner berdasarkan prioritas
                $sortedExams = collect($exams)->sortByDesc(function($exam) use ($completedExams) {
                    $result = isset($completedExams) ? collect($completedExams)->firstWhere('exam_id', $exam->id) : null;
                    $isCompleted = $result !== null;
                    $startDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
                    $endDate = $exam->exam_end_date ? \Carbon\Carbon::parse($exam->exam_end_date)->startOfDay() : $startDate;
                    $today = \Carbon\Carbon::now()->startOfDay();

                    if ($isCompleted) return 1;
                    if ($today->lt($startDate)) return 2;
                    if ($today->gt($endDate)) return 0;
                    return 3;
                });
            @endphp

            @forelse ($sortedExams as $exam)
                @php
                    $result = isset($completedExams) ? collect($completedExams)->firstWhere('exam_id', $exam->id) : null;
                    $isCompleted = $result !== null;
                    $startDate = \Carbon\Carbon::parse($exam->exam_date)->startOfDay();
                    $endDate = $exam->exam_end_date ? \Carbon\Carbon::parse($exam->exam_end_date)->startOfDay() : $startDate;
                    $today = \Carbon\Carbon::now()->startOfDay();

                    $isLocked  = $today->lt($startDate);
                    $isOverdue = $today->gt($endDate);
                @endphp

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#EBF5FF] text-[#4A90E2] rounded-xl flex items-center justify-center">
                                <i class="ph-fill ph-exam text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm">{{ $exam->title }}</h3>
                                <p class="text-xs text-gray-500">
                                    <i class="ph-fill ph-clock text-[10px]"></i> {{ $exam->duration_minutes }} menit
                                </p>
                            </div>
                        </div>

                        @if($isCompleted)
                            <span class="px-2.5 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold uppercase border border-green-100">
                                Selesai
                            </span>
                        @elseif($isLocked)
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold uppercase border border-gray-200">
                                Terkunci
                            </span>
                        @elseif($isOverdue)
                            <span class="px-2.5 py-1 bg-red-50 text-red-500 rounded-full text-[10px] font-bold uppercase border border-red-100">
                                Overdue
                            </span>
                        @else
                            <span class="px-2.5 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-[10px] font-bold uppercase border border-orange-100">
                                Belum Tes
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-500 mb-3">
                        <i class="ph-fill ph-calendar {{ ($isOverdue && !$isCompleted) ? 'text-red-500' : 'text-[#4A90E2]' }}"></i>
                        {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}{{ $exam->exam_end_date ? ' - ' . \Carbon\Carbon::parse($exam->exam_end_date)->format('d M Y') : '' }}
                    </p>

                    @if($isCompleted)
                        <button disabled class="w-full py-2.5 bg-[#E8F9F5] text-[#2ECC71] border border-[#2ECC71]/30 rounded-xl font-semibold text-xs cursor-not-allowed">
                            Kuesioner Selesai
                        </button>
                    @elseif($isLocked)
                        <button disabled class="w-full py-2.5 bg-gray-100 text-gray-400 rounded-xl font-semibold text-xs cursor-not-allowed">
                            Belum Dimulai
                        </button>
                    @elseif($isOverdue)
                        <button onclick="alert('Jadwal sudah terlewat. Hubungi Admin.')" class="w-full py-2.5 bg-red-50 border border-red-200 text-red-500 rounded-xl font-semibold text-xs cursor-pointer">
                            Contact Admin
                        </button>
                    @else
                        <a href="{{ route('exam.take', $exam->id) }}" class="block w-full py-2.5 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-xs text-center">
                            Mulai Kuesioner
                        </a>
                    @endif
                </div>

            @empty
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                    <div class="w-14 h-14 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-2xl mx-auto mb-3">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <h3 class="font-semibold text-gray-700 mb-1">Semua Beres!</h3>
                    <p class="text-gray-400 text-xs">Belum ada jadwal tes untuk kelas Anda.</p>
                </div>
            @endforelse

            <!-- Tips Card -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#FFF4E5] text-[#FF9F43] rounded-xl flex items-center justify-center">
                        <i class="ph-fill ph-lightbulb text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Tips Belajar</h3>
                        <p class="text-xs text-gray-500">Metode belajar efektif</p>
                    </div>
                </div>
                <form action="{{ route('tips') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-white border border-[#4A90E2] text-[#4A90E2] rounded-xl font-semibold text-xs cursor-pointer">
                        Lihat Tips
                    </button>
                </form>
            </div>

            <!-- Laporan Card -->
            @php
                $hasAnyCompletedExam = count($completedExamIds ?? []) > 0;
            @endphp
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 {{ !$hasAnyCompletedExam ? 'opacity-60' : '' }}">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $hasAnyCompletedExam ? 'bg-[#EBF5FF] text-[#4A90E2]' : 'bg-gray-100 text-gray-400' }}">
                        <i class="ph-fill ph-files"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-sm">Laporan Hasil</h3>
                        <p class="text-xs text-gray-500">Hasil akan dikirim ke Wali</p>
                    </div>
                    @if($hasAnyCompletedExam)
                        <span class="px-2 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-[10px] font-bold">Tersedia</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-bold">Terkunci</span>
                    @endif
                </div>
                <button disabled class="w-full py-2.5 bg-gray-50 border border-gray-200 text-gray-400 rounded-xl font-semibold text-xs cursor-not-allowed">
                    <i class="ph ph-lock-key mr-1"></i> Khusus Wali
                </button>
            </div>
        </div>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-50 safe-area-pb">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-[#4A90E2] py-1 px-4">
            <i class="ph-fill ph-squares-four text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Home</span>
        </a>
        <a href="{{ route('kuesioner') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-clipboard-text text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Tes</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-user text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Profil</span>
        </a>
    </nav>

</body>
</html>