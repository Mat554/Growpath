/**
 * Admin Question Archive JavaScript
 * Handles question archive with drag-drop between class columns
 * And Publisher folder-based question selection
 */

window.globalQuestionsData = window.globalQuestionsData || [];
window.selectedQuestionIds = new Set();
window.currentPublisherFolder = null;

/**
 * Initialize the archive system
 */
document.addEventListener('DOMContentLoaded', () => {
    window.loadQuestionArchive();
    window.initPublisherFolders();
});

/**
 * Initialize publisher folder counts
 */
window.initPublisherFolders = function() {
    window.updatePublisherFolderCounts();
};

window.updatePublisherFolderCounts = function() {
    const counts = { 10: 0, 11: 0, 12: 0 };

    window.globalQuestionsData.forEach(q => {
        if (q.is_active == 1 && q.target_class) {
            const classes = q.target_class.split(',').map(c => c.trim());
            classes.forEach(cls => {
                if (counts[cls] !== undefined) {
                    counts[cls]++;
                }
            });
        }
    });

    document.getElementById('folder-count-10').innerText = counts[10] + ' soal';
    document.getElementById('folder-count-11').innerText = counts[11] + ' soal';
    document.getElementById('folder-count-12').innerText = counts[12] + ' soal';
};

/**
 * Open a publisher folder
 */
window.openPublisherFolder = function(classLevel) {
    window.currentPublisherFolder = classLevel;

    // Update folder button styles
    ['10', '11', '12'].forEach(c => {
        const btn = document.getElementById('folder-btn-' + c);
        if (btn) {
            if (c === classLevel) {
                const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
                const color = colors[c];
                btn.className = `flex-1 bg-${color}-100 hover:bg-${color}-200 border-2 border-${color}-400 rounded-xl p-4 transition-all shadow-sm`;
            } else {
                const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
                const color = colors[c];
                btn.className = `flex-1 bg-${color}-50 hover:bg-${color}-100 border-2 border-${color}-200 rounded-xl p-4 transition-all`;
            }
        }
    });

    // Update folder title
    const title = document.getElementById('publisherFolderTitle');
    const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
    const colorNames = { 10: 'Kelas 10', 11: 'Kelas 11', 12: 'Kelas 12' };
    if (title) {
        title.innerHTML = `<span class="text-${colors[classLevel]}-600 font-bold">${colorNames[classLevel]}</span>`;
    }

    // Show actions
    const actions = document.getElementById('publisherFolderActions');
    if (actions) actions.classList.remove('hidden');
    const actionsSpan = document.getElementById('publisherFolderActions');
    if (actionsSpan) actionsSpan.className = 'gap-2 flex';

    // Load questions for this folder
    window.loadPublisherQuestions(classLevel);
};

/**
 * Close publisher folder (show initial state)
 */
window.closePublisherFolder = function() {
    window.currentPublisherFolder = null;

    // Reset folder button styles
    ['10', '11', '12'].forEach(c => {
        const btn = document.getElementById('folder-btn-' + c);
        if (btn) {
            const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
            const color = colors[c];
            btn.className = `flex-1 bg-${color}-50 hover:bg-${color}-100 border-2 border-${color}-200 rounded-xl p-4 transition-all`;
        }
    });

    // Reset title
    const title = document.getElementById('publisherFolderTitle');
    if (title) title.innerText = 'Pilih folder di atas';

    // Hide actions
    const actions = document.getElementById('publisherFolderActions');
    if (actions) actions.classList.add('hidden');

    // Reset list to initial state
    const container = document.getElementById('publisherList');
    if (container) {
        container.innerHTML = `
            <div class="text-center p-8 text-gray-400">
                <i class="ph-fill ph-folder-open text-5xl mb-3 text-gray-300"></i>
                <p>Klik folder di atas untuk melihat soal</p>
            </div>
        `;
    }
};

/**
 * Load questions for publisher folder
 */
window.loadPublisherQuestions = function(classLevel) {
    const container = document.getElementById('publisherList');
    if (!container) return;

    container.innerHTML = '';

    // Filter only active questions that belong to this class
    const folderQuestions = window.globalQuestionsData.filter(q => {
        if (q.is_active != 1) return false;
        if (!q.target_class) return false;
        const classes = q.target_class.split(',').map(c => c.trim());
        return classes.includes(classLevel);
    });

    if (folderQuestions.length === 0) {
        const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
        const color = colors[classLevel];
        container.innerHTML = `
            <div class="text-center p-8 text-gray-400">
                <i class="ph-fill ph-folder-simple-dashed text-5xl mb-3 text-${color}-200"></i>
                <p>Belum ada soal untuk Kelas ${classLevel}</p>
                <small class="text-xs">Tambahkan soal di menu Kelola Soal</small>
            </div>
        `;
        return;
    }

    folderQuestions.forEach(q => {
        const isSelected = window.selectedQuestionIds.has(q.id);
        const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
        const color = colors[classLevel];
        const activeClass = isSelected ? `border-${color}-400 bg-${color}-50` : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50';
        const checkIconColor = isSelected ? `text-${color}-500` : 'text-gray-300';
        const iconClass = isSelected ? 'ph-fill ph-check-square' : 'ph ph-square';

        const itemHtml = `
        <div class="p-4 rounded-xl border mb-2 cursor-pointer transition-all flex gap-3 items-start ${activeClass}"
             onclick="window.toggleSelection(${q.id})">
            <i class="${iconClass} text-xl ${checkIconColor} mt-0.5"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-700 line-clamp-2">${q.question_text}</p>
                <div class="flex flex-wrap gap-1 mt-2">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500"><strong class="text-[#4A90E2]">R:</strong> ${q.opt_r.substring(0, 15)}${q.opt_r.length > 15 ? '...' : ''}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500"><strong class="text-[#4A90E2]">I:</strong> ${q.opt_i.substring(0, 15)}${q.opt_i.length > 15 ? '...' : ''}</span>
                </div>
            </div>
        </div>`;
        container.innerHTML += itemHtml;
    });

    window.updateTotalSelected();
};

/**
 * Select all questions in current folder
 */
window.selectAllInFolder = function() {
    if (!window.currentPublisherFolder) return;

    const folderQuestions = window.globalQuestionsData.filter(q => {
        if (q.is_active != 1) return false;
        if (!q.target_class) return false;
        const classes = q.target_class.split(',').map(c => c.trim());
        return classes.includes(window.currentPublisherFolder);
    });

    folderQuestions.forEach(q => {
        window.selectedQuestionIds.add(q.id);
    });

    window.loadPublisherQuestions(window.currentPublisherFolder);
};

/**
 * Clear selections for questions in current folder
 */
window.clearFolderSelection = function() {
    if (!window.currentPublisherFolder) return;

    const folderQuestions = window.globalQuestionsData.filter(q => {
        if (!window.selectedQuestionIds.has(q.id)) return false;
        if (!q.target_class) return false;
        const classes = q.target_class.split(',').map(c => c.trim());
        return classes.includes(window.currentPublisherFolder);
    });

    folderQuestions.forEach(q => {
        window.selectedQuestionIds.delete(q.id);
    });

    window.loadPublisherQuestions(window.currentPublisherFolder);
};

/**
 * Update total selected counter
 */
window.updateTotalSelected = function() {
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

    // Refresh current folder view if one is open
    if (window.currentPublisherFolder) {
        window.loadPublisherQuestions(window.currentPublisherFolder);
    }

    window.updateTotalSelected();
};

/**
 * Load all questions into the archive system (Kelola Soal)
 */
window.loadQuestionArchive = function() {
    // Organize questions by class
    const byClass = { all: [], 10: [], 11: [], 12: [] };

    window.globalQuestionsData.forEach(q => {
        const classes = q.target_class ? q.target_class.split(',').map(c => c.trim()) : [];

        // Add to "all" list
        byClass.all.push(q);

        // Add to respective class columns
        classes.forEach(cls => {
            if (byClass[cls]) {
                byClass[cls].push(q);
            }
        });
    });

    // Render each column
    window.renderArchiveColumn('10', byClass[10]);
    window.renderArchiveColumn('11', byClass[11]);
    window.renderArchiveColumn('12', byClass[12]);
    window.renderAllQuestionsList(byClass.all);

    // Update counts
    document.getElementById('count-class-10').innerText = byClass[10].length;
    document.getElementById('count-class-11').innerText = byClass[11].length;
    document.getElementById('count-class-12').innerText = byClass[12].length;
    document.getElementById('count-all').innerText = byClass.all.length;

    // Also update publisher folder counts
    window.updatePublisherFolderCounts();
};

/**
 * Render a single archive column
 */
window.renderArchiveColumn = function(classLevel, questions) {
    const container = document.getElementById('archive-class-' + classLevel);
    if (!container) return;

    if (questions.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm">Seret soal ke sini</div>';
        return;
    }

    container.innerHTML = questions.map(q => window.createArchiveCard(q, classLevel)).join('');
};

/**
 * Render the "All Questions" list
 */
window.renderAllQuestionsList = function(questions) {
    const container = document.getElementById('allQuestionsList');
    if (!container) return;

    if (questions.length === 0) {
        container.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm">Belum ada soal.</div>';
        return;
    }

    container.innerHTML = questions.map(q => window.createAllQuestionsCard(q)).join('');
};

/**
 * Create an archive card HTML for a class column
 */
window.createArchiveCard = function(question, classLevel) {
    const colors = { 10: 'blue', 11: 'green', 12: 'purple' };
    const color = colors[classLevel] || 'gray';
    const isActive = question.is_active == 1;
    const activeBtnClass = isActive
        ? 'bg-green-500 hover:bg-green-600 text-white'
        : 'bg-gray-300 hover:bg-gray-400 text-gray-600';
    const activeBtnText = isActive ? 'Aktif' : 'Draft';

    return `
        <div class="question-card bg-white p-3 rounded-lg border border-${color}-200 shadow-sm mb-2"
             data-question-id="${question.id}">
            <div class="flex items-start gap-2">
                <div class="cursor-grab active:cursor-grabbing" draggable="true"
                     ondragstart="window.handleArchiveDragStart(event, ${question.id})"
                     ondragend="window.handleArchiveDragEnd(event)">
                    <i class="ph-fill ph-dots-six-vertical text-gray-400 mt-1 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 line-clamp-2">${question.question_text}</p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">
                            <strong class="text-[#4A90E2]">R:</strong> ${question.opt_r.substring(0, 20)}${question.opt_r.length > 20 ? '...' : ''}
                        </span>
                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">
                            <strong class="text-[#4A90E2]">I:</strong> ${question.opt_i.substring(0, 20)}${question.opt_i.length > 20 ? '...' : ''}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="px-2 py-0.5 bg-${color}-50 text-${color}-600 rounded text-[10px] font-semibold">Kelas ${classLevel}</span>
                        <button onclick="window.toggleQuestionStatus(${question.id})"
                                class="px-2 py-0.5 rounded text-[10px] font-semibold transition-all ${activeBtnClass}">
                            <i class="ph-fill ph-power ${isActive ? '' : 'hidden'}"></i>
                            <i class="ph ph-power ${isActive ? 'hidden' : ''}"></i>
                            ${activeBtnText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
};

/**
 * Create a card for the "All Questions" list
 */
window.createAllQuestionsCard = function(question) {
    const classes = question.target_class ? question.target_class.split(',').map(c => c.trim()) : [];
    const classBadges = classes.length > 0
        ? classes.map(c => `<span class="px-2 py-0.5 bg-[#EBF5FF] text-[#4A90E2] rounded text-[10px] font-semibold">K${c}</span>`).join('')
        : '<span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-semibold">-</span>';

    const isActive = question.is_active == 1;
    const activeBtnClass = isActive
        ? 'bg-green-500 hover:bg-green-600 text-white'
        : 'bg-gray-300 hover:bg-gray-400 text-gray-600';
    const activeBtnText = isActive ? 'Aktif' : 'Draft';

    return `
        <div class="question-card bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-2"
             data-question-id="${question.id}">
            <div class="flex items-start gap-3">
                <div class="cursor-grab active:cursor-grabbing mt-0.5" draggable="true"
                     ondragstart="window.handleArchiveDragStart(event, ${question.id})"
                     ondragend="window.handleArchiveDragEnd(event)">
                    <i class="ph-fill ph-dots-six-vertical text-gray-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 line-clamp-2">${question.question_text}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        ${classBadges}
                        <button onclick="window.toggleQuestionStatus(${question.id})"
                                class="px-2 py-0.5 rounded text-[10px] font-semibold transition-all ${activeBtnClass}">
                            <i class="ph-fill ph-power ${isActive ? '' : 'hidden'}"></i>
                            <i class="ph ph-power ${isActive ? 'hidden' : ''}"></i>
                            ${activeBtnText}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
};

// Drag and Drop handlers for archive (Kelola Soal)
window.draggedQuestionId = null;
window.draggedFromClass = null;

/**
 * Handle drag start from archive
 */
window.handleArchiveDragStart = function(event, questionId) {
    window.draggedQuestionId = questionId;
    event.target.classList.add('opacity-50', 'scale-95');
    event.dataTransfer.effectAllowed = 'move';
    event.dataTransfer.setData('text/plain', questionId);
};

/**
 * Handle drag end
 */
window.handleArchiveDragEnd = function(event) {
    event.target.classList.remove('opacity-50', 'scale-95');
    window.draggedQuestionId = null;
    window.draggedFromClass = null;

    // Remove all drag-over styles
    document.querySelectorAll('.drag-over').forEach(el => {
        el.classList.remove('drag-over', 'border-solid');
    });
};

/**
 * Handle drag over a column
 */
window.handleColumnDragOver = function(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = 'move';
    event.currentTarget.classList.add('drag-over');
};

/**
 * Handle drag leave from column
 */
window.handleColumnDragLeave = function(event) {
    event.currentTarget.classList.remove('drag-over');
};

/**
 * Handle drop on a class column
 */
window.handleColumnDrop = function(event, targetClass) {
    event.preventDefault();
    event.currentTarget.classList.remove('drag-over');

    if (!window.draggedQuestionId) return;

    const questionId = window.draggedQuestionId;
    const question = window.globalQuestionsData.find(q => q.id == questionId);

    if (!question) return;

    // Get current classes
    let currentClasses = question.target_class ? question.target_class.split(',').map(c => c.trim()) : [];

    // Add the target class if not already present
    if (!currentClasses.includes(targetClass)) {
        currentClasses.push(targetClass);
    }

    // Sort classes numerically
    currentClasses.sort((a, b) => a - b);

    // Update the question's target_class
    const newTargetClass = currentClasses.join(',');

    // Send update to server
    window.updateQuestionClass(questionId, newTargetClass);
};

/**
 * Update question class via API
 */
window.updateQuestionClass = function(questionId, newTargetClass) {
    fetch(`/admin-dashboard/question/${questionId}/class`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            target_class: newTargetClass
        })
    })
    .then(async res => {
        if (!res.ok) {
            const errData = await res.json().catch(() => ({}));
            throw new Error(errData.message || "Gagal mengupdate kelas soal.");
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            // Update local data
            const question = window.globalQuestionsData.find(q => q.id == questionId);
            if (question) {
                question.target_class = newTargetClass;
            }

            // Reload the archive
            window.loadQuestionArchive();
        }
    })
    .catch(err => {
        console.error("Error updating class:", err);
        alert("Gagal mengupdate kelas: " + err.message);
    });
};

/**
 * Toggle question active status
 */
window.toggleQuestionStatus = function(questionId) {
    fetch(`/admin-dashboard/question/${questionId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const question = window.globalQuestionsData.find(q => q.id == questionId);
            if (question) {
                question.is_active = question.is_active == 1 ? 0 : 1;
            }
            window.loadQuestionArchive();
        }
    })
    .catch(err => {
        console.error("Error toggling status:", err);
    });
};