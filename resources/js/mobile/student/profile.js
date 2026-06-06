/**
 * Mobile Student Profile Script
 * Handles avatar upload, clipboard, and profile interactions
 */

(function() {
    'use strict';

    let originalSrc = '';

    /**
     * Preview image before upload
     */
    window.previewImage = function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                const saveContainer = document.getElementById('saveButtonContainer');

                if (preview) preview.src = e.target.result;
                if (saveContainer) {
                    saveContainer.classList.remove('hidden');
                    saveContainer.classList.add('flex');
                }
            };
            reader.readAsDataURL(file);
        }
    };

    /**
     * Cancel avatar upload and reset preview
     */
    window.cancelUpload = function() {
        const fileInput = document.getElementById('avatarUpload');
        const preview = document.getElementById('avatarPreview');
        const saveContainer = document.getElementById('saveButtonContainer');

        if (fileInput) fileInput.value = '';
        if (preview && originalSrc) preview.src = originalSrc;
        if (saveContainer) {
            saveContainer.classList.add('hidden');
            saveContainer.classList.remove('flex');
        }
    };

    /**
     * Copy text to clipboard
     */
    window.copyToClipboard = function(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showCopyNotification();
            }).catch(function() {
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    };

    /**
     * Fallback copy to clipboard for older browsers
     */
    function fallbackCopyToClipboard(text) {
        const input = document.createElement('input');
        input.value = text;
        input.style.position = 'fixed';
        input.style.opacity = '0';
        document.body.appendChild(input);
        input.select();

        try {
            document.execCommand('copy');
            showCopyNotification();
        } catch (err) {
            console.error('Gagal menyalin teks:', err);
            alert('Gagal menyalin kode.');
        }

        document.body.removeChild(input);
    }

    /**
     * Show notification after successful copy
     */
    function showCopyNotification() {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-20 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium z-50';
        toast.style.cssText = 'animation: fadeIn 0.3s ease-out forwards;';
        toast.textContent = 'Kode berhasil disalin!';
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function() {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    }

    /**
     * Initialize when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        const preview = document.getElementById('avatarPreview');
        if (preview) originalSrc = preview.src;
    });

})();
