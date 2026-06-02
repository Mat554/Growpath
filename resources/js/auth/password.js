/**
 * Password reset pages JavaScript
 * Handles password toggle and form loading states
 */

document.addEventListener('DOMContentLoaded', () => {
    // Password visibility toggle
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (!input || input.tagName !== 'INPUT') return;

            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;

            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
            } else {
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
            }
        });
    });

    // Legacy togglePassword function (for views using onclick)
    window.togglePassword = function(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        if (!passwordInput || !eyeIcon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.replace('ph-eye', 'ph-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.replace('ph-eye-slash', 'ph-eye');
        }
    };

    // Form submit with loading state
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Memproses...';
            }
        });
    });
});
