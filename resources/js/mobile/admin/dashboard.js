/**
 * Mobile Admin Dashboard Script
 * Handles navigation and section switching for mobile admin view
 */

(function() {
    'use strict';

    // Data from Laravel (injected via blade)
    let questionsData = window.globalQuestionsData || [];
    let csrfToken = window.csrfToken || '';
    let selectedQuestions = [];
    let simQuestions = [];
    let simAnswers = {};
    let simCurrentIndex = 0;

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileAdmin();
    });

    /**
     * Initialize mobile admin functionality
     */
    function initMobileAdmin() {
        // Load questions for publisher if element exists
        const publisherList = document.getElementById('publisherList');
        if (publisherList) {
            renderMobilePublisherList();
        }

        const questionTable = document.getElementById('questionTable');
        if (questionTable) {
            loadMobileQuestions();
        }
    }

    /**
     * Toggle mobile sidebar menu
     */
    window.toggleMobileMenu = function() {
        const sidebar = document.getElementById('mobileSidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    };

    /**
     * Show a specific section and hide others
     */
    window.showSection = function(sectionId) {
        const sections = document.querySelectorAll('.section');
        sections.forEach(function(section) {
            section.classList.remove('active');
        });

        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
        }
    };

    /**
     * Render mobile publisher question list
     */
    function renderMobilePublisherList() {
        const container = document.getElementById('publisherList');
        if (!container || !questionsData.length) {
            container.innerHTML = '<p class="text-center text-gray-400 text-xs py-4">Belum ada soal.</p>';
            return;
        }

        let html = '';
        questionsData.forEach(function(q) {
            html += '<div class="p-3 bg-gray-50 rounded-xl mb-2 border border-gray-100">' +
                '<div class="flex items-start gap-2">' +
                '<input type="checkbox" id="q_' + q.id + '" onchange="toggleMobileQuestion(' + q.id + ')" class="mt-1">' +
                '<label for="q_' + q.id + '" class="flex-1 cursor-pointer">' +
                '<p class="text-xs text-gray-700 line-clamp-2">' + q.question_text + '</p>' +
                '</label>' +
                '</div></div>';
        });

        container.innerHTML = html;
    }

    /**
     * Toggle question selection for mobile
     */
    window.toggleMobileQuestion = function(questionId) {
        const index = selectedQuestions.indexOf(questionId);
        if (index === -1) {
            selectedQuestions.push(questionId);
        } else {
            selectedQuestions.splice(index, 1);
        }
        updateMobileSelectedCount();
    };

    /**
     * Update selected count display
     */
    function updateMobileSelectedCount() {
        const counter = document.getElementById('totalSelected');
        if (counter) {
            counter.textContent = selectedQuestions.length;
        }
    }

    /**
     * Load questions for management section
     */
    function loadMobileQuestions() {
        const container = document.getElementById('questionTable');
        if (!container) return;

        if (!questionsData.length) {
            container.innerHTML = '<p class="text-center text-gray-400 text-xs py-8">Belum ada soal.</p>';
            return;
        }

        let html = '';
        questionsData.forEach(function(q) {
            const statusClass = q.is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500';
            const statusText = q.is_active ? 'Aktif' : 'Nonaktif';
            html += '<div class="p-3 bg-gray-50 rounded-xl mb-2">' +
                '<div class="flex justify-between items-start mb-2">' +
                '<p class="text-xs text-gray-700 flex-1">' + q.question_text.substring(0, 50) + '...</p>' +
                '<span class="px-2 py-0.5 ' + statusClass + ' rounded-full text-[10px] font-bold ml-2">' + statusText + '</span>' +
                '</div>' +
                '<button onclick="toggleMobileQuestionStatus(' + q.id + ')" class="text-[10px] text-[#4A90E2] font-medium">' +
                (q.is_active ? 'Nonaktifkan' : 'Aktifkan') +
                '</button></div>';
        });

        container.innerHTML = html;
    }

    /**
     * Toggle question status
     */
    window.toggleMobileQuestionStatus = function(questionId) {
        if (!csrfToken) return;

        fetch('/admin-dashboard/question/' + questionId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                loadMobileQuestions();
            }
        })
        .catch(function(error) {
            console.error('Error toggling question:', error);
        });
    };

    /**
     * Start beta test for mobile
     */
    window.startBetaTest = function() {
        if (selectedQuestions.length === 0) {
            alert('Pilih soal terlebih dahulu!');
            return;
        }

        simQuestions = questionsData.filter(function(q) {
            return selectedQuestions.indexOf(q.id) !== -1;
        });
        simAnswers = {};
        simCurrentIndex = 0;

        // Show modal
        const modal = document.getElementById('betaModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        showMobileSimQuestion(0);
    };

    /**
     * Show simulation question
     */
    function showMobileSimQuestion(index) {
        const quizArea = document.getElementById('simQuizArea');
        const resultArea = document.getElementById('simResultArea');
        const questionText = document.getElementById('simQText');
        const optionsContainer = document.getElementById('simOptions');
        const progressText = document.getElementById('simProgress');

        if (!quizArea || !simQuestions[index]) return;

        if (resultArea) resultArea.classList.add('hidden');
        quizArea.classList.remove('hidden');

        const q = simQuestions[index];
        questionText.textContent = (index + 1) + '. ' + q.question_text;

        const options = ['R', 'I', 'A', 'S', 'E', 'C'];
        const labels = {
            R: 'Realistic',
            I: 'Investigative',
            A: 'Artistic',
            S: 'Social',
            E: 'Enterprising',
            C: 'Conventional'
        };

        let optionsHtml = '';
        options.forEach(function(opt) {
            const checked = simAnswers[q.id] === opt ? 'checked' : '';
            optionsHtml += '<label class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg cursor-pointer">' +
                '<input type="radio" name="simOption" value="' + opt + '" ' + checked + ' onchange="setMobileSimAnswer(' + q.id + ', \'' + opt + '\')">' +
                '<span class="text-xs">' + opt + ' - ' + labels[opt] + '</span></label>';
        });
        optionsContainer.innerHTML = optionsHtml;

        if (progressText) {
            progressText.textContent = 'Soal ' + (index + 1) + ' / ' + simQuestions.length;
        }
    }

    /**
     * Set answer for simulation
     */
    window.setMobileSimAnswer = function(questionId, answer) {
        simAnswers[questionId] = answer;
    };

    /**
     * Next simulation question
     */
    window.nextSimQuestion = function() {
        const currentQ = simQuestions[simCurrentIndex];
        if (!simAnswers[currentQ.id]) {
            alert('Pilih jawaban terlebih dahulu!');
            return;
        }

        simCurrentIndex++;

        if (simCurrentIndex >= simQuestions.length) {
            showMobileSimResult();
        } else {
            showMobileSimQuestion(simCurrentIndex);
        }
    };

    /**
     * Show simulation result
     */
    function showMobileSimResult() {
        const quizArea = document.getElementById('simQuizArea');
        const resultArea = document.getElementById('simResultArea');

        if (quizArea) quizArea.classList.add('hidden');
        if (resultArea) resultArea.classList.remove('hidden');

        // Calculate scores
        const scores = { R: 0, I: 0, A: 0, S: 0, E: 0, C: 0 };
        Object.values(simAnswers).forEach(function(answer) {
            scores[answer]++;
        });

        // Sort and get top 3
        const sorted = Object.entries(scores).sort(function(a, b) { return b[1] - a[1]; });
        const top3 = sorted.slice(0, 3).map(function(item) { return item[0]; }).join('');

        const domEl = document.getElementById('finalDominance');
        if (domEl) domEl.textContent = top3;
    }

    /**
     * Close beta test modal
     */
    window.closeBetaTest = function() {
        const modal = document.getElementById('betaModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    /**
     * Confirm publish from beta test
     */
    window.confirmPublishFromBeta = function() {
        alert('Fitur publish dari sini akan diimplementasikan.');
        closeBetaTest();
    };

})();