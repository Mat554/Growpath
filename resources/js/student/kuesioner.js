/**
 * Kuesioner (Exam List) JavaScript
 * Handles filtering and search functionality
 */

// State for current category
let currentKategori = 'semua';

// Expose filterKuesioner globally
window.filterKuesioner = function(kategori, btnElement) {
    currentKategori = kategori;

    // Reset all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'text-[#4A90E2]', 'border-[#4A90E2]', 'font-semibold');
        btn.classList.add('text-gray-500', 'border-transparent', 'font-medium');
    });

    // Highlight selected tab
    if (btnElement) {
        btnElement.classList.add('active', 'text-[#4A90E2]', 'border-[#4A90E2]', 'font-semibold');
        btnElement.classList.remove('text-gray-500', 'border-transparent', 'font-medium');
    }

    // Run search to sync with tab
    window.searchKuesioner();
};

// Expose searchKuesioner globally
window.searchKuesioner = function() {
    const input = document.getElementById('searchInput');
    if (!input) return;

    const searchTerm = input.value.toLowerCase();
    const cards = document.querySelectorAll('.kuesioner-card');

    cards.forEach(card => {
        const titleEl = card.querySelector('h3');
        if (!titleEl) return;

        const title = titleEl.innerText.toLowerCase();
        const cardKategori = card.getAttribute('data-kategori');

        const isMatchSearch = title.includes(searchTerm);
        const isMatchTab = (currentKategori === 'semua' || cardKategori === currentKategori);

        card.style.display = (isMatchSearch && isMatchTab) ? 'flex' : 'none';
    });
};

document.addEventListener('DOMContentLoaded', () => {
    // Initialize first tab as active
    const firstTab = document.querySelector('.tab-btn');
    if (firstTab) {
        firstTab.classList.add('active', 'text-[#4A90E2]', 'border-[#4A90E2]', 'font-semibold');
    }
});