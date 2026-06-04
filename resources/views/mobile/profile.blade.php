<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profil Saya - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/student/profile.js'])
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
                <h1 class="font-bold text-gray-800 text-lg">Profil Saya</h1>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        <!-- Avatar Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            <div class="h-[80px] bg-gradient-to-br from-[#4A90E2] to-[#56CCF2]"></div>
            <div class="text-center -mt-[50px] px-4 pb-6">
                <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm" class="flex flex-col items-center">
                    @csrf
                    <div class="w-[100px] h-[100px] bg-white rounded-full p-1.5 shadow-md relative group">
                        <img id="avatarPreview" src="{{ Auth::user()->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4A90E2&color=fff&size=128' }}"
                             alt="Avatar" class="w-full h-full rounded-full object-cover">
                        <label for="avatarUpload" class="absolute inset-1.5 rounded-full overflow-hidden bg-black/50 opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer">
                            <i class="ph-fill ph-camera-plus text-white text-2xl mb-0.5"></i>
                            <span class="text-white text-[9px]">Pilih</span>
                        </label>
                        <input type="file" name="avatar" id="avatarUpload" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                    </div>

                    <div id="saveButtonContainer" class="hidden mt-3 flex-col items-center gap-2">
                        <button type="submit" class="px-4 py-1.5 bg-[#4A90E2] text-white rounded-full text-xs font-semibold">
                            Simpan
                        </button>
                        <button type="button" onclick="cancelUpload()" class="text-xs text-red-400">Batal</button>
                    </div>
                </form>

                <h3 class="text-lg font-bold text-gray-800 mt-3">{{ Auth::user()->name }}</h3>
                <span class="inline-block px-3 py-0.5 bg-[#EBF5FF] text-[#4A90E2] rounded-full text-[10px] font-bold uppercase">Siswa</span>

                <div class="mt-4 space-y-2">
                    <div class="bg-gray-50 p-3 rounded-xl text-left flex items-center gap-3 border-l-2 border-l-[#4A90E2]">
                        <i class="ph-fill ph-identification-card text-[#4A90E2]"></i>
                        <div>
                            <span class="text-[10px] text-gray-500">User ID</span>
                            <strong class="text-[#357ABD] text-sm font-mono block">{{ Auth::user()->user_code ?? '-' }}</strong>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl text-left flex items-center gap-3">
                        <i class="ph-fill ph-graduation-cap text-[#4A90E2]"></i>
                        <div>
                            <span class="text-[10px] text-gray-500">Kelas</span>
                            <strong class="text-gray-800 text-sm">{{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : 'Belum Diatur' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pribadi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-4">Data Pribadi</h3>

            <div class="space-y-3">
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Nama Lengkap</label>
                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl">
                        <i class="ph ph-user text-gray-400"></i>
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Email</label>
                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl">
                        <i class="ph ph-envelope text-gray-400"></i>
                        <span class="text-sm text-gray-700">{{ Auth::user()->email }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 mb-1">Kelas</label>
                    <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl">
                        <i class="ph ph-graduation-cap text-gray-400"></i>
                        <span class="text-sm text-gray-700">{{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kode Sambung Orang Tua -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-[#4A90E2] mb-2 flex items-center gap-2">
                <i class="ph-fill ph-qr-code"></i> Kode Sambung Orang Tua
            </h3>
            <p class="text-[10px] text-gray-500 mb-3">Berikan User ID ini kepada Orang Tua untuk menghubungkan akun.</p>

            <div class="relative group cursor-pointer" onclick="copyToClipboard('{{ Auth::user()->user_code }}')">
                <input type="text" value="{{ Auth::user()->user_code ?? 'BELUM-ADA' }}" readonly
                    class="w-full px-4 py-3 bg-[#EBF5FF] border-2 border-dashed border-[#4A90E2] rounded-xl text-center font-mono font-bold text-base text-[#357ABD] tracking-wider cursor-pointer">
                <div class="absolute inset-y-0 right-3 flex items-center">
                    <i class="ph ph-copy text-[#4A90E2]"></i>
                </div>
            </div>
            <p class="text-[10px] text-[#27ae60] mt-2 flex items-center gap-1">
                <i class="ph-fill ph-check-circle"></i> Kode aktif
            </p>
        </div>

        <!-- Keamanan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <i class="ph-fill ph-lock-key"></i> Keamanan Akun
            </h3>
            <a href="{{ route('profile.ubah-password') }}" class="block w-full py-3 bg-white border border-[#4A90E2] text-[#4A90E2] rounded-xl font-semibold text-sm text-center">
                Ubah Password
            </a>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 bg-red-50 text-red-500 rounded-xl font-semibold text-sm flex items-center justify-center gap-2">
                <i class="ph ph-sign-out"></i> Keluar
            </button>
        </form>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-50">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-squares-four text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Home</span>
        </a>
        <a href="{{ route('kuesioner') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-clipboard-text text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Tes</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center text-[#4A90E2] py-1 px-4">
            <i class="ph-fill ph-user text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Profil</span>
        </a>
    </nav>

</body>
</html>