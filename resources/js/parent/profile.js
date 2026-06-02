/**
 * Parent Profile JavaScript
 * Handles avatar preview, revoke modal, and clipboard copy
 */

let originalSrc = '';

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

window.openRevokeModal = function() {
    const modal = document.getElementById('revokeModal');
    const content = document.getElementById('revokeModalContent');
    if (!modal || !content) return;

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeRevokeModal = function() {
    const modal = document.getElementById('revokeModal');
    const content = document.getElementById('revokeModalContent');
    if (!modal || !content) return;

    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => { modal.classList.add('hidden'); }, 200);
};

document.addEventListener('DOMContentLoaded', () => {
    // Store original avatar source
    const preview = document.getElementById('avatarPreview');
    if (preview) originalSrc = preview.src;

    // Avatar input change handler
    const avatarInput = document.getElementById('avatarUpload');
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

    // Connect form loading state
    const connectForm = document.getElementById('connectForm');
    if (connectForm) {
        connectForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menghubungkan...';
            }
        });
    }

    // Close revoke modal on backdrop click
    const revokeModal = document.getElementById('revokeModal');
    if (revokeModal) {
        const backdrop = revokeModal.querySelector('.absolute.inset-0');
        if (backdrop) {
            backdrop.addEventListener('click', window.closeRevokeModal);
        }
    }
});