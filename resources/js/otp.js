// resources/js/otp.js

document.addEventListener('DOMContentLoaded', () => {
    // Pastikan script ini hanya berjalan jika ada elemen countdown (agar tidak error di halaman lain)
    const countdownElem = document.getElementById('countdown');
    if (!countdownElem) return;

    // 1. Ambil data expiredTime dari "Jembatan" window (dikali 1000 untuk milidetik)
    const expiredTime = window.otpExpiredTime * 1000; 

    const timerContainer = document.getElementById('timerContainer');
    const resendContainer = document.getElementById('resendContainer');

    // 2. Jalankan hitung mundur
    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        // JIKA WAKTU HABIS
        if (distance < 0) {
            clearInterval(x);
            timerContainer.classList.add('hidden');
            resendContainer.classList.remove('hidden');
            return; // Hentikan eksekusi kode di bawahnya
        }

        // Hitung menit dan detik
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Tampilkan di layar dengan format 00:00
        countdownElem.innerHTML = 
            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
            (seconds < 10 ? "0" + seconds : seconds);
            
    }, 1000);
});