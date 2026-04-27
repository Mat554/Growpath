<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Growpath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f3f4f6] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[500px] rounded-[24px] shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] p-10 flex flex-col justify-center relative overflow-hidden">
        
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-[#EBF5FF] text-[#4A90E2] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="ph-fill ph-envelope-open"></i>
            </div>
            <h2 class="text-2xl font-bold text-[#1f2937] mb-2 tracking-tight">Cek Email Anda</h2>
            <p class="text-[#6b7280] text-sm leading-relaxed">
                Kami telah mengirimkan 6 digit kode OTP ke email <br>
                <strong class="text-[#1f2937]">{{ $email ?? 'email Anda' }}</strong>
            </p>
        </div>

        <form method="POST" action="{{ route('password.otp.verify') }}" id="otpForm">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-xl border border-red-100 flex items-start gap-2">
                    <i class="ph-fill ph-warning-circle mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="mb-6">
                <label class="block text-[0.85rem] font-semibold text-[#1f2937] mb-2 text-center">Masukkan Kode OTP</label>
                <div class="relative">
                    <input type="text" name="otp_code" placeholder="Contoh: 123456" required maxlength="6" pattern="\d*"
                        class="w-full text-center py-3.5 border border-[#e5e7eb] rounded-xl text-xl font-bold tracking-[0.5em] focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/15 transition-all text-[#357ABD] bg-[#f9fafb] focus:bg-white">
                </div>
            </div>

            <button type="submit" id="submitBtn" class="w-full py-3.5 bg-gradient-to-r from-[#4A90E2] to-[#357ABD] hover:from-[#357ABD] hover:to-[#2b6399] text-white rounded-xl font-semibold text-[1rem] transition-all transform hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(74,144,226,0.3)]">
                Verifikasi OTP
            </button>
        </form>

        <div class="text-center mt-6 text-[0.9rem]">
            <a href="{{ route('password.request') }}" class="text-[#6b7280] font-medium hover:text-[#4A90E2] transition">
                Salah email atau OTP kadaluarsa? <span class="underline">Minta ulang</span>
            </a>
        </div>

    </div>

    <script>
        document.getElementById("otpForm").addEventListener("submit", function() {
            const submitBtn = document.getElementById("submitBtn");
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin mr-2"></i> Memverifikasi...';
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    </script>
</body>
</html>