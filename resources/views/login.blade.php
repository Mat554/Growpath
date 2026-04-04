<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f4f6] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[960px] flex rounded-[24px] shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] overflow-hidden min-h-[600px] flex-col md:flex-row">
        
        <div class="hidden md:flex md:w-[55%] bg-gradient-to-br from-[#1E3A8A] to-[#4A90E2] p-12 flex-col justify-center text-white relative overflow-hidden">
            <div class="absolute w-[350px] h-[350px] rounded-full bg-white/10 -top-[100px] -right-[100px] backdrop-blur-sm"></div>
            <div class="absolute w-[200px] h-[200px] rounded-full bg-white/10 bottom-[50px] -left-[80px] backdrop-blur-sm"></div>

            <div class="relative z-10 text-center">
              <img src="/asset/logo.png" alt="Logo Growpath" class="max-w-[280px] h-auto mx-auto mb-6 object-contain">
                
                <p class="text-[1.05rem] text-gray-200 font-light leading-relaxed">
                    Platform SPK berbasis data untuk membantu Siswa dan Orang Tua menentukan jurusan kuliah yang tepat dan terarah.
                </p>
            </div>
        </div>

        <div class="w-full md:w-[45%] p-10 flex flex-col justify-center bg-white">
            
            <div class="mb-8 text-center md:text-left">
               
                <h2 class="text-3xl font-bold text-[#1f2937] mb-2 tracking-tight">Selamat Datang</h2>
                <p class="text-[#6b7280] text-sm">Silakan pilih peran untuk masuk.</p>
            </div>

            <div class="flex bg-[#f3f4f6] p-1.5 rounded-2xl mb-8 select-none gap-1">
                <button type="button" onclick="switchRole('siswa')" id="tabSiswa" 
                    class="flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 bg-white text-[#4A90E2] shadow-sm cursor-pointer">
                    Siswa
                </button>
                <button type="button" onclick="switchRole('ortu')" id="tabOrtu" 
                    class="flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 text-[#6b7280] hover:text-[#4A90E2] cursor-pointer">
                    Orang Tua
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <input type="hidden" name="role" id="roleInput" value="siswa">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-xl border border-red-100 flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle mt-0.5"></i>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label class="block text-[0.85rem] font-semibold text-[#1f2937] mb-2">Alamat Email</label>
                    <div class="relative">
                        <input type="email" name="email" id="loginInput" placeholder="Masukkan email Anda" required 
                            class="w-full pl-12 pr-4 py-3.5 border border-[#e5e7eb] rounded-xl text-[0.95rem] focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15 transition-all text-[#1f2937] bg-[#f9fafb] focus:bg-white peer"
                            value="{{ old('email') }}">
                        <i class="ph ph-user absolute left-4 top-1/2 -translate-y-1/2 text-[#9ca3af] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[0.85rem] font-semibold text-[#1f2937] mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required 
                            class="w-full pl-12 pr-10 py-3.5 border border-[#e5e7eb] rounded-xl text-[0.95rem] focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15 transition-all text-[#1f2937] bg-[#f9fafb] focus:bg-white peer">
                        <i class="ph ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-[#9ca3af] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9ca3af] hover:text-[#6b7280] focus:outline-none cursor-pointer">
                            <i class="ph ph-eye text-xl" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end mb-6">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#4A90E2] font-medium hover:underline">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" id="submitBtn" class="w-full py-3.5 bg-gradient-to-r from-[#4A90E2] to-[#357ABD] hover:from-[#357ABD] hover:to-[#2b6399] text-white rounded-xl font-semibold text-[1rem] transition-all transform hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(74,144,226,0.3)]">
                    Masuk Sebagai Siswa
                </button>
            </form>

            <div class="text-center mt-6 text-[0.9rem] text-[#6b7280]">
                Belum punya akun? <a href="{{ route('register') }}" class="text-[#4A90E2] font-semibold hover:underline transition">Daftar sekarang</a>
            </div>
        </div>
    </div>

    <script>
        // 1. Toggle Password Visibility (Dari Main)
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('ph-eye', 'ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('ph-eye-slash', 'ph-eye');
            }
        }

        // 2. Switch Role Logic (Gabungan UI Branch & Logic Main)
        function switchRole(role) {
            const tabSiswa = document.getElementById('tabSiswa');
            const tabOrtu = document.getElementById('tabOrtu');
            const roleInput = document.getElementById('roleInput');
            const submitBtn = document.getElementById('submitBtn');

            roleInput.value = role;

            if (role === 'siswa') {
                tabSiswa.className = "flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 bg-white text-[#4A90E2] shadow-sm cursor-pointer";
                tabOrtu.className = "flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 text-[#6b7280] hover:text-[#4A90E2] cursor-pointer";
                submitBtn.innerText = "Masuk Sebagai Siswa";
            } else {
                tabOrtu.className = "flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 bg-white text-[#4A90E2] shadow-sm cursor-pointer";
                tabSiswa.className = "flex-1 py-3 text-sm font-semibold rounded-xl transition-all duration-300 text-[#6b7280] hover:text-[#4A90E2] cursor-pointer";
                submitBtn.innerText = "Masuk Sebagai Orang Tua";
            }
        }

        // 3. Loading State UX (Dari Branch)
        document.getElementById("loginForm").addEventListener("submit", function() {
            const submitBtn = document.getElementById("submitBtn");
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin mr-2"></i> Memproses...';
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>