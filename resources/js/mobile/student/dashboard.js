/**
 * Mobile Student Dashboard Script
 * Handles notification dropdown functionality for desktop and mobile devices
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileNotifications();
    });

    /**
     * Initialize mobile notifications functionality
     */
    function initMobileNotifications() {
        // Close notifications when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notificationDropdown');
            const menu = document.getElementById('notificationMenu');

            if (dropdown && menu && !dropdown.contains(e.target)) {
                menu.classList.add('hidden');
                menu.style.opacity = '0';
                menu.style.transform = 'scale(0.95)';
            }
        });
    }

    /**
     * Toggle notifications dropdown
     */
    window.toggleNotifications = function() {
        const menu = document.getElementById('notificationMenu');
        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');

        if (isHidden) {
            menu.classList.remove('hidden');
            menu.style.opacity = '1';
            menu.style.transform = 'scale(1)';
        } else {
            menu.classList.add('hidden');
            menu.style.opacity = '0';
            menu.style.transform = 'scale(0.95)';
        }
    };

})();