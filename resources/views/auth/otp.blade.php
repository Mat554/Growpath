<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Growpath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            expiredTime: {{ $expired_time ?? 0 }}
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth/otp.js'])
</head>
<body class="bg-[#f3f4f6] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[500px] rounded-[24px] shadow-[0_20px_40px_-5px_rgba(0,0,0,0.1)] p-10 flex flex-col justify-center relative overflow-hidden">

        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-[#EBF5FF] text-[#4A90E2] rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                <i class="ph-fill ph-envelope-open"></i>
            </div>
            <h2 class="text-2xl font-bold text-[#1f2937] mb-2 tracking-tight">Verifikasi Email</h2>
            <p class="text-[#6b7280] text-sm leading-relaxed">
                Kode dikirim ke <strong class="text-[#1f2937]">{{ $email }}</strong><br>
                Masukkan kode 6 digit di bawah ini.
            </p>
        </div>

        <form method="POST" action="{{ route('otp.check') }}" id="otpForm">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-xl border border-red-100 flex items-start gap-2">
                    <i class="ph-fill ph-warning-circle mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 text-green-600 text-sm rounded-xl border border-green-100 flex items-start gap-2">
                    <i class="ph-fill ph-check-circle mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-6">
                <div class="relative">
                    <input type="text" name="otp_code" placeholder="------" required maxlength="6" pattern="\d*"
                        class="w-full py-4 text-center text-2xl tracking-[10px] border border-[#e1e1e1] rounded-xl focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-[#333]">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-[#4A90E2] to-[#357ABD] hover:from-[#357ABD] hover:to-[#2b6399] text-white rounded-xl font-semibold text-[1rem] transition-all transform hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(74,144,226,0.3)]">
                Verifikasi & Masuk
            </button>
        </form>

        <div class="text-center mt-6">
            <p id="countdownDisplay" class="text-[#6b7280] text-sm mb-3"></p>
            <button type="button" id="resendBtn" class="text-[#4A90E2] font-semibold hover:text-[#357ABD] transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(3x)</span>
            </button>
        </div>

        <div class="mt-8 border-t pt-6 text-center">
            <a href="{{ route('login') }}" class="text-xs text-[#6b7280] hover:text-[#4A90E2] transition">
                <i class="ph ph-arrow-left mr-1"></i> Salah Email? Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
