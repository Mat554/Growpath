/**
 * Login page JavaScript
 * Handles password toggle and role switching
 */

// Expose togglePassword globally for onclick handlers
window.togglePassword = function() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    if (!passwordInput || !eyeIcon) return;

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.replace('ph-eye', 'ph-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.replace('ph-eye-slash', 'ph-eye');
    }
};

// Expose switchRole globally for onclick handlers
window.switchRole = function(role) {
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
};

document.addEventListener('DOMContentLoaded', () => {
    // Loading state on form submit
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin mr-2"></i> Memproses...';
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
        });
    }
});
