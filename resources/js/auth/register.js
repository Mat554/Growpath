/**
 * Registration page JavaScript
 * Handles password toggle and role-specific form sections
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
    const sectionSiswa = document.getElementById('sectionSiswa');
    const sectionOrtu = document.getElementById('sectionOrtu');
    const roleInput = document.getElementById('roleInput');
    const btnSubmit = document.getElementById('btnSubmit');

    roleInput.value = role;

    if (role === 'siswa') {
        tabSiswa.classList.add('bg-white', 'text-[#4A90E2]', 'shadow-sm');
        tabSiswa.classList.remove('text-[#888]');

        tabOrtu.classList.remove('bg-white', 'text-[#4A90E2]', 'shadow-sm');
        tabOrtu.classList.add('text-[#888]');

        sectionSiswa.classList.remove('hidden');
        sectionOrtu.classList.add('hidden');

        btnSubmit.innerText = "Daftar Sebagai Siswa";
    } else {
        tabOrtu.classList.add('bg-white', 'text-[#4A90E2]', 'shadow-sm');
        tabOrtu.classList.remove('text-[#888]');

        tabSiswa.classList.remove('bg-white', 'text-[#4A90E2]', 'shadow-sm');
        tabSiswa.classList.add('text-[#888]');

        sectionSiswa.classList.add('hidden');
        sectionOrtu.classList.remove('hidden');

        btnSubmit.innerText = "Daftar Sebagai Orang Tua";
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Loading state on form submit
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            const submitBtn = document.getElementById('btnSubmit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin mr-2"></i> Mendaftarkan...';
            }
        });
    }
});