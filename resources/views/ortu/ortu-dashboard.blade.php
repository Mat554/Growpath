<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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


<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden text-[#333]">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20 shadow-[0_0_20px_rgba(0,0,0,0.03)]">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> Growpath
        </div>
        
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all shadow-sm">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
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
                <p class="text-gray-500 text-sm mt-1">Selamat datang di portal pemantauan minat bakat.</p>
            </div>
            
            <div class="flex items-center justify-end gap-4 ml-auto">
                
                <div class="relative" id="notificationDropdown">
                    <button onclick="toggleNotifications()" class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-sm text-gray-500 hover:text-[#4A90E2] transition-colors relative cursor-pointer border border-gray-100 focus:outline-none">
                        
                        <i id="bellIcon" class="ph-fill ph-bell text-xl {{ isset($anak) && $anak ? 'text-[#4A90E2] animate-ring' : '' }}"></i>
                        
                        @if(isset($anak) && $anak)
                            <span id="notifBadge" class="absolute top-2.5 right-3 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                        @endif
                    </button>

                    <div id="notificationMenu" class="hidden absolute right-0 mt-3 w-[320px] bg-white rounded-2xl shadow-[0_15px_40px_rgba(0,0,0,0.12)] border border-gray-100 z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200 origin-top-right">
                        <div class="p-4 border-b border-gray-100 bg-[#F8FAFC]">
                            <h3 class="font-bold text-gray-800 text-sm">Notifikasi</h3>
                        </div>
                        
                        <div class="max-h-[300px] overflow-y-auto">
                            @if(isset($anak) && $anak)
                                <div class="p-4 border-b border-gray-50 flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[#EBF5FF] text-[#4A90E2] flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="ph-fill ph-student text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-800 leading-snug">
                                            Terhubung dengan: <strong class="text-[#4A90E2]">{{ $anak->name }}</strong>.
                                        </p>
                                        <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" onsubmit="return confirm('Berhenti memantau akun anak ini?')">
                                            @csrf
                                            <button type="submit" class="text-[11px] text-red-500 font-semibold hover:underline mt-2 bg-transparent border-none p-0 cursor-pointer">
                                                Lepas Koneksi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="p-8 text-center text-gray-400 text-sm">Tidak ada notifikasi</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white px-5 py-2.5 rounded-full shadow-sm border border-gray-100 flex items-center gap-2.5 text-sm font-semibold text-[#4A90E2]">
                    <i class="ph-fill ph-users text-lg"></i>
                    <span>Wali Murid</span>
                </div>
            </div> 
            
        </div> <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @php
                // Mengecek apakah ada minimal 1 skor di database
                $isAnakSelesai = $hasilTesAnak->isNotEmpty();
            @endphp

            <div class="bg-white p-6 rounded-[18px] border border-gray-100 transition-all duration-300 flex flex-col {{ $isAnakSelesai ? 'shadow-[0_5px_20px_rgba(0,0,0,0.05)] hover:-translate-y-1' : 'shadow-sm opacity-60' }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl {{ $isAnakSelesai ? 'bg-[#EBF5FF] text-[#4A90E2]' : 'bg-gray-100 text-gray-400' }}">
                        <i class="ph-fill ph-student"></i>
                    </div>
                    
                    @if($isAnakSelesai)
                        <span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase border border-green-100">
                            Tersedia
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-xs font-bold uppercase border border-gray-200">
                            Menunggu Anak
                        </span>
                    @endif
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Laporan Hasil {{ $anak->name ?? 'Anak' }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Lihat hasil analisis gaya berpikir anak Anda berdasarkan kuesioner yang telah diselesaikan.
                </p>
                
                @if($isAnakSelesai)
                    <a href="{{ route('laporan.ortu') }}" class="mt-auto w-full py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all text-center block shadow-lg shadow-[#4A90E2]/30">
                        Lihat Laporan Lengkap
                    </a>
                @else
                    <button disabled class="mt-auto w-full py-3 bg-gray-100 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed text-center block">
                        Belum Tersedia
                    </button>
                @endif
            </div>

            <div class="bg-white p-6 rounded-[18px] shadow-[0_5px_20px_rgba(0,0,0,0.05)] border border-gray-100 hover:-translate-y-1 transition-transform duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-gray-50 text-gray-500 rounded-xl flex items-center justify-center text-2xl">
                        <i class="ph-fill ph-calendar-check"></i>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Agenda Sekolah</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">
                    Jadwal pertemuan wali murid dan konseling karir mendatang.
                </p>
                <button class="w-full py-3 bg-gray-50 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                    Tidak Ada Jadwal
                </button>
            </div>

        </div>
    </main>

    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around z-50">
        <a href="{{ route('dashboard.ortu') }}" class="flex flex-col items-center text-[#4A90E2]">
            <i class="ph-fill ph-squares-four text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Home</span>
        </a>
        <a href="{{ route('profile.ortu') }}" class="flex flex-col items-center text-gray-400">
            <i class="ph ph-user text-2xl"></i>
            <span class="text-[10px] font-medium mt-1">Profil</span>
        </a>
    </div>

    <script>
       function toggleNotifications() {
    const menu = document.getElementById('notificationMenu');
    const bell = document.getElementById('bellIcon');
    const badge = document.getElementById('notifBadge');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        // Matikan animasi dan hilangkan titik merah saat dibuka
        if(bell) bell.classList.remove('animate-ring');
        if(badge) badge.classList.add('hidden');

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

        // Tutup saat klik di luar
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const menu = document.getElementById('notificationMenu');
            
            if (!dropdown.contains(event.target) && !menu.classList.contains('hidden')) {
                toggleNotifications();
            }
        });
    </script>

</body>
</html>