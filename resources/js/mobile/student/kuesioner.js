/**
 * Mobile Student Kuesioner Script
 * Handles exam list filtering for mobile devices
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileKuesioner();
    });

    /**
     * Initialize mobile kuesioner functionality
     */
    function initMobileKuesioner() {
        // Additional initialization if needed
    }

    /**
     * Filter kuesioner by category (mobile version)
     */
    window.filterKuesioner = function(kategori, btn) {
        // Update active tab styling
        const tabs = document.querySelectorAll('.tab-btn');
        tabs.forEach(function(tab) {
            tab.classList.remove('active', 'text-[#4A90E2]', 'bg-[#EBF5FF]');
            tab.classList.add('text-gray-500', 'bg-white', 'border', 'border-gray-200');
        });

        if (btn) {
            btn.classList.add('active', 'text-[#4A90E2]', 'bg-[#EBF5FF]');
            btn.classList.remove('text-gray-500', 'border', 'border-gray-200');
        }

        // Filter cards
        const cards = document.querySelectorAll('.kuesioner-card');
        cards.forEach(function(card) {
            const cardKategori = card.getAttribute('data-kategori');
            if (kategori === 'semua' || cardKategori === kategori) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    };

})();