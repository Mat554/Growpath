/**
 * Parent Dashboard JavaScript
 * Handles notifications and child connection status
 */

window.toggleNotifications = function() {
    const menu = document.getElementById('notificationMenu');
    const bell = document.getElementById('bellIcon');
    const badge = document.getElementById('notifBadge');

    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        if (bell) bell.classList.remove('animate-ring');
        if (badge) badge.classList.add('hidden');
        setTimeout(() => {
            menu.classList.remove('opacity-0', 'scale-95');
            menu.classList.add('opacity-100', 'scale-100');
        }, 10);
    } else {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Notification dropdown toggle
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');

    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.add('hidden');
            }
        });
    }

    // Close notifications when clicking outside
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('notificationDropdown');
        const menu = document.getElementById('notificationMenu');
        if (dropdown && !dropdown.contains(e.target) && menu && !menu.classList.contains('hidden')) {
            window.toggleNotifications();
        }
    });
});