<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SPK Minat Bakat</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[900px] min-h-[600px] flex rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] overflow-hidden flex-col md:flex-row">
        
        <div class="hidden md:flex w-[55%] relative flex-col justify-center p-12 text-white bg-gradient-to-br from-[#1e3c72] to-[#2a5298] overflow-hidden">
            
            <div class="absolute w-[250px] h-[250px] rounded-full bg-white/5 -top-[80px] -right-[80px]"></div>
            <div class="absolute w-[350px] h-[350px] rounded-full bg-white/5 -bottom-[120px] -left-[80px]"></div>

            <div class="relative z-10">
                <div class="inline-block bg-white/20 px-4 py-1.5 rounded-full text-xs font-medium tracking-wider mb-5">
                    ADMINISTRATOR
                </div>

                <h1 class="text-[2.2rem] font-semibold mb-4 leading-[1.2]">Panel Kontrol<br>Sistem SPK.</h1>
                <p class="text-[0.95rem] font-light opacity-80 leading-relaxed">
                    Kelola data siswa, kriteria penilaian, dan hasil rekomendasi jurusan dalam satu pintu.
                </p>
            </div>
        </div>

        <div class="w-full md:w-[45%] flex items-center justify-center p-10 bg-white">
            <div class="w-full max-w-[320px]">
                
                <div class="text-center mb-9">
                    <h2 class="text-[#333] text-[1.8rem] font-semibold mb-1">Admin Portal</h2>
                    <p class="text-[#666] text-sm">Masukkan kredensial admin Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-5 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100 flex items-center gap-2">
                        <i class="ph-fill ph-warning-circle text-lg"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}"> 
                    @csrf
                    
                    <input type="hidden" name="role" value="admin"> 

                    <div class="mb-5">
                        <label for="email" class="block text-[#333] text-sm font-medium mb-2">Email / Username</label>
                        <div class="relative group">
                            <input type="email" name="email" id="email" placeholder="nama@admin.com" required value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-3 border-[1.5px] border-[#eee] rounded-[10px] text-[0.95rem] focus:outline-none focus:border-[#1e3c72] focus:ring-4 focus:ring-[#1e3c72]/10 transition-all text-[#333] placeholder-gray-400 group-hover:border-gray-300">
                            <i class="ph ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-[#aaa] text-xl transition-colors group-focus-within:text-[#1e3c72]"></i>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-[#333] text-sm font-medium mb-2">Password</label>
                        <div class="relative group">
                            <input type="password" name="password" id="password" placeholder="Masukkan Password" required
                                class="w-full pl-12 pr-10 py-3 border-[1.5px] border-[#eee] rounded-[10px] text-[0.95rem] focus:outline-none focus:border-[#1e3c72] focus:ring-4 focus:ring-[#1e3c72]/10 transition-all text-[#333] placeholder-gray-400 group-hover:border-gray-300">
                            <i class="ph ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-[#aaa] text-xl transition-colors group-focus-within:text-[#1e3c72]"></i>
                            
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#aaa] hover:text-[#666] focus:outline-none cursor-pointer">
                                <i class="ph ph-eye text-xl" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-[#1e3c72] hover:bg-[#2a5298] text-white rounded-[10px] font-semibold text-base transition-all transform hover:-translate-y-0.5 shadow-lg shadow-[#1e3c72]/30 active:translate-y-0">
                        Login ke Dashboard
                    </button>
                </form>

                <div class="text-center mt-8 text-sm text-[#666]">
                    Bukan Admin? <a href="{{ route('login') }}" class="text-[#1e3c72] font-semibold hover:underline decoration-2">Masuk sebagai Siswa/Ortu</a>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>