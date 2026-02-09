<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SPK</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FD] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[1000px] flex rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.08)] overflow-hidden min-h-[600px] flex-col md:flex-row">
        
        <div class="hidden md:flex md:w-[50%] bg-gradient-to-br from-[#4A90E2] to-[#56CCF2] p-12 flex-col justify-center text-white relative overflow-hidden">
            <div class="absolute w-[300px] h-[300px] rounded-full bg-white/10 -top-[100px] -right-[50px]"></div>
            <div class="absolute w-[200px] h-[200px] rounded-full bg-white/10 -bottom-[50px] -left-[50px]"></div>

            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-5 leading-tight">Selamat Datang<br>Kembali!</h1>
                <p class="text-base opacity-90 leading-relaxed">Silakan masuk untuk melanjutkan akses ke Platform SPK.</p>
            </div>
        </div>

        <div class="w-full md:w-[50%] p-10 flex flex-col justify-center bg-white">
            
            <div class="mb-8">
                <h2 class="text-3xl font-semibold text-[#333] mb-2">Masuk Akun</h2>
                <p class="text-[#888] text-sm">Pilih peran Anda untuk masuk.</p>
            </div>

            <div class="flex bg-[#F0F2F5] p-1.5 rounded-xl mb-6 select-none">
                <button type="button" onclick="switchRole('siswa')" id="tabSiswa" 
                    class="flex-1 py-3 text-sm font-semibold rounded-lg transition-all duration-300 bg-white text-[#4A90E2] shadow-sm cursor-pointer">
                    Siswa
                </button>
                <button type="button" onclick="switchRole('ortu')" id="tabOrtu" 
                    class="flex-1 py-3 text-sm font-semibold rounded-lg transition-all duration-300 text-[#888] hover:text-[#4A90E2] cursor-pointer">
                    Orang Tua
                </button>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <input type="hidden" name="role" id="roleInput" value="siswa">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-lg border border-red-100 flex items-start gap-2">
                        <i class="ph-fill ph-warning-circle mt-0.5"></i>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5">
                    <label class="block text-sm font-medium text-[#333] mb-2">Alamat Email</label>
                    <div class="relative">
                        <input type="email" name="email" placeholder="Masukkan Email" required 
                            class="w-full pl-12 pr-4 py-3.5 border border-[#e1e1e1] rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-[#333] bg-[#FCFCFC] focus:bg-white peer"
                            value="{{ old('email') }}">
                        <i class="ph ph-envelope-simple absolute left-4 top-1/2 -translate-y-1/2 text-[#aaa] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-medium text-[#333] mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Masukkan Password" required 
                            class="w-full pl-12 pr-10 py-3.5 border border-[#e1e1e1] rounded-xl text-sm focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-[#333] bg-[#FCFCFC] focus:bg-white peer">
                        <i class="ph ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-[#aaa] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                        
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#aaa] hover:text-[#666] focus:outline-none cursor-pointer">
                            <i class="ph ph-eye text-xl" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-end mb-8">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-[#4A90E2] hover:underline">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" id="btnSubmit" class="w-full py-4 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold text-base transition-all transform hover:-translate-y-0.5 shadow-[0_10px_20px_rgba(74,144,226,0.2)]">
                    Masuk Sebagai Siswa
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-[#888]">
                Belum punya akun? <a href="{{ route('register') }}" class="text-[#4A90E2] font-semibold hover:underline">Daftar di sini</a>
            </div>
        </div>
    </div>

    <script>
        // 1. Toggle Password Visibility
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

        // 2. Switch Role Logic (Siswa <-> Ortu)
        function switchRole(role) {
            const tabSiswa = document.getElementById('tabSiswa');
            const tabOrtu = document.getElementById('tabOrtu');
            const roleInput = document.getElementById('roleInput');
            const btnSubmit = document.getElementById('btnSubmit');

            // Set hidden input value agar dikirim ke Controller
            roleInput.value = role;

            if (role === 'siswa') {
                // UI Tab Siswa Active
                tabSiswa.classList.add('bg-white', 'text-[#4A90E2]', 'shadow-sm');
                tabSiswa.classList.remove('text-[#888]');
                
                // UI Tab Ortu Inactive
                tabOrtu.classList.remove('bg-white', 'text-[#4A90E2]', 'shadow-sm');
                tabOrtu.classList.add('text-[#888]');

                // Ubah Text Tombol
                btnSubmit.innerText = "Masuk Sebagai Siswa";
            } else {
                // UI Tab Ortu Active
                tabOrtu.classList.add('bg-white', 'text-[#4A90E2]', 'shadow-sm');
                tabOrtu.classList.remove('text-[#888]');

                // UI Tab Siswa Inactive
                tabSiswa.classList.remove('bg-white', 'text-[#4A90E2]', 'shadow-sm');
                tabSiswa.classList.add('text-[#888]');

                // Ubah Text Tombol
                btnSubmit.innerText = "Masuk Sebagai Orang Tua";
            }
        }
    </script>
</body>
</html>