<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        /* PDF Download Overlay */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@keyframes pulse-text {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
@keyframes progress-fill {
    0% { width: 0%; }
    20% { width: 25%; }
    50% { width: 60%; }
    80% { width: 85%; }
    100% { width: 100%; }
}
#pdf-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 99999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
#pdf-overlay.active {
    display: flex;
}
#pdf-overlay-card {
    background: white;
    border-radius: 20px;
    padding: 2.5rem 3rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
    min-width: 300px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.2);
}
#pdf-spinner {
    width: 52px;
    height: 52px;
    border: 4px solid #EBF5FF;
    border-top-color: #4A90E2;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}
#pdf-overlay-title {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}
#pdf-overlay-subtitle {
    font-size: 0.8rem;
    color: #6b7280;
    margin: -0.5rem 0 0;
    animation: pulse-text 1.5s ease-in-out infinite;
}
#pdf-progress-track {
    width: 100%;
    height: 6px;
    background: #f3f4f6;
    border-radius: 99px;
    overflow: hidden;
}
#pdf-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(to right, #4A90E2, #6DD5FA);
    border-radius: 99px;
    transition: width 0.4s ease;
}
        /* Animasi Notifikasi */
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-15deg); }
        }
        .animate-ring {
            animation: ring 0.5s ease-in-out infinite;
        }

        /* CSS Khusus Print Laporan */
        @media print {
            body { background: white; -webkit-print-color-adjust: exact; }
            aside, .mobile-nav, .dashboard-header, .no-print { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; }
            .shadow-lg, .shadow-sm, .shadow-[0_10px_40px_rgba(0,0,0,0.08)] { box-shadow: none !important; }
            .report-card { border: none; max-width: 100%; box-shadow: none; margin: 0; padding: 0; }
            .bar-bg { background-color: #f3f4f6 !important; } 
        }
        
        /* Animasi Laporan */
        .bar-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

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
        
        <div class="dashboard-header flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
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
        </div> 

        <div class="w-full flex justify-center">
            
            @if(isset($result) && $result)
                <div id="report-content" class="bg-white w-full max-w-[900px] rounded-[20px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] overflow-hidden report-card mb-10 relative">
                    
                    <div id="loading" class="absolute inset-0 bg-white z-50 flex flex-col justify-center items-center rounded-[20px]">
                        <i class="ph ph-spinner ph-spin text-4xl text-[#4A90E2] mb-3"></i>
                        <p class="text-gray-500 font-medium">Menganalisis data laporan...</p>
                    </div>

                    <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] p-8 md:p-10 text-white relative overflow-hidden">
                        <div class="absolute top-[-50%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-[-50%] left-[-10%] w-48 h-48 bg-white/10 rounded-full blur-2xl"></div>

                        <div class="relative z-10 text-center md:text-left flex flex-col md:flex-row justify-between items-center gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold mb-1">Laporan Hasil Analisis</h1>
                                <p class="opacity-90 text-sm">Tes Minat Bakat (RIASEC) - {{ $anak->name ?? 'Siswa' }}</p>
                            </div>
                            <div class="text-right hidden md:block">
                                <div class="text-2xl font-bold">{{ $anak->name ?? $namaPemilik ?? 'Siswa' }}</div>
                                <div class="text-sm opacity-80" id="testDate">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-10">
                        
                        <div class="animate-fade-in" style="animation-delay: 0.2s;">
                            <div class="bg-[#EBF5FF] border-l-[6px] border-[#4A90E2] p-6 rounded-r-xl mb-8 shadow-sm flex flex-col md:flex-row gap-6 items-center">
                                <div class="text-center min-w-[120px]">
                                    <div class="text-xs text-[#4A90E2] font-bold uppercase tracking-wider mb-1">Kode Dominan</div>
                                    <div id="domCode" class="text-4xl md:text-5xl font-extrabold text-[#4A90E2] tracking-widest">---</div>
                                </div>
                               <div class="flex-1 text-center md:text-left border-t md:border-t-0 md:border-l border-blue-100 pt-4 md:pt-0 md:pl-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $aiData['judul'] ?? 'Menunggu Hasil...' }}</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ $aiData['deskripsi'] ?? 'Sistem sedang memproses hasil kepribadian anak Anda.' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="animate-fade-in" style="animation-delay: 0.4s;">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="ph-fill ph-chart-bar text-[#4A90E2]"></i> Rincian Skor Potensi
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-red-500"></span> Realistic</span>
                                        <span id="scoreR" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barR" class="bar-fill h-full bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Investigative</span>
                                        <span id="scoreI" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barI" class="bar-fill h-full bg-blue-500 rounded-full"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Artistic</span>
                                        <span id="scoreA" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barA" class="bar-fill h-full bg-yellow-400 rounded-full"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span> Social</span>
                                        <span id="scoreS" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barS" class="bar-fill h-full bg-green-500 rounded-full"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-purple-500"></span> Enterprising</span>
                                        <span id="scoreE" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barE" class="bar-fill h-full bg-purple-500 rounded-full"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2 text-sm font-medium">
                                        <span class="text-gray-700 flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-500"></span> Conventional</span>
                                        <span id="scoreC" class="text-gray-900 font-bold">0</span>
                                    </div>
                                    <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden bar-bg">
                                        <div id="barC" class="bar-fill h-full bg-gray-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 animate-fade-in" style="animation-delay: 0.5s;">
                            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="ph-fill ph-chart-line text-[#4A90E2]"></i> Analisis Visual
                            </h3>
                            <div class="w-full h-[320px] relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <canvas id="riasecChart"></canvas>
                            </div>
                        </div>

                        <div class="mt-10 animate-fade-in" style="animation-delay: 0.6s;">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                <i class="ph-fill ph-student text-[#4A90E2]"></i> Rekomendasi Jurusan
                            </h3>
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                                    @if(isset($aiData['jurusan']))
                                        @foreach($aiData['jurusan'] as $jurusan)
                                            <li>{{ $jurusan }}</li>
                                        @endforeach
                                    @else
                                        <li>Gagal memuat rekomendasi jurusan dari AI.</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 animate-fade-in" style="animation-delay: 0.7s;">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                    <i class="ph-fill ph-buildings text-[#4A90E2]"></i> Rekomendasi Kampus
                                </h3>
                                <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100 h-full">
                                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                                        @if(isset($aiData['kampus']))
                                            @foreach($aiData['kampus'] as $kampus)
                                                <li>{{ $kampus }}</li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 italic">Belum ada rekomendasi kampus.</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-100 pb-2">
                                    <i class="ph-fill ph-lightbulb text-[#FF9F43]"></i> Tips Belajar
                                </h3>
                                <div class="bg-orange-50/50 rounded-xl p-5 border border-orange-100 h-full">
                                    <ul class="list-disc list-inside text-gray-700 text-sm space-y-2">
                                        @if(isset($aiData['tips']))
                                            @foreach($aiData['tips'] as $tips)
                                                <li>{{ $tips }}</li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 italic">Belum ada tips belajar.</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div id="action-buttons" class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row gap-4 justify-center no-print animate-fade-in" style="animation-delay: 0.8s;">
                            <button onclick="downloadPDF()" class="px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-download-simple"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>

                 <div id="pdf-overlay">
    <div id="pdf-overlay-card">
        <div id="pdf-spinner"></div>
        <p id="pdf-overlay-title">Menyiapkan PDF...</p>
        <p id="pdf-overlay-subtitle">Mohon tunggu sebentar</p>
        <div id="pdf-progress-track">
            <div id="pdf-progress-bar"></div>
        </div>
    </div>
</div>

            @else
                <div class="bg-white w-full max-w-[900px] p-10 rounded-[20px] shadow-sm border border-gray-100 text-center flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center text-4xl mb-4">
                        <i class="ph-fill ph-file-dashed"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan Belum Tersedia</h3>
                    <p class="text-gray-500 text-sm max-w-[400px]">
                        Saat ini anak Anda belum menyelesaikan kuesioner minat bakat, atau Anda belum terhubung dengan akun siswa. Laporan akan otomatis muncul di sini setelah tes diselesaikan.
                    </p>
                </div>
            @endif

        </div>
    </main>

    <div class="mobile-nav fixed bottom-0 w-full bg-white border-t border-gray-200 p-3 flex md:hidden justify-around z-50">
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

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const menu = document.getElementById('notificationMenu');
            if (dropdown && !dropdown.contains(event.target) && menu && !menu.classList.contains('hidden')) {
                toggleNotifications();
            }
        });

        // ==========================================
        // FUNGSI RENDER LAPORAN (HANYA JIKA ADA DATA)
        // ==========================================
        @if(isset($result) && $result)
        const mockResult = {
            created_at: "{{ $result->created_at }}",
            scores: { 
                R: {{ $result->score_r }}, 
                I: {{ $result->score_i }}, 
                A: {{ $result->score_a }}, 
                S: {{ $result->score_s }}, 
                E: {{ $result->score_e }}, 
                C: {{ $result->score_c }} 
            }, 
            dominant_code: "{{ $result->dominant_code }}"
        };

        function renderReport() {
            setTimeout(() => {
                document.getElementById('loading').style.display = 'none';

                const dateObj = new Date(mockResult.created_at);
                document.getElementById('testDate').innerText = dateObj.toLocaleDateString('id-ID', { 
                    day: 'numeric', month: 'long', year: 'numeric' 
                });

                document.getElementById('domCode').innerText = mockResult.dominant_code;

                const maxDisplayScore = 15; 
                updateBar('barR', 'scoreR', mockResult.scores.R, maxDisplayScore);
                updateBar('barI', 'scoreI', mockResult.scores.I, maxDisplayScore);
                updateBar('barA', 'scoreA', mockResult.scores.A, maxDisplayScore);
                updateBar('barS', 'scoreS', mockResult.scores.S, maxDisplayScore);
                updateBar('barE', 'scoreE', mockResult.scores.E, maxDisplayScore);
                updateBar('barC', 'scoreC', mockResult.scores.C, maxDisplayScore);

                const canvas = document.getElementById('riasecChart');
                if(canvas) {
                    const ctx = canvas.getContext('2d');
                    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(74, 144, 226, 0.5)'); 
                    gradient.addColorStop(1, 'rgba(74, 144, 226, 0.0)'); 

                    new Chart(ctx, {
                        type: 'line', 
                        data: {
                            labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
                            datasets: [{
                                label: 'Poin',
                                data: [
                                    mockResult.scores.R, mockResult.scores.I, mockResult.scores.A, 
                                    mockResult.scores.S, mockResult.scores.E, mockResult.scores.C
                                ],
                                borderColor: '#4A90E2',           
                                backgroundColor: gradient,        
                                borderWidth: 3,                   
                                pointBackgroundColor: '#ffffff',  
                                pointBorderColor: '#4A90E2',      
                                pointBorderWidth: 2,              
                                pointRadius: 5,                   
                                pointHoverRadius: 7,              
                                fill: true,                       
                                tension: 0.4                      
                            }]
                        },
                        options: {
                             animation: {
        onComplete: () => { window._chartReady = true; }
    },
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 10,
                                    grid: { color: '#f3f4f6', borderDash: [5, 5] },
                                    border: { display: false }
                                },
                                x: {
                                    grid: { display: false }, 
                                    border: { display: false },
                                    ticks: { font: { family: "'Poppins', sans-serif", weight: '500' }, color: '#6b7280' }
                                }
                            }
                        }
                    });
                }
            }, 500); 
        }

        function updateBar(barId, textId, score, max) {
            let percentage = (score / max) * 100;
            if(percentage > 100) percentage = 100;
            
            const bar = document.getElementById(barId);
            const text = document.getElementById(textId);
            
            if (text) text.innerText = `${score} Poin`;
            if (bar) {
                setTimeout(() => {
                    bar.style.width = percentage + "%";
                }, 100);
            }
        }

   async function downloadPDF() {
    // --- Show overlay ---
    const overlay = document.getElementById('pdf-overlay');
    const overlayTitle = document.getElementById('pdf-overlay-title');
    const overlaySubtitle = document.getElementById('pdf-overlay-subtitle');
    const progressBar = document.getElementById('pdf-progress-bar');

    function setProgress(pct, title, subtitle) {
        progressBar.style.width = pct + '%';
        if (title) overlayTitle.textContent = title;
        if (subtitle) overlaySubtitle.textContent = subtitle;
    }

    overlay.classList.add('active');
    setProgress(0, 'Menyiapkan laporan...', 'Mengambil data hasil tes');

    await new Promise(r => setTimeout(r, 300));

    // Step 1 — wait for chart
    setProgress(15, 'Memproses grafik...', 'Mengkonversi chart ke gambar');
    if (!window._chartReady) {
        await new Promise(r => setTimeout(r, 1000));
    }

    const canvas = document.getElementById('riasecChart');
    let chartImageSrc = '';
    if (canvas && canvas.width > 0) {
        chartImageSrc = canvas.toDataURL('image/png', 1.0);
    }

    await new Promise(r => setTimeout(r, 200));
    setProgress(30, 'Menyusun halaman...', 'Membangun layout dokumen');

    // Step 2 — grab HTML
    const buttons = document.getElementById('action-buttons');
    if (buttons) buttons.style.display = 'none';
    const reportHTML = document.getElementById('report-content').innerHTML;
    if (buttons) buttons.style.display = 'flex';

    await new Promise(r => setTimeout(r, 200));
    setProgress(50, 'Membuat dokumen...', 'Memuat font dan aset');

    // Step 3 — build iframe
    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed;top:0;left:0;width:900px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-1;';
    iframe.setAttribute('data-pdf-frame', 'true');
    document.body.appendChild(iframe);

    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

    iframeDoc.open();
    iframeDoc.write(`
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            <script src="https://unpkg.com/@phosphor-icons/web"><\/script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"><\/script>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: 'Poppins', sans-serif; background: white; color: #333; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .bg-gradient-to-r { background: linear-gradient(to right, #4A90E2, #6DD5FA) !important; }
                .text-white { color: white !important; }
                .p-8 { padding: 2rem; }
                .md\\:p-10 { padding: 2.5rem; }
                .text-2xl { font-size: 1.5rem; }
                .text-3xl { font-size: 1.875rem; }
                .font-bold { font-weight: 700; }
                .font-extrabold { font-weight: 800; }
                .font-semibold { font-weight: 600; }
                .font-medium { font-weight: 500; }
                .mb-1 { margin-bottom: 0.25rem; }
                .mb-2 { margin-bottom: 0.5rem; }
                .mb-4 { margin-bottom: 1rem; }
                .mb-6 { margin-bottom: 1.5rem; }
                .mb-8 { margin-bottom: 2rem; }
                .mt-6 { margin-top: 1.5rem; }
                .mt-10 { margin-top: 2.5rem; }
                .pt-6 { padding-top: 1.5rem; }
                .pb-2 { padding-bottom: 0.5rem; }
                .p-5 { padding: 1.25rem; }
                .p-6 { padding: 1.5rem; }
                .p-4 { padding: 1rem; }
                .gap-2 { gap: 0.5rem; }
                .gap-4 { gap: 1rem; }
                .gap-6 { gap: 1.5rem; }
                .gap-8 { gap: 2rem; }
                .flex { display: flex; }
                .flex-col { flex-direction: column; }
                .flex-1 { flex: 1; }
                .grid { display: grid; }
                .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
                .md\\:grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
                .gap-x-8 { column-gap: 2rem; }
                .gap-y-6 { row-gap: 1.5rem; }
                .w-full { width: 100%; }
                .h-full { height: 100%; }
                .text-sm { font-size: 0.875rem; }
                .text-xs { font-size: 0.75rem; }
                .text-lg { font-size: 1.125rem; }
                .text-4xl { font-size: 2.25rem; }
                .text-5xl { font-size: 3rem; }
                .leading-relaxed { line-height: 1.625; }
                .tracking-widest { letter-spacing: 0.1em; }
                .tracking-wider { letter-spacing: 0.05em; }
                .uppercase { text-transform: uppercase; }
                .rounded-full { border-radius: 9999px; }
                .rounded-xl { border-radius: 0.75rem; }
                .rounded-r-xl { border-radius: 0 0.75rem 0.75rem 0; }
                .overflow-hidden { overflow: visible !important; }
                .border-b { border-bottom: 1px solid; }
                .border-t { border-top: 1px solid; }
                .border-l { border-left: 1px solid; }
                .border-l-\\[6px\\] { border-left-width: 6px !important; }
                .border-\\[\\#4A90E2\\] { border-color: #4A90E2 !important; }
                .border-gray-100 { border-color: #f3f4f6 !important; }
                .border-blue-100 { border-color: #dbeafe !important; }
                .border-orange-100 { border-color: #ffedd5 !important; }
                .text-\\[\\#4A90E2\\] { color: #4A90E2 !important; }
                .text-\\[\\#FF9F43\\] { color: #FF9F43 !important; }
                .text-gray-800 { color: #1f2937 !important; }
                .text-gray-700 { color: #374151 !important; }
                .text-gray-600 { color: #4b5563 !important; }
                .text-gray-500 { color: #6b7280 !important; }
                .text-gray-900 { color: #111827 !important; }
                .bg-\\[\\#EBF5FF\\] { background-color: #EBF5FF !important; }
                .bg-gray-50 { background-color: #f9fafb !important; }
                .bg-gray-100 { background-color: #f3f4f6 !important; }
                .bg-white { background-color: white !important; }
                .h-2\\.5 { height: 0.625rem; }
                .w-2 { width: 0.5rem; }
                .h-2 { height: 0.5rem; }
                .min-w-\\[120px\\] { min-width: 120px; }
                .text-center { text-align: center; }
                .text-left { text-align: left; }
                .text-right { text-align: right; }
                .list-disc { list-style-type: disc; }
                .list-inside { list-style-position: inside; }
                .space-y-2 > * + * { margin-top: 0.5rem; }
                .italic { font-style: italic; }
                .shadow-sm { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
                .relative { position: relative; }
                .absolute { position: absolute; }
                .inset-0 { top:0;right:0;bottom:0;left:0; }
                .z-10 { z-index: 10; }
                .hidden { display: none !important; }
                .no-print { display: none !important; }
                .items-center { align-items: center; }
                .items-start { align-items: flex-start; }
                .justify-between { justify-content: space-between; }
                .justify-center { justify-content: center; }
                .flex-shrink-0 { flex-shrink: 0; }
                .md\\:flex-row { flex-direction: row; }
                .md\\:text-left { text-align: left; }
                .md\\:border-l { border-left: 1px solid; }
                .md\\:border-t-0 { border-top: none; }
                .md\\:pl-6 { padding-left: 1.5rem; }
                .md\\:pt-0 { padding-top: 0; }
                .md\\:block { display: block; }
                .pt-4 { padding-top: 1rem; }
                .blur-2xl { filter: blur(40px); }
                .bg-white\\/10 { background: rgba(255,255,255,0.1) !important; }
                .w-64 { width: 16rem; }
                .h-64 { height: 16rem; }
                .w-48 { width: 12rem; }
                .h-48 { height: 12rem; }
                .top-\\[-50\\%\\] { top: -50%; }
                .right-\\[-10\\%\\] { right: -10%; }
                .bottom-\\[-50\\%\\] { bottom: -50%; }
                .left-\\[-10\\%\\] { left: -10%; }
                .bar-bg { background-color: #f3f4f6 !important; }
                .bar-fill { height: 100%; border-radius: 9999px; transition: none !important; }
                .bg-red-500 { background-color: #ef4444 !important; }
                .bg-blue-500 { background-color: #3b82f6 !important; }
                .bg-yellow-400 { background-color: #facc15 !important; }
                .bg-green-500 { background-color: #22c55e !important; }
                .bg-purple-500 { background-color: #a855f7 !important; }
                .bg-gray-500 { background-color: #6b7280 !important; }
                .bg-blue-50\\/50 { background-color: rgba(239,246,255,0.5) !important; }
                .bg-orange-50\\/50 { background-color: rgba(255,247,237,0.5) !important; }
                .opacity-90 { opacity: 0.9; }
                .opacity-80 { opacity: 0.8; }
                .animate-fade-in { opacity: 1 !important; transform: none !important; }
                .w-\\[320px\\] { width: 320px; }
                .h-\\[320px\\] { height: 320px; }
                #loading { display: none !important; }
                #action-buttons { display: none !important; }
            </style>
        </head>
        <body>
            <div id="pdf-root" style="width:900px;background:white;">
                ${reportHTML}
            </div>
            <script>
                const canvas = document.querySelector('canvas');
                if (canvas) {
                    const img = document.createElement('img');
                    img.src = '${chartImageSrc}';
                    img.style.cssText = 'width:100%;height:100%;object-fit:fill;display:block;';
                    canvas.parentNode.replaceChild(img, canvas);
                }
                const scores = ${JSON.stringify(mockResult.scores)};
                const max = 15;
                const barMap = { R:'barR', I:'barI', A:'barA', S:'barS', E:'barE', C:'barC' };
                Object.entries(barMap).forEach(([key, id]) => {
                    const bar = document.getElementById(id);
                    if (bar) bar.style.width = Math.min((scores[key] / max) * 100, 100) + '%';
                });

                window.addEventListener('load', async function() {
                    await new Promise(r => setTimeout(r, 600));
                    const el = document.getElementById('pdf-root');
                    const opt = {
                        margin: [0.2, 0, 0.2, 0],
                        filename: 'Laporan_RIASEC_${mockResult.dominant_code}.pdf',
                        image: { type: 'jpeg', quality: 1.0 },
                        html2canvas: { scale: 2, useCORS: true, scrollX: 0, scrollY: 0, windowWidth: 900, logging: false },
                        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
                        pagebreak: { mode: ['css', 'legacy'] }
                    };
                    await html2pdf().set(opt).from(el).save();

                    // Notify parent that PDF is done
                    window.parent.postMessage('pdf-done', '*');
                });
            <\/script>
        </body>
        </html>
    `);
    iframeDoc.close();

    // Step 4 — listen for iframe to finish
    setProgress(70, 'Merender konten...', 'Sedang memproses halaman');

    await new Promise(resolve => {
        window.addEventListener('message', function handler(e) {
            if (e.data === 'pdf-done') {
                window.removeEventListener('message', handler);
                resolve();
            }
        });
    });

    // Step 5 — done!
    setProgress(100, 'Selesai!', 'PDF berhasil diunduh');

    await new Promise(r => setTimeout(r, 800));

    // Clean up
    const frame = document.querySelector('iframe[data-pdf-frame]');
    if (frame) frame.remove();

    overlay.classList.remove('active');
}


        // Eksekusi fungsi render
        renderReport();
        @endif
    </script>
</body>
</html>