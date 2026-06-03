/**
 * OTP verification countdown timer
 * Displays countdown and shows resend button when expired
 */

document.addEventListener('DOMContentLoaded', () => {
    const countdownElem = document.getElementById('countdown');
    if (!countdownElem) return;

    // Get expired time from window variable (set by Blade)
    const expiredTime = window.otpExpiredTime * 1000;

    const timerContainer = document.getElementById('timerContainer');
    const resendContainer = document.getElementById('resendContainer');

    // Run countdown
    const interval = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        // Time expired
        if (distance < 0) {
            clearInterval(interval);
            if (timerContainer) timerContainer.classList.add('hidden');
            if (resendContainer) resendContainer.classList.remove('hidden');
            return;
        }

        // Calculate minutes and seconds
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display with leading zeros
        countdownElem.innerHTML =
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);

    }, 1000);

    // Form submit with loading state
    const otpForm = document.getElementById('otpForm');
    if (otpForm) {
        otpForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Memverifikasi...';
            }
        });
    }
});
