<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tips & Strategi Belajar - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans text-[#333] min-h-screen flex flex-col">

    <nav class="bg-white border-b border-gray-200 px-6 py-4 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                {{-- Anda bisa membungkus logo ini dengan <a href="{{ route('home') }}"> jika ingin menjadi link --}}
                <span class="text-xl font-bold text-gray-800 tracking-tight">Grow<span class="text-[#4A90E2]">path</span></span>
            </div>
            
            <button onclick="window.history.back()" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-all flex items-center gap-2">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </button>
        </div>
    </nav>

    <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] py-16 relative overflow-hidden">
        <div class="absolute top-[-50%] right-[10%] w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-50%] left-[10%] w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <span class="bg-white/20 text-white px-4 py-1.5 rounded-full text-xs font-bold tracking-wider uppercase mb-4 inline-block">Growpath Academy</span>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Eksplorasi Metode Belajarmu</h1>
            <p class="text-blue-50 max-w-2xl mx-auto text-sm md:text-base">
                Temukan berbagai strategi dan teknik belajar yang terbukti efektif untuk meningkatkan fokus, daya ingat, dan produktivitas kamu selama di sekolah maupun universitas.
            </p>
        </div>
    </div>

    <main class="flex-1 max-w-7xl mx-auto px-6 py-12 w-full">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.1s;">
                <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-timer"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Teknik Pomodoro</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Belajar fokus selama 25 menit, lalu istirahat 5 menit. Teknik ini sangat ampuh untuk mencegah kelelahan otak (burnout) dan menjaga konsentrasi tetap tinggi.
                </p>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.2s;">
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-brain"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Active Recall</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Jangan hanya membaca berulang-ulang! Tutup bukumu dan cobalah untuk mengingat atau menuliskan kembali konsep yang baru saja dipelajari untuk menguji memori.
                </p>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.3s;">
                <div class="w-12 h-12 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-chalkboard-teacher"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Teknik Feynman</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Konsep terbaik untuk memahami materi adalah dengan menjelaskannya menggunakan bahasa sederhana, seolah-olah kamu sedang mengajar seorang anak kecil.
                </p>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.4s;">
                <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-calendar-check"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Spaced Repetition</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Review materi yang sama dengan jeda waktu yang semakin lama (misal: 1 hari, 3 hari, 1 minggu kemudian) untuk memindahkan memori dari jangka pendek ke jangka panjang.
                </p>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.5s;">
                <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-tree-structure"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Peta Konsep (Mind Mapping)</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Visualisasikan informasi dengan menggambar struktur cabang. Ini sangat membantu bagi pembelajar visual untuk melihat gambaran besar dan hubungan antar materi.
                </p>
            </div>

            <div class="bg-white p-6 rounded-[20px] shadow-sm border border-gray-100 hover:shadow-md hover:border-[#4A90E2]/30 transition-all transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 0.6s;">
                <div class="w-12 h-12 bg-teal-50 text-teal-500 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    <i class="ph-fill ph-coffee"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Kelola Lingkungan</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Jauhkan distraksi (terutama notifikasi HP). Pastikan meja belajar bersih, pencahayaan cukup, dan siapkan air minum agar tubuh tetap terhidrasi.
                </p>
            </div>

        </div>

    </main>

    <footer class="text-center py-6 text-sm text-gray-400 mt-auto">
        &copy; {{ date('Y') }} Growpath. All rights reserved.
    </footer>

</body>
</html>