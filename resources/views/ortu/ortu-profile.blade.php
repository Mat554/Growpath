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
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-auto cursor-pointer border-none bg-transparent text-left">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
                <i class="ph-fill ph-identification-badge text-[#4A90E2]"></i> Profil Orang Tua
            </h2>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 bg-[#E8F9F5] border border-[#2ECC71] text-[#27ae60] rounded-xl text-sm flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i> {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8 items-start">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="h-[120px] bg-gradient-to-br from-[#FF9F43] to-[#FFC107]"></div>
                
                <div class="text-center -mt-[60px] relative px-6">
                    <div class="w-[120px] h-[120px] bg-white rounded-full p-1.5 shadow-md inline-block relative group">
                        
                        <img src="{{ Auth::user()->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=FF9F43&color=fff&size=128' }}" 
                             alt="Avatar" class="w-full h-full rounded-full object-cover">
                        
                        <form action="{{ route('profile.ortu.update-avatar') }}" method="POST" enctype="multipart/form-data" class="absolute inset-1.5 rounded-full overflow-hidden bg-black/50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center cursor-pointer backdrop-blur-sm" onclick="document.getElementById('avatarUpload').click()">
                            @csrf
                            <i class="ph-fill ph-camera-plus text-white text-3xl mb-1"></i>
                            <span class="text-white text-[10px] font-medium">Ubah Foto</span>
                            
                            <input type="file" name="avatar" id="avatarUpload" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
                        </form>
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-[#4A90E2]"></i> Data Pribadi
                </h3>
                
                <form action="#" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap Wali</label>
                         <div class="relative">
                                <input type="text" 
                                    value="{{ Auth::user()->name }}" 
                                    readonly 
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none transition-all">
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

                <div class="mb-6">
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

                            <div class="flex justify-end mt-6">
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
                                <i class="ph-fill ph-clock text-base"></i> Menunggu Persetujuan
                            </div>
                        </div>

                        <small class="text-[#FF9F43] mt-2 block flex items-start gap-1">
                            <i class="ph-fill ph-info text-base mt-0.5"></i> 
                            Permintaan telah dikirim. Silakan minta {{ Auth::user()->child->name }} untuk menerima koneksi melalui notifikasi di dashboard-nya.
                        </small>

                        <div class="flex justify-end mt-6">
                            <form action="{{ route('koneksi.revoke.ortu') }}" method="POST" onsubmit="return confirm('Batalkan permintaan koneksi ini?')">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2">
                                    <i class="ph-bold ph-x"></i> Batalkan Permintaan
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
                            <i class="ph-fill ph-shield-check"></i> Akun berhasil terhubung dengan {{ Auth::user()->child->name }} (Kelas {{ Auth::user()->child->kelas }}).
                        </small>

                        <div class="flex justify-end mt-6">
                            <button type="button" onclick="openRevokeModal()" class="px-5 py-2.5 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl font-semibold text-sm transition-all cursor-pointer flex items-center gap-2">
                                <i class="ph-bold ph-plugs"></i> Lepas Koneksi
                            </button>
                        </div>
                    @endif
                </div>

                <hr class="border-gray-100 my-8">
                
                <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-lock-key"></i> Keamanan Akun
                </h3>

                <div class="bg-gray-50 border border-gray-100 p-5 rounded-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Ubah Kata Sandi</p>
                        <p class="text-xs text-gray-500 mt-1">Kami sarankan untuk memperbarui kata sandi Anda secara berkala agar akun tetap aman.</p>
                    </div>
                    
                    <a href="{{ route('profile.ubah-password') }}" class="whitespace-nowrap px-6 py-2.5 bg-white border border-[#4A90E2] text-[#4A90E2] hover:bg-[#F0F7FF] rounded-xl font-semibold text-sm transition-all shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-key"></i> Ubah Password
                    </a>
                </div>

            </div>
        </div>
    </main>

    @if(Auth::user()->child)
    <div id="revokeModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity cursor-pointer" onclick="closeRevokeModal()"></div>
        
        <div class="bg-white rounded-2xl shadow-xl w-[90%] max-w-md relative z-10 transform scale-95 opacity-0 transition-all duration-200" id="revokeModalContent">
            <div class="p-6">
                <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="ph-fill ph-warning-circle"></i>
                </div>
                
                <h3 class="text-xl font-bold text-center text-gray-800 mb-2">Konfirmasi Lepas Koneksi</h3>
                <p class="text-center text-sm text-gray-500 mb-6">
                    Apakah Anda yakin ingin memutuskan hubungan dengan akun siswa di bawah ini? Anda tidak akan bisa memantau laporan tesnya lagi.
                </p>

                <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 flex items-center gap-4 mb-8">
                    <img src="{{ Auth::user()->child->avatar ? 'https://ivmjjoplrdblxwhjzpcb.supabase.co/storage/v1/object/public/avatars/' . Auth::user()->child->avatar : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->child->name).'&background=4A90E2&color=fff' }}" 
                         alt="Siswa" class="w-14 h-14 rounded-full object-cover border-2 border-white shadow-sm">
                    
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm">{{ Auth::user()->child->name }}</h4>
                        <div class="text-xs text-gray-500 mt-1 flex flex-col gap-0.5">
                            <span class="flex items-center gap-1"><i class="ph-fill ph-identification-card"></i> ID: {{ Auth::user()->child->user_code }}</span>
                            <span class="flex items-center gap-1"><i class="ph-fill ph-student"></i> Kelas: {{ Auth::user()->child->kelas ?? 'Belum diatur' }}</span>
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
                            <i class="ph-bold ph-trash"></i> Ya, Lepas Koneksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</body>

<script>
    function openRevokeModal() {
        const modal = document.getElementById('revokeModal');
        const content = document.getElementById('revokeModalContent');
        
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRevokeModal() {
        const modal = document.getElementById('revokeModal');
        const content = document.getElementById('revokeModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
</html>