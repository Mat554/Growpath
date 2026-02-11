<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20">
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
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all">
                <i class="ph ph-clipboard-text text-lg"></i> Kuesioner
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">Profil Siswa</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8 items-start">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-[120px] bg-gradient-to-br from-[#4A90E2] to-[#56CCF2]"></div>
                
                <div class="text-center -mt-[60px] relative px-6">
                    <div class="w-[120px] h-[120px] bg-white rounded-full p-1.5 shadow-md inline-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=4A90E2&color=fff&size=128" 
                             alt="Avatar" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>

                <div class="text-center px-6 pb-8 pt-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h3>
                    <span class="inline-block px-3 py-1 bg-[#EBF5FF] text-[#4A90E2] rounded-full text-xs font-bold uppercase tracking-wide mb-6">
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

                    <div class="bg-[#E8F9F5] border border-dashed border-[#2ECC71] p-4 rounded-xl flex items-center gap-4 text-left mt-4">
                        <i class="ph-fill ph-shield-check text-2xl text-[#2ECC71]"></i>
                        <div>
                            <span class="text-xs text-gray-500 block">Status Akun</span>
                            <strong class="text-[#27ae60] text-sm">Akun Terverifikasi</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[#4A90E2]"></i> Data Pribadi
                </h3>
                
                <form action="#" method="POST"> @csrf 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <input type="text" value="{{ Auth::user()->name }}" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-gray-800">
                                <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
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

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                            <i class="ph ph-envelope-simple absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                        </div>
                    </div>
                </form>

                <hr class="border-gray-100 my-8">

                <h3 class="text-lg font-semibold text-[#4A90E2] mb-2 flex items-center gap-2">
                    <i class="ph-fill ph-qr-code"></i> Kode Sambung Orang Tua
                </h3>
                <p class="text-sm text-gray-500 mb-5">
                    Berikan <strong>User ID</strong> ini kepada Orang Tua Anda untuk menghubungkan akun.
                </p>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User ID Anda</label>
                    <div class="relative group cursor-copy" onclick="copyToClipboard('{{ Auth::user()->user_code }}')">
                        <input type="text" value="{{ Auth::user()->user_code ?? 'BELUM-ADA' }}" readonly 
                            class="w-full pl-11 pr-4 py-3 bg-[#EBF5FF] border-2 border-dashed border-[#4A90E2] rounded-xl text-center font-mono font-bold text-lg text-[#357ABD] tracking-widest cursor-pointer focus:outline-none hover:bg-blue-50 transition-colors">
                        <i class="ph ph-copy absolute left-4 top-1/2 -translate-y-1/2 text-[#4A90E2] text-xl"></i>
                        
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-[#4A90E2] opacity-0 group-hover:opacity-100 transition-opacity">
                            Klik salin
                        </span>
                    </div>
                    <small class="text-[#27ae60] mt-2 block flex items-center gap-1">
                        <i class="ph-fill ph-check-circle"></i> Kode aktif dan dapat digunakan.
                    </small>
                </div>

                <hr class="border-gray-100 my-8">

                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-lock-key"></i> Keamanan Akun
                </h3>
                
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="password" placeholder="Min. 6 karakter" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-gray-800">
                                <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" placeholder="Ulangi password" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-gray-800">
                                <i class="ph ph-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="px-6 py-3 bg-[#2c3e50] hover:bg-[#1a252f] text-white rounded-xl font-semibold text-sm transition-all shadow-lg hover:shadow-xl">
                            Update Password
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert("Kode " + text + " berhasil disalin!");
        }
    </script>
</body>
</html>