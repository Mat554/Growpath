<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Profil Saya - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/mobile/parent/profile.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        html, body { overflow-x: hidden; overscroll-behavior: none; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-[#F4F7F6] font-sans min-h-screen pb-24">

    <!-- MOBILE HEADER with Back Button -->
    <header class="bg-white px-4 py-3 sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.ortu') }}" class="w-9 h-9 bg-gray-50 rounded-xl flex items-center justify-center hover:bg-gray-100 transition-colors">
                <i class="ph ph-arrow-left text-gray-600 text-lg"></i>
            </a>
            <h1 class="font-bold text-gray-800 text-base">Profil Saya</h1>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 py-4 animate-fade-in">

        @if(session('status'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs flex items-center gap-2">
                <i class="ph-fill ph-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        <!-- Avatar Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            <div class="h-[70px] bg-gradient-to-r from-[#FF9F43] to-[#FFC107]"></div>
            <div class="text-center -mt-10 px-4 pb-5">
                <form action="{{ route('profile.ortu.update-avatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <div class="w-[80px] h-[80px] bg-white rounded-full p-1 shadow-md relative mx-auto">
                        <img id="avatarPreview"
                             src="{{ Auth::user()->avatar
                                 ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->avatar
                                 : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=FF9F43&color=fff&size=128' }}"
                             alt="Avatar" class="w-full h-full rounded-full object-cover">
                        <label for="avatarUpload" class="absolute inset-0 rounded-full bg-black/50 opacity-0 hover:opacity-100 transition-opacity flex flex-col items-center justify-center cursor-pointer">
                            <i class="ph-fill ph-camera-plus text-white text-lg"></i>
                        </label>
                        <input type="file" name="avatar" id="avatarUpload" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                    </div>
                    <div id="saveButtonContainer" class="hidden mt-3 flex-col items-center gap-2">
                        <button type="submit" class="px-4 py-1.5 bg-[#2ECC71] text-white rounded-full text-xs font-semibold">Simpan</button>
                        <button type="button" onclick="cancelUpload()" class="text-xs text-red-500">Batal</button>
                    </div>
                </form>

                <h3 class="text-base font-bold text-gray-800 mt-2">{{ Auth::user()->name }}</h3>
                <span class="inline-block px-3 py-0.5 bg-orange-100 text-orange-600 rounded-full text-[10px] font-bold uppercase">Wali Murid</span>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
            <!-- Connection Status -->
            <div class="p-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center
                    @if(Auth::user()->child && Auth::user()->child_connection_status == 'approved')
                        bg-green-100 text-green-600
                    @elseif(Auth::user()->child && Auth::user()->child_connection_status == 'pending')
                        bg-orange-100 text-orange-600
                    @else
                        bg-gray-100 text-gray-400
                    @endif">
                    @if(Auth::user()->child && Auth::user()->child_connection_status == 'approved')
                        <i class="ph-fill ph-check-circle text-sm"></i>
                    @elseif(Auth::user()->child && Auth::user()->child_connection_status == 'pending')
                        <i class="ph-fill ph-clock text-sm"></i>
                    @else
                        <i class="ph-fill ph-warning-circle text-sm"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-[10px] text-gray-500">Akun Terhubung</p>
                    @if(Auth::user()->child && Auth::user()->child_connection_status == 'approved')
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->child->name }}</p>
                    @elseif(Auth::user()->child && Auth::user()->child_connection_status == 'pending')
                        <p class="text-sm font-semibold text-orange-600">Menunggu...</p>
                    @else
                        <p class="text-sm font-semibold text-gray-400">Belum Terhubung</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Pribadi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <i class="ph-fill ph-user text-[#4A90E2]"></i> Data Diri
            </h3>
            <div class="space-y-2">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <i class="ph ph-user text-gray-400"></i>
                    <div>
                        <p class="text-[10px] text-gray-500">Nama</p>
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <i class="ph ph-envelope text-gray-400"></i>
                    <div>
                        <p class="text-[10px] text-gray-500">Email</p>
                        <p class="text-sm font-medium text-gray-800">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hubungkan Akun -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <i class="ph-fill ph-link-simple text-[#4A90E2]"></i> Hubungkan Akun Anak
            </h3>

            @if(session('success'))
                <div class="mb-3 p-2 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs flex items-center gap-2">
                    <i class="ph-fill ph-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-3 p-2 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs flex items-center gap-2">
                    <i class="ph-fill ph-warning-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if(!Auth::user()->child)
                <form action="{{ route('koneksi.connect.ortu') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <input type="text" name="child_code" placeholder="Masukkan User ID anak" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2]">
                    </div>
                    <p class="text-[10px] text-gray-400 mb-3">Contoh: SIS-10-12345</p>
                    <button type="submit" class="w-full py-3 bg-[#4A90E2] text-white rounded-xl font-semibold text-sm">
                        <i class="ph-bold ph-link mr-1"></i> Hubungkan
                    </button>
                </form>
            @elseif(Auth::user()->child_connection_status == 'pending')
                <div class="p-3 bg-orange-50 border border-orange-200 rounded-xl mb-3">
                    <p class="text-sm text-orange-700">
                        Menunggu persetujuan dari <strong>{{ Auth::user()->child->name }}</strong>
                    </p>
                </div>
                <form action="{{ route('koneksi.revoke.ortu') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 border border-red-300 text-red-600 rounded-xl text-xs font-semibold">
                        Batalkan
                    </button>
                </form>
            @elseif(Auth::user()->child_connection_status == 'approved')
                <div class="p-3 bg-green-50 border border-green-200 rounded-xl mb-3">
                    <p class="text-sm text-green-700 flex items-center gap-2">
                        <i class="ph-fill ph-check-circle"></i>
                        Terhubung dengan <strong>{{ Auth::user()->child->name }}</strong>
                    </p>
                </div>
                <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" onsubmit="return confirm('Lepas koneksi?')">
                    @csrf
                    <button type="submit" class="w-full py-2.5 border border-red-300 text-red-600 rounded-xl text-xs font-semibold">
                        Lepas Koneksi
                    </button>
                </form>
            @endif
        </div>

        <!-- Pengaturan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <i class="ph-fill ph-gear text-gray-500"></i> Pengaturan
            </h3>
            <a href="{{ route('profile.ubah-password') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    <i class="ph ph-lock-key text-gray-500"></i>
                    <span class="text-sm text-gray-700">Ubah Password</span>
                </div>
                <i class="ph ph-caret-right text-gray-400"></i>
            </a>
        </div>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3 bg-red-50 text-red-600 rounded-xl font-semibold text-sm flex items-center justify-center gap-2">
                <i class="ph ph-sign-out"></i> Keluar
            </button>
        </form>
    </main>

    <!-- MOBILE BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-2 flex justify-around z-50 safe-area-bottom">
        <a href="{{ route('dashboard.ortu') }}" class="flex flex-col items-center text-gray-400 py-1 px-4">
            <i class="ph ph-squares-four text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Home</span>
        </a>
        <a href="{{ route('profile.ortu') }}" class="flex flex-col items-center text-[#FF9F43] py-1 px-4">
            <i class="ph-fill ph-user text-xl"></i>
            <span class="text-[9px] font-medium mt-0.5">Profil</span>
        </a>
    </nav>

</body>
</html>