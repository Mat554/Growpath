<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8F9FD] min-h-screen flex justify-center items-center p-5 font-sans">

    <div class="bg-white w-full max-w-[500px] p-8 rounded-3xl shadow-lg border border-gray-100 text-center">
        
        <h2 class="text-2xl font-bold text-[#333] mb-2">Verifikasi Email</h2>
        <p class="text-[#888] text-sm mb-6">
            Kode dikirim ke <strong>{{ $email }}</strong><br>
            Masukkan kode 6 digit di bawah ini.
        </p>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-600 text-sm rounded-lg border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-500 text-sm rounded-lg border border-red-100">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.check') }}">
            @csrf
            
            <div class="mb-6">
                <input type="text" name="otp_code" placeholder="------" maxlength="6"
                    class="w-full py-4 text-center text-2xl tracking-[10px] border border-[#e1e1e1] rounded-xl focus:outline-none focus:border-[#4A90E2] focus:ring-4 focus:ring-[#4A90E2]/10 transition-all text-[#333]">
            </div>

            <button type="submit" class="w-full py-3.5 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-bold transition-all shadow-md">
                Verifikasi & Masuk
            </button>
        </form>

        <div class="mt-6 text-sm">
            
            <div id="timerContainer" class="text-[#888]">
                Kirim ulang kode dalam <span id="countdown" class="font-bold text-[#4A90E2]">05:00</span>
            </div>

            <form id="resendContainer" method="POST" action="{{ route('otp.resend') }}" class="hidden">
                @csrf
                <p class="text-[#888] mb-2">Tidak menerima kode?</p>
                <button type="submit" class="text-[#4A90E2] font-semibold hover:underline cursor-pointer">
                    Kirim Ulang Kode
                </button>
            </form>

        </div>

        <div class="mt-8 border-t pt-4">
            <a href="{{ route('login') }}" class="text-xs text-[#888] hover:text-[#4A90E2]">Salah Email? Kembali ke Login</a>
        </div>
    </div>

    <script>
        // Ambil waktu expired dari Controller (dikali 1000 karena JS pakai milidetik)
        // {{ $expired_time }} adalah timestamp dari PHP
        const expiredTime = {{ $expired_time }} * 1000; 

        const timerContainer = document.getElementById('timerContainer');
        const resendContainer = document.getElementById('resendContainer');
        const countdownElem = document.getElementById('countdown');

        // Update hitungan mundur setiap 1 detik
        const x = setInterval(function() {

            // Waktu sekarang
            const now = new Date().getTime();

            // Selisih waktu expired dengan sekarang
            const distance = expiredTime - now;

            // Hitung menit dan detik
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Tampilkan di layar (tambah angka 0 jika di bawah 10)
            countdownElem.innerHTML = 
                (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                (seconds < 10 ? "0" + seconds : seconds);

            // JIKA WAKTU HABIS
            if (distance < 0) {
                clearInterval(x);
                // Sembunyikan Timer
                timerContainer.classList.add('hidden');
                // Munculkan Tombol Resend
                resendContainer.classList.remove('hidden');
            }
        }, 1000);
    </script>
</body>
</html>