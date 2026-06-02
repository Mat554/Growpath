<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/student/profile.js'])

    <style>
        /* Mobile menu overlay */
        .mobile-overlay {
            transition: opacity 0.3s ease;
        }
        .mobile-menu {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans">

    <!-- Mobile Header -->
    <header class="md:hidden sticky top-0 z-30 bg-white border-b border-gray-100 px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="text-lg font-bold text-[#4A90E2] flex items-center gap-2">
                <i class="ph-fill ph-brain text-xl"></i> Growpath
            </div>
            <button id="mobileMenuBtn" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                <i class="ph ph-list text-2xl text-gray-600"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobileOverlay" class="mobile-overlay fixed inset-0 bg-black/50 z-40 hidden opacity-0" onclick="closeMobileMenu()"></div>

    <!-- Mobile Sidebar -->
    <aside id="mobileSidebar" class="mobile-menu fixed top-0 left-0 w-[260px] h-full bg-white z-50 flex flex-col transform -translate-x-full">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5">
                    <i class="ph-fill ph-brain text-2xl"></i> Growpath
                </div>
                <button onclick="closeMobileMenu()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="ph ph-x text-xl text-gray-500"></i>
                </button>
            </div>
        </div>
        <nav class="flex-1 flex flex-col gap-2 p-4">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
            <a href="{{ route('kuesioner') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Test
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-gray-100">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <!-- Desktop Sidebar -->
    <aside class="w-[260px] bg-white h-screen flex flex-col border-r border-gray-100 p-6 hidden md:flex fixed left-0 top-0 transition-all z-20">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-10">
            <i class="ph-fill ph-brain text-2xl"></i> Growpath
        </div>
        <nav class="flex-1 flex flex-col gap-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
            <a href="{{ route('kuesioner') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Test
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="md:ml-[260px] p-4 md:p-8 pb-20 md:pb-8">
        <div class="mb-6 md:mb-8">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800">Profil Siswa</h2>
        </div>

        <div class="flex flex-col gap-6 lg:gap-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-[80px] md:h-[120px] bg-gradient-to-br from-[#4A90E2] to-[#56CCF2]"></div>

                <div class="text-center -mt-[50px] md:-mt-[60px] relative px-4 md:px-6">
                    <form action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm" class="flex flex-col items-center">
                        @csrf

                        <div class="w-[100px] h-[100px] md:w-[120px] md:h-[120px] bg-white rounded-full p-1.5 shadow-md relative group">

                            <img id="avatarPreview" src="{{ Auth::user()->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4A90E2&color=fff&size=128' }}"
                                 alt="Avatar" class="w-full h-full rounded-full object-cover">

                            <label for="avatarUpload" class="absolute inset-1.5 rounded-full overflow-hidden bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer backdrop-blur-sm">
                                <i class="ph-fill ph-camera-plus text-white text-2xl md:text-3xl mb-1"></i>
                                <span class="text-white text-[9px] md:text-[10px] font-medium">Pilih Foto</span>
                            </label>

                            <input type="file" name="avatar" id="avatarUpload" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                        </div>

                        <div id="saveButtonContainer" class="hidden mt-4 flex-col items-center gap-2">
                            <button type="submit" class="px-5 py-2 bg-[#4A90E2] hover:bg-[#4A90E2] text-white rounded-full font-semibold text-xs transition-all shadow-sm flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-sm"></i> Simpan Foto
                            </button>
                            <button type="button" onclick="cancelUpload()" class="text-xs text-red-400 hover:text-red-600 font-medium transition-all">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>

                <div class="text-center px-4 md:px-6 pb-6 md:pb-8 pt-3 md:pt-4">
                    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h3>
                    <span class="inline-block px-3 py-1 bg-[#EBF5FF] text-[#4A90E2] rounded-full text-xs font-bold uppercase tracking-wide mb-4 md:mb-6">
                        SISWA
                    </span>

                    <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl flex items-center gap-4 text-left mb-3 border-l-4 border-l-[#4A90E2]">
                        <div class="w-10 h-10 bg-[#EBF5FF] rounded-lg flex items-center justify-center text-[#4A90E2] text-xl">
                            <i class="ph-fill ph-identification-card"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">User ID</span>
                            <strong class="text-[#357ABD] text-base font-mono tracking-wide">
                                {{ Auth::user()->user_code ?? '-' }}
                            </strong>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl flex items-center gap-4 text-left mb-3">
                        <div class="w-10 h-10 bg-[#EBF5FF] rounded-lg flex items-center justify-center text-[#4A90E2] text-xl">
                            <i class="ph-fill ph-graduation-cap"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 block">Kelas</span>
                            <strong class="text-gray-800 text-base">
                                {{ Auth::user()->kelas ? 'Kelas ' . Auth::user()->kelas : 'Belum Diatur' }}
                            </strong>
                        </div>
                    </div>

                    <div class="bg-[#E8F9F5] border border-dashed border-[#2ECC71] p-3 md:p-4 rounded-xl flex items-center gap-3 md:gap-4 text-left mt-4">
                        <i class="ph-fill ph-shield-check text-xl md:text-2xl text-[#2ECC71]"></i>
                        <div>
                            <span class="text-xs text-gray-500 block">Status Akun</span>
                            <strong class="text-[#27ae60] text-sm">Akun Terverifikasi</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Data Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 lg:p-8">

                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[#4A90E2]"></i> Data Pribadi
                </h3>

                <form action="#" method="POST"> @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4 md:mb-5">
                        <div>
                            <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text"
                                    value="{{ Auth::user()->name }}"
                                    readonly
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none transition-all">
                                <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            </div>
                        </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <div class="relative">
                                <input type="text" value="Kelas {{ Auth::user()->kelas ?? '-' }}" readonly class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                                <i class="ph ph-graduation-cap absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 md:mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            <i class="ph ph-envelope-simple absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                    </div>
                </form>

                <hr class="border-gray-100 my-6 md:my-8">

                <h3 class="text-base md:text-lg font-semibold text-[#4A90E2] mb-2 flex items-center gap-2">
                    <i class="ph-fill ph-qr-code"></i> Kode Sambung Orang Tua
                </h3>
                <p class="text-sm text-gray-500 mb-4 md:mb-5">
                    Berikan <strong>User ID</strong> ini kepada Orang Tua Anda untuk menghubungkan akun.
                </p>

                <div class="mb-4 md:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User ID Anda</label>
                    <div class="relative group cursor-copy" onclick="copyToClipboard('{{ Auth::user()->user_code }}')">
                        <input type="text" value="{{ Auth::user()->user_code ?? 'BELUM-ADA' }}" readonly
                            class="w-full pl-11 pr-4 py-3 bg-[#EBF5FF] border-2 border-dashed border-[#4A90E2] rounded-xl text-center font-mono font-bold text-base md:text-lg text-[#357ABD] tracking-widest cursor-pointer focus:outline-none hover:bg-blue-50 transition-colors">
                        <i class="ph ph-copy absolute left-4 top-1/2 -translate-y-1/2 text-[#4A90E2] text-xl"></i>

                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[#4A90E2] opacity-0 group-hover:opacity-100 transition-opacity hidden md:block">
                            Klik salin
                        </span>
                    </div>
                    <small class="text-[#27ae60] mt-2 block flex items-center gap-1">
                        <i class="ph-fill ph-check-circle"></i> Kode aktif dan dapat digunakan.
                    </small>
                </div>

                <hr class="border-gray-100 my-6 md:my-8">

                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-3 md:mb-4 flex items-center gap-2">
                    <i class="ph-fill ph-lock-key"></i> Keamanan Akun
                </h3>

                <div class="bg-gray-50 border border-gray-100 p-4 md:p-5 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Ubah Kata Sandi</p>
                        <p class="text-xs text-gray-500 mt-1">Kami sarankan untuk memperbarui kata sandi Anda secara berkala agar akun tetap aman.</p>
                    </div>

                   <a href="{{ route('profile.ubah-password') }}" class="whitespace-nowrap px-6 py-2.5 bg-white border border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all shadow-sm">
                        Ubah Password
                    </a>
                </div>

            </div>
        </div>
    </main>

    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileSidebar = document.getElementById('mobileSidebar');

        function openMobileMenu() {
            mobileOverlay.classList.remove('hidden');
            setTimeout(() => {
                mobileOverlay.classList.remove('opacity-0');
                mobileSidebar.classList.remove('-translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileOverlay.classList.add('opacity-0');
            mobileSidebar.classList.add('-translate-x-full');
            setTimeout(() => {
                mobileOverlay.classList.add('hidden');
            }, 300);
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', openMobileMenu);
    </script>

</body>
</html>