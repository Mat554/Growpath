/**
 * Admin Dashboard JavaScript
 * Handles tab navigation, section switching, and initial data loading
 */

document.addEventListener('DOMContentLoaded', () => {
    // Show section function (used by onclick in Blade / shared with questions.js)
    window.showSection = function(sectionId) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));

        // Update nav buttons
        document.querySelectorAll('aside button').forEach(el => {
            el.classList.remove('bg-[#EBF5FF]', 'text-[#4A90E2]');
            el.classList.add('text-gray-500', 'hover:bg-gray-50');
        });

        // Show selected section
        const section = document.getElementById(sectionId);
        if (section) section.classList.add('active');

        // Highlight active nav
        const navBtn = document.getElementById('nav-' + sectionId);
        if (navBtn) {
            navBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
            navBtn.classList.add('bg-[#EBF5FF]', 'text-[#4A90E2]');
        }

        // Load data for specific sections
        if (sectionId === 'publish' || sectionId === 'overview') {
            if (typeof window.loadQuestions === 'function') window.loadQuestions();
        }
        if (sectionId === 'publisher-v2') {
            if (typeof window.loadForPublisher === 'function') window.loadForPublisher();
        }
    };

    // Load questions on page ready (questions.js already exposes these)
    const activeTab = window.activeTabSession;
    if (activeTab && activeTab !== '') {
        window.showSection(activeTab);
    } else {
        if (typeof window.loadQuestions === 'function') window.loadQuestions();
    }
});