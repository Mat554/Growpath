<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Orang Tua - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/parent/profile.js'])

    <style>
        .mobile-overlay { transition: opacity 0.3s ease; }
        .mobile-menu { transition: transform 0.3s ease; }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans text-[#333]">

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
            <a href="{{ url('/dashboard-ortu') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST" class="p-4 border-t border-gray-100">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all cursor-pointer border-none bg-transparent text-left">
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
            <a href="{{ url('/dashboard-ortu') }}" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            <a href="{{ route('profile.ortu') }}" class="flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all">
                <i class="ph ph-user text-lg"></i> Profil Saya
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer border-none bg-transparent text-left">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="md:ml-[260px] p-4 md:p-8 pb-24 md:pb-8 overflow-y-auto">
        <div class="mb-6 md:mb-8">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="ph-fill ph-identification-badge text-[#4A90E2]"></i> Profil Orang Tua
            </h2>
        </div>

        @if(session('status'))
            <div class="mb-4 md:mb-6 p-4 bg-[#E8F9F5] border border-[#2ECC71] text-[#27ae60] rounded-xl text-sm flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i> {{ session('status') }}
            </div>
        @endif

        <div class="flex flex-col gap-6 lg:gap-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-[80px] md:h-[120px] bg-gradient-to-br from-[#FF9F43] to-[#FFC107]"></div>

                <div class="text-center -mt-[50px] md:-mt-[60px] relative px-4 md:px-6">
                    <form action="{{ route('profile.ortu.update-avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm" class="flex flex-col items-center">
                        @csrf

                        <div class="w-[100px] h-[100px] md:w-[120px] md:h-[120px] bg-white rounded-full p-1.5 shadow-md relative group">

                            <img id="avatarPreview" src="{{ Auth::user()->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=FF9F43&color=fff&size=128' }}"
                                 alt="Avatar" class="w-full h-full rounded-full object-cover">

                            <label for="avatarUpload" class="absolute inset-1.5 rounded-full overflow-hidden bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer backdrop-blur-sm">
                                <i class="ph-fill ph-camera-plus text-white text-2xl md:text-3xl mb-1"></i>
                                <span class="text-white text-[9px] md:text-[10px] font-medium">Pilih Foto</span>
                            </label>

                            <input type="file" name="avatar" id="avatarUpload" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                        </div>

                        <div id="saveButtonContainer" class="hidden mt-4 flex-col items-center gap-2">
                            <button type="submit" class="px-5 py-2 bg-[#2ECC71] hover:bg-[#27ae60] text-white rounded-full font-semibold text-xs transition-all shadow-sm flex items-center gap-2">
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
                    <span class="inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase tracking-wide mb-4 md:mb-6">
                        WALI MURID
                    </span>

                    <div class="bg-[#E8F9F5] border border-dashed border-[#2ECC71] p-3 md:p-4 rounded-xl flex items-center gap-3 md:gap-4 text-left">
                        <i class="ph-fill ph-link-simple text-xl md:text-2xl text-[#2ECC71]"></i>
                        <div>
                            <span class="text-xs text-gray-500 block mb-0.5">Akun Terhubung:</span>
                            @if(Auth::user()->child && Auth::user()->child_connection_status == 'approved')
                                <strong class="text-[#27ae60] text-sm flex items-center gap-1"><i class="ph-fill ph-student"></i> {{ Auth::user()->child->name }}</strong>
                                <span class="text-[10px] text-gray-400 block tracking-wider mt-0.5">ID: {{ Auth::user()->child->user_code }}</span>
                            @elseif(Auth::user()->child && Auth::user()->child_connection_status == 'pending')
                                <strong class="text-[#FF9F43] text-sm flex items-center gap-1"><i class="ph-fill ph-clock"></i> Menunggu...</strong>
                                <span class="text-[10px] text-gray-400 block tracking-wider mt-0.5">{{ Auth::user()->child->name }}</span>
                            @else
                                <strong class="text-gray-400 text-sm flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> Belum Terhubung</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Data Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-6 lg:p-8">
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[#4A90E2]"></i> Data Pribadi
                </h3>

                <form action="#" method="POST">
                    @csrf
                    <div class="mb-4 md:mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Wali</label>
                         <div class="relative">
                                <input type="text"
                                    value="{{ Auth::user()->name }}"
                                    readonly
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none transition-all">
                                <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
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
                    <i class="ph-fill ph-plugs-connected"></i> Hubungkan Akun Anak
                </h3>
                <p class="text-sm text-gray-500 mb-4 md:mb-5">
                    Masukkan <strong>User ID</strong> akun siswa anak Anda untuk melihat hasil tes dan laporan perkembangan.
                </p>

                @if(session('success'))
                    <div class="mb-4 p-3 bg-[#E8F9F5] border border-[#2ECC71] text-[#27ae60] rounded-xl text-sm flex items-center gap-2">
                        <i class="ph-fill ph-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-500 rounded-xl text-sm flex items-center gap-2">
                        <i class="ph-fill ph-warning-circle"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 md:mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User ID Anak (Siswa)</label>

                    @if(!Auth::user()->child)
                        <form action="{{ route('koneksi.connect.ortu') }}" method="POST">
                            @csrf
                            <div class="relative group">
                                <input type="text"
                                    name="child_code"
                                    placeholder="Masukkan User ID (Contoh: SIS-10-12345)"
                                    required
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none transition-all focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 text-gray-800">
                                <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-lg text-gray-400"></i>
                            </div>
                            <small class="text-gray-400 mt-2 block">
                                *Pastikan ID yang dimasukkan sesuai dengan yang ada di profil siswa.
                            </small>

                            <div class="flex justify-end mt-4 md:mt-6">
                                <button type="submit" class="px-6 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30 cursor-pointer flex items-center gap-2">
                                    <i class="ph-bold ph-link"></i> Hubungkan Akun
                                </button>
                            </div>
                        </form>
                    @elseif(Auth::user()->child_connection_status == 'pending')
                        <div class="relative group">
                            <input type="text"
                                value="{{ Auth::user()->child->user_code }}"
                                readonly
                                class="w-full pl-11 pr-4 py-3 border border-[#FF9F43] rounded-xl text-sm bg-[#FFF4E5] text-[#FF9F43] font-semibold cursor-default focus:outline-none transition-all">

                            <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-lg text-[#FF9F43]"></i>

                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 text-xs font-bold text-[#FF9F43]">
                                <i class="ph-fill ph-clock text-base"></i> Menunggu
                            </div>
                        </div>

                        <small class="text-[#FF9F43] mt-2 block flex items-start gap-1">
                            <i class="ph-fill ph-info text-base mt-0.5"></i>
                            Permintaan telah dikirim. Silakan minta {{ Auth::user()->child->name }} untuk menerima koneksi.
                        </small>

                        <div class="flex justify-end mt-4 md:mt-6">
                            <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" onsubmit="return confirm('Batalkan permintaan koneksi ini?')">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2">
                                    <i class="ph-bold ph-x"></i> Batalkan
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::user()->child_connection_status == 'approved')
                        <div class="relative group">
                            <input type="text"
                                value="{{ Auth::user()->child->user_code }}"
                                readonly
                                class="w-full pl-11 pr-4 py-3 border border-[#2ECC71] rounded-xl text-sm bg-[#E8F9F5] text-[#27ae60] font-semibold cursor-default focus:outline-none transition-all">

                            <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-lg text-[#2ECC71]"></i>

                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 text-xs font-bold text-[#2ECC71]">
                                <i class="ph-fill ph-check-circle text-base"></i> Terverifikasi
                            </div>
                        </div>

                        <small class="text-[#27ae60] mt-2 block flex items-center gap-1">
                            <i class="ph-fill ph-shield-check"></i> Terhubung dengan {{ Auth::user()->child->name }}.
                        </small>

                        <div class="flex justify-end mt-4 md:mt-6">
                            <button type="button" onclick="openRevokeModal()" class="px-5 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2">
                                <i class="ph-bold ph-plugs"></i> Lepas Koneksi
                            </button>
                        </div>
                    @endif
                </div>

                <hr class="border-gray-100 my-6 md:my-8">

                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4 md:mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-lock-key"></i> Keamanan Akun
                </h3>

                <div class="bg-gray-50 border border-gray-100 p-4 md:p-5 rounded-xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Ubah Kata Sandi</p>
                        <p class="text-xs text-gray-500 mt-1">Kami sarankan untuk memperbarui kata sandi secara berkala.</p>
                    </div>

                    <a href="{{ route('profile.ubah-password') }}" class="whitespace-nowrap px-6 py-2.5 bg-white border border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-key"></i> Ubah Password
                    </a>
                </div>
            </div>
        </div>
    </main>

    @if(Auth::user()->child)
    <div id="revokeModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity cursor-pointer" onclick="closeRevokeModal()"></div>

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md relative z-10 transform scale-95 opacity-0 transition-all duration-200" id="revokeModalContent">
            <div class="p-6">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="ph-fill ph-warning-circle"></i>
                </div>

                <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Konfirmasi Lepas Koneksi</h3>
                <p class="text-center text-sm text-gray-500 mb-6">
                    Lepas hubungan dengan akun siswa di bawah ini?
                </p>

                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex items-center gap-4 mb-8">
                    <img src="{{ Auth::user()->child->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->child->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->child->name).'&background=4A90E2&color=fff' }}"
                         alt="Siswa" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm">

                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ Auth::user()->child->name }}</h4>
                        <div class="text-xs text-gray-500 mt-1 flex flex-col gap-0.5">
                            <span class="flex items-center gap-1"><i class="ph-fill ph-identification-card"></i> ID: {{ Auth::user()->child->user_code }}</span>
                            <span class="flex items-center gap-1"><i class="ph-fill ph-student"></i> Kelas: {{ Auth::user()->child->kelas ?? 'Belum' }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRevokeModal()" class="flex-1 py-3 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-xl font-semibold text-sm transition-all cursor-pointer">
                        Batal
                    </button>
                    <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-red-500/30 cursor-pointer flex items-center justify-center gap-2">
                            <i class="ph-bold ph-trash"></i> Ya, Lepas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
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

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', openMobileMenu);
    </script>

</body>

</html>