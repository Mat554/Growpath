/**
 * Mobile Parent Profile Script
 * Handles avatar upload for parent mobile view
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
        location.reload();
    };

})();