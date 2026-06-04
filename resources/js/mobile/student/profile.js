/**
 * Mobile Student Profile Script
 * Handles avatar upload and clipboard functionality
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileProfile();
    });

    /**
     * Initialize mobile profile functionality
     */
    function initMobileProfile() {
        // Avatar upload preview is handled inline in the view
        // This script handles additional functionality if needed
    }

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
            console.error('Copy failed:', err);
            alert('Gagal menyalin kode.');
        }

        document.body.removeChild(input);
    }

    /**
     * Show notification after successful copy
     */
    function showCopyNotification() {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-20 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-medium z-50 animate-fade-in';
        toast.style.cssText = 'animation: fadeIn 0.3s ease-out forwards;';
        toast.textContent = 'Kode berhasil disalin!';
        document.body.appendChild(toast);

        // Remove after 2 seconds
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(function() {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    }

    /**
     * Preview image before upload
     */
    window.previewImage = function(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('avatarPreview');
            const saveContainer = document.getElementById('saveButtonContainer');

            if (preview) {
                preview.src = reader.result;
            }
            if (saveContainer) {
                saveContainer.classList.remove('hidden');
                saveContainer.classList.add('flex');
            }
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    /**
     * Cancel avatar upload
     */
    window.cancelUpload = function() {
        const fileInput = document.getElementById('avatarUpload');
        const saveContainer = document.getElementById('saveButtonContainer');

        if (fileInput) {
            fileInput.value = '';
        }
        if (saveContainer) {
            saveContainer.classList.add('hidden');
            saveContainer.classList.remove('flex');
        }
        // Reload to reset preview
        location.reload();
    };

})();