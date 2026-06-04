<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tips & Strategi Belajar - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        html, body { overflow-x: hidden; overscroll-behavior: none; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans text-[#333] min-h-screen pb-6">

    <!-- MOBILE HEADER -->
    <header class="bg-white px-4 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <button onclick="window.history.back()" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-500">
                    <i class="ph ph-arrow-left text-xl"></i>
                </button>
                <span class="text-lg font-bold text-gray-800">Grow<span class="text-[#4A90E2]">path</span></span>
            </div>
        </div>
    </header>

    <!-- HERO -->
    <div class="bg-gradient-to-r from-[#4A90E2] to-[#6DD5FA] px-4 py-6 relative overflow-hidden">
        <div class="absolute top-[-30%] right-[-20%] w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <span class="bg-white/20 text-white px-3 py-0.5 rounded-full text-[10px] font-bold uppercase inline-block mb-2">Growpath Academy</span>
            <h1 class="text-xl font-extrabold text-white mb-2">Eksplorasi Metode Belajarmu</h1>
            <p class="text-blue-50 text-xs leading-relaxed">
                Temukan strategi dan teknik belajar efektif untuk meningkatkan fokus dan produktivitas.
            </p>
        </div>
    </div>

    <!-- TIPS GRID -->
    <main class="px-4 py-4 animate-fade-in">
        <div class="space-y-3">

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-timer"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Teknik Pomodoro</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Belajar fokus 25 menit, istirahat 5 menit. Sangat ampuh untuk mencegah burnout.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-brain"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Active Recall</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Tutup buku dan coba ingat kembali materi yang sudah dipelajari untuk menguji memori.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Teknik Feynman</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Jelaskan materi dengan bahasa sederhana, seolah-olah kamu sedang mengajar anak kecil.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Spaced Repetition</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Review materi dengan jeda waktu semakin lama (1 hari, 3 hari, 1 minggu) untuk memori jangka panjang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-tree-structure"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Mind Mapping</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Visualisasikan informasi dengan menggambar struktur cabang untuk melihat gambaran besar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-teal-50 text-teal-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="ph-fill ph-coffee"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm mb-1">Kelola Lingkungan</h3>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            Jauhkan distraksi, meja bersih, pencahayaan cukup, dan tetap terhidrasi.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>