<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Growpath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f4f6] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[500px] rounded-[24px] shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] p-10 flex flex-col justify-center relative overflow-hidden">
        
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-[#E8F9F5] text-[#2ECC71] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="ph-fill ph-shield-check"></i>
            </div>
            <h2 class="text-2xl font-bold text-[#1f2937] mb-2 tracking-tight">Buat Password Baru</h2>
            <p class="text-[#6b7280] text-sm leading-relaxed">
                OTP berhasil diverifikasi! Silakan buat password baru untuk akun Anda.
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="newPasswordForm">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-xl border border-red-100 flex items-start gap-2">
                    <i class="ph-fill ph-warning-circle mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="mb-5">
                <label class="block text-[0.85rem] font-semibold text-[#1f2937] mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" placeholder="Min 8 karakter, 1 angka & huruf besar" required minlength="8"
                        class="w-full pl-12 pr-10 py-3.5 border border-[#e5e7eb] rounded-xl text-[0.95rem] focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15 transition-all text-[#1f2937] bg-[#f9fafb] focus:bg-white peer">
                    <i class="ph ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-[#9ca3af] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                    
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9ca3af] hover:text-[#6b7280] focus:outline-none cursor-pointer">
                        <i class="ph ph-eye text-xl" id="eyeIcon1"></i>
                    </button>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-[0.85rem] font-semibold text-[#1f2937] mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru" required minlength="8"
                        class="w-full pl-12 pr-10 py-3.5 border border-[#e5e7eb] rounded-xl text-[0.95rem] focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15 transition-all text-[#1f2937] bg-[#f9fafb] focus:bg-white peer">
                    <i class="ph ph-lock-key absolute left-4 top-1/2 -translate-y-1/2 text-[#9ca3af] text-xl peer-focus:text-[#4A90E2] transition-colors"></i>
                    
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#9ca3af] hover:text-[#6b7280] focus:outline-none cursor-pointer">
                        <i class="ph ph-eye text-xl" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

           <button type="submit" id="submitBtn" class="w-full py-3.5 bg-gradient-to-r from-[#4A90E2] to-[#357ABD] hover:from-[#357ABD] hover:to-[#2b6399] text-white rounded-xl font-semibold text-[1rem] transition-all transform hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(74,144,226,0.3)]">
                Update Password
            </button>
        </form>

    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('ph-eye', 'ph-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('ph-eye-slash', 'ph-eye');
            }
        }

        document.getElementById("newPasswordForm").addEventListener("submit", function() {
            const submitBtn = document.getElementById("submitBtn");
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin mr-2"></i> Menyimpan...';
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>