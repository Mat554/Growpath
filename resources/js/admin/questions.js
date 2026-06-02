/**
 * Admin Question Management JavaScript
 * Handles question table rendering and status toggle
 */

// Get questions from global window variable (set by Blade)
window.globalQuestionsData = window.globalQuestionsData || [];
window.selectedQuestionIds = new Set();

/**
 * Load and render questions table
 */
window.loadQuestions = function() {
    const tbody = document.getElementById('questionTable');
    if (!tbody) return;

    tbody.innerHTML = '';
    const statEl = document.getElementById('statQuestions');
    if (statEl) statEl.innerText = window.globalQuestionsData.length;

    if (window.globalQuestionsData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-gray-400">Belum ada soal di database.</td></tr>';
        return;
    }

    window.globalQuestionsData.forEach(q => {
        const isActive = q.is_active == 1;
        const statusBadge = isActive
            ? `<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase">Tayang</span>`
            : `<span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase">Draft</span>`;

        const btnClass = isActive ? 'bg-red-500 hover:bg-red-600' : 'bg-[#4A90E2] hover:bg-[#357ABD]';
        const btnText = isActive ? 'Tarik' : 'Publish';

        tbody.innerHTML += `
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <td class="p-4 text-sm text-gray-700">${q.question_text}</td>
                <td class="p-4">${statusBadge}</td>
                <td class="p-4 text-right">
                    <form action="/admin-dashboard/question/${q.id}/toggle" method="POST" class="inline">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <button type="submit" class="px-3 py-1.5 ${btnClass} text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                            ${btnText}
                        </button>
                    </form>
                </td>
            </tr>`;
    });
};

/**
 * Load questions for publisher (only active ones)
 */
window.loadForPublisher = function() {
    const container = document.getElementById('publisherList');
    if (!container) return;

    container.innerHTML = '';

    // Filter only active questions
    const activeQuestions = window.globalQuestionsData.filter(q => q.is_active == 1);

    if (activeQuestions.length === 0) {
        container.innerHTML = '<div class="text-center p-8 text-gray-400">Belum ada soal yang berstatus TAYANG.<br><small class="text-xs">Silakan publish soal di menu Kelola Soal terlebih dahulu.</small></div>';
        return;
    }

    activeQuestions.forEach(q => {
        const isSelected = window.selectedQuestionIds.has(q.id);
        const activeClass = isSelected ? 'border-[#4A90E2] bg-[#EBF5FF]' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-[#4A90E2]';
        const checkIconColor = isSelected ? 'text-[#4A90E2]' : 'text-gray-300';
        const iconClass = isSelected ? 'ph-fill ph-check-square' : 'ph ph-square';

        const itemHtml = `
        <div class="p-4 rounded-xl border mb-3 cursor-pointer transition-all flex gap-4 items-start ${activeClass}" onclick="window.toggleSelection(${q.id})">
            <i class="${iconClass} text-2xl ${checkIconColor} mt-0.5"></i>
            <div class="flex-1">
                <div class="font-semibold text-sm text-gray-800 mb-2">${q.question_text}</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">R:</strong> ${q.opt_r}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">I:</strong> ${q.opt_i}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">A:</strong> ${q.opt_a}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">S:</strong> ${q.opt_s}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">E:</strong> ${q.opt_e}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">C:</strong> ${q.opt_c}</span>
                </div>
            </div>
        </div>`;
        container.innerHTML += itemHtml;
    });

    // Update counter
    const totalEl = document.getElementById('totalSelected');
    if (totalEl) totalEl.innerText = window.selectedQuestionIds.size + " Item";
};

/**
 * Toggle question selection
 */
window.toggleSelection = function(id) {
    if (window.selectedQuestionIds.has(id)) {
        window.selectedQuestionIds.delete(id);
    } else {
        window.selectedQuestionIds.add(id);
    }
    window.loadForPublisher();
};

// Auto-load on page ready
document.addEventListener('DOMContentLoaded', () => {
    if (typeof loadQuestions === 'function') loadQuestions();
});