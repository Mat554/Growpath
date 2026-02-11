<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Orang Tua - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden text-[#333]">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-100 p-6 hidden md:flex transition-all z-20">
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
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800">Profil Orang Tua</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8 items-start">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-[120px] bg-gradient-to-br from-[#FF9F43] to-[#FFC107]"></div>
                
                <div class="text-center -mt-[60px] relative px-6">
                    <div class="w-[120px] h-[120px] bg-white rounded-full p-1.5 shadow-md inline-block">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF9F43&color=fff&size=128" 
                             alt="Avatar" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>

                <div class="text-center px-6 pb-8 pt-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ Auth::user()->name }}</h3>
                    <span class="inline-block px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase tracking-wide mb-6">
                        WALI MURID
                    </span>

                    <div class="bg-[#E8F9F5] border border-dashed border-[#2ECC71] p-4 rounded-xl flex items-center gap-4 text-left">
                        <i class="ph-fill ph-link-simple text-2xl text-[#2ECC71]"></i>
                        <div>
                            <span class="text-xs text-gray-500 block">Akun Terhubung:</span>
                            @if(Auth::user()->child) 
                                <strong class="text-[#27ae60] text-sm">{{ Auth::user()->child->name }}</strong>
                                <span class="text-[10px] text-gray-400 block tracking-wider">ID: {{ Auth::user()->child->user_code }}</span>
                            @else
                                <strong class="text-gray-400 text-sm">Belum Terhubung</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[#4A90E2]"></i> Data Pribadi
                </h3>
                
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Wali</label>
                        <div class="relative">
                            <input type="text" value="{{ Auth::user()->name }}" class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-gray-800">
                            <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
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
                    <i class="ph-fill ph-plugs-connected"></i> Hubungkan Akun Anak
                </h3>
                <p class="text-sm text-gray-500 mb-5">
                    Masukkan <strong>User ID</strong> akun siswa anak Anda untuk melihat hasil tes dan laporan perkembangan.
                </p>

                <form id="connectForm">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">User ID Anak (Siswa)</label>
                        
                        <div class="relative group">
                            <input type="text" 
                                value="{{ Auth::user()->child ? Auth::user()->child->user_code : '' }}" 
                                placeholder="Masukkan User ID (Contoh: SISWA-1234)" 
                                {{ Auth::user()->child ? 'readonly' : '' }}
                                class="w-full pl-11 pr-4 py-3 border rounded-xl text-sm focus:outline-none transition-all
                                {{ Auth::user()->child 
                                    ? 'border-[#2ECC71] bg-[#E8F9F5] text-[#27ae60] font-semibold cursor-default' 
                                    : 'border-gray-200 focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 text-gray-800' 
                                }}">
                            
                            <i class="ph ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-lg {{ Auth::user()->child ? 'text-[#2ECC71]' : 'text-gray-400' }}"></i>

                            @if(Auth::user()->child)
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 text-xs font-bold text-[#2ECC71]">
                                <i class="ph-fill ph-check-circle text-base"></i> Terverifikasi
                            </div>
                            @endif
                        </div>

                        @if(Auth::user()->child)
                            <small class="text-[#27ae60] mt-2 block flex items-center gap-1">
                                ✅ Akun berhasil terhubung dengan {{ Auth::user()->child->name }} (Kelas {{ Auth::user()->child->kelas }}).
                            </small>
                        @else
                            <small class="text-gray-400 mt-2 block">
                                *Pastikan ID yang dimasukkan benar.
                            </small>
                        @endif
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        @if(Auth::user()->child)
                            <button type="button" onclick="alert('Untuk keamanan, pemutusan hubungan akun hanya dapat dilakukan oleh Admin Sekolah.')" class="px-5 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl font-semibold text-sm transition-all">
                                Lepas Koneksi
                            </button>
                            <button type="button" disabled class="px-5 py-2.5 bg-gray-200 text-gray-400 rounded-xl font-semibold text-sm cursor-not-allowed">
                                Simpan Koneksi
                            </button>
                        @else
                            <button type="button" class="px-6 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-sm transition-all shadow-lg shadow-[#4A90E2]/30">
                                Hubungkan Akun
                            </button>
                        @endif
                    </div>
                </form>

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

</body>
</html>