/**
 * Student Dashboard JavaScript
 * Handles notifications dropdown and UI interactions
 */

// Expose toggleNotifications globally
window.toggleNotifications = function() {
    const menu = document.getElementById('notificationMenu');

    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        setTimeout(() => {
            menu.classList.remove('opacity-0', 'scale-95');
            menu.classList.add('opacity-100', 'scale-100');
        }, 10);
    } else {
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 200);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    // Close notifications when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        const menu = document.getElementById('notificationMenu');

        if (dropdown && menu && !dropdown.contains(event.target) && !menu.classList.contains('hidden')) {
            window.toggleNotifications();
        }
    });

    // Card animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.exam-card').forEach(card => {
        observer.observe(card);
    });
});