/**
 * Student Profile JavaScript
 * Handles avatar preview, upload cancel, and clipboard copy
 */

let originalSrc = '';

// Expose previewImage globally
window.previewImage = function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            const saveBtn = document.getElementById('saveButtonContainer');
            if (preview) preview.src = e.target.result;
            if (saveBtn) {
                saveBtn.classList.remove('hidden');
                saveBtn.classList.add('flex');
            }
        };
        reader.readAsDataURL(file);
    }
};

// Expose cancelUpload globally
window.cancelUpload = function() {
    const upload = document.getElementById('avatarUpload');
    const preview = document.getElementById('avatarPreview');
    const saveBtn = document.getElementById('saveButtonContainer');

    if (upload) upload.value = "";
    if (preview && originalSrc) preview.src = originalSrc;
    if (saveBtn) {
        saveBtn.classList.add('hidden');
        saveBtn.classList.remove('flex');
    }
};

// Expose copyToClipboard globally
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('User ID berhasil disalin!');
    }).catch(() => {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('User ID berhasil disalin!');
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // Store original avatar source
    const preview = document.getElementById('avatarPreview');
    if (preview) originalSrc = preview.src;

    // Avatar input change handler
    const avatarInput = document.getElementById('avatarInput');
    if (avatarInput) {
        avatarInput.addEventListener('change', window.previewImage);
    }

    // Form submit loading state
    const avatarForm = document.getElementById('avatarForm');
    if (avatarForm) {
        avatarForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Mengupload...';
            }
        });
    }
});