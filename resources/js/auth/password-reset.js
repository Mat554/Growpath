/**
 * Password reset OTP page JavaScript
 * Handles countdown timer and OTP resend functionality for password reset flow
 */

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('otpForm');
    const resendBtn = document.getElementById('resendBtn');
    const resendCountEl = document.getElementById('resendCount');
    const countdownDisplay = document.getElementById('countdownDisplay');
    const submitBtn = form?.querySelector('button[type="submit"]');
    const otpInput = form?.querySelector('input[name="otp_code"]');

    let countdownInterval = null;
    const MAX_RESENDS = 3;

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                      document.querySelector('input[name="_token"]')?.value || '';

    // Persist resend count in sessionStorage
    function getRemainingResends() {
        const stored = sessionStorage.getItem('otp_resend_count');
        return stored !== null ? parseInt(stored) : MAX_RESENDS;
    }

    function setRemainingResends(count) {
        sessionStorage.setItem('otp_resend_count', count.toString());
    }

    let remainingResends = getRemainingResends();

    // Update resend count display
    function updateResendCountDisplay() {
        if (resendCountEl) {
            resendCountEl.textContent = `(${remainingResends}x)`;
        }
    }

    // Initialize display on load
    updateResendCountDisplay();

    // Disable button if no tries left on page load
    if (remainingResends <= 0) {
        resendBtn.disabled = true;
        resendBtn.innerHTML = '<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(0x)</span>';
        countdownDisplay.textContent = 'Batas pengiriman tercapai. Tunggu hingga OTP kadaluarsa.';
        countdownDisplay.classList.add('text-red-500');
        countdownDisplay.classList.remove('text-[#6b7280]');
    }

    // Auto-submit when 6 digits entered
    if (otpInput) {
        otpInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
            if (this.value.length === 6) {
                form?.requestSubmit();
            }
        });

        otpInput.addEventListener('keydown', function(e) {
            if (!/^\d$/.test(e.key) && !['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });
    }

    // Start countdown (client-side since server doesn't send expiry time for reset OTP)
    function startCountdown(durationSeconds = 600) {
        // Clear existing interval
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }

        // Reset display state
        countdownDisplay.classList.remove('text-red-500');
        countdownDisplay.classList.add('text-[#6b7280]');

        let remaining = durationSeconds;

        function updateCountdown() {
            if (remaining <= 0) {
                countdownDisplay.textContent = 'Kode OTP sudah kadaluarsa';
                countdownDisplay.classList.add('text-red-500');
                countdownDisplay.classList.remove('text-[#6b7280]');
                clearInterval(countdownInterval);
                countdownInterval = null;

                // Re-enable resend if they still have tries left
                if (remainingResends > 0) {
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = `<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(${remainingResends}x)</span>`;
                } else {
                    countdownDisplay.textContent = 'Batas pengiriman tercapai. Tunggu hingga OTP kadaluarsa.';
                }
                return;
            }

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            countdownDisplay.textContent = `Kode berlaku dalam ${minutes}:${seconds.toString().padStart(2, '0')}`;
            remaining--;
        }

        updateCountdown();
        countdownInterval = setInterval(updateCountdown, 1000);
    }

    // Resend OTP
    if (resendBtn) {
        resendBtn.addEventListener('click', async function() {
            if (this.disabled) return;

            this.disabled = true;
            this.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Mengirim...';

            try {
                const response = await fetch('/forgot-password/resend', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    remainingResends = data.remaining_resends;
                    setRemainingResends(remainingResends);
                    updateResendCountDisplay();

                    if (remainingResends > 0) {
                        this.innerHTML = `<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(${remainingResends}x)</span>`;
                        showToast('Kode OTP telah dikirim ulang ke email Anda', 'success');
                        // Re-enable button for next try
                        this.disabled = false;
                    } else {
                        this.innerHTML = '<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(0x)</span>';
                        this.disabled = true;
                        countdownDisplay.textContent = 'Batas pengiriman tercapai. Tunggu hingga OTP kadaluarsa.';
                        countdownDisplay.classList.add('text-red-500');
                        countdownDisplay.classList.remove('text-[#6b7280]');
                    }

                    // Restart countdown
                    const expiresIn = data.expires_in || 600;
                    startCountdown(expiresIn);
                } else {
                    this.disabled = false;
                    this.innerHTML = `<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(${remainingResends}x)</span>`;
                    showToast(data.error || 'Gagal mengirim OTP', 'error');
                }
            } catch (error) {
                this.disabled = false;
                this.innerHTML = `<i class="ph ph-arrow-clockwise mr-1"></i> Kirim Ulang OTP <span id="resendCount">(${remainingResends}x)</span>`;
                showToast('Terjadi kesalahan. Coba lagi nanti.', 'error');
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function() {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Memproses...';
            }
        });
    }

    // Start initial countdown (10 minutes default)
    startCountdown(600);

    // Focus on OTP input
    if (otpInput) {
        otpInput.focus();
    }
});

// Toast notification helper
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-xl shadow-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.innerHTML = `<i class="ph ${type === 'success' ? 'ph-check-circle' : type === 'error' ? 'ph-x-circle' : 'ph-info'} mr-2"></i>${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}