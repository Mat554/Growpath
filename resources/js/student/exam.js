/**
 * Exam/Quiz JavaScript
 * Handles quiz logic, timer, multi-select answers, submission, and localStorage caching
 */

// These values are set by Blade template before this script loads
// window.examQuestions, window.isBetaMode, window.examDuration, window.examId, window.csrfToken

document.addEventListener('DOMContentLoaded', () => {
    const quizArea = document.getElementById('quizArea');
    if (!quizArea) return;

    // Get exam data from Blade (set via window variables)
    const questions = window.examQuestions || [];
    const isBeta = window.isBetaMode || false;
    const examId = window.examId || 0;
    const csrfToken = window.csrfToken || "";
    let timeRemaining = (window.examDuration || 0) * 60; // Convert minutes to seconds

    // State
    let currentIndex = 0;
    let userAnswers = [];
    let timerInterval = null;

    // LocalStorage key for this exam
    const STORAGE_KEY = `growpath_exam_${examId}`;

    // ==========================================
    // LOCALSTORAGE CACHE FUNCTIONS
    // ==========================================

    // Save progress to localStorage
    function saveProgress() {
        const cacheData = {
            userAnswers: userAnswers,
            currentIndex: currentIndex,
            timeRemaining: timeRemaining,
            savedAt: new Date().toISOString()
        };
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(cacheData));
        } catch (e) {
            console.warn('Failed to save progress to localStorage:', e);
        }
    }

    // Load progress from localStorage
    function loadProgress() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                const cacheData = JSON.parse(saved);

                // Validate the saved data matches current exam
                if (cacheData.userAnswers && cacheData.userAnswers.length === questions.length) {
                    userAnswers = cacheData.userAnswers;
                    currentIndex = cacheData.currentIndex || 0;
                    timeRemaining = cacheData.timeRemaining || timeRemaining;

                    // Show recovery message
                    showRecoveryMessage();

                    return true;
                }
            }
        } catch (e) {
            console.warn('Failed to load progress from localStorage:', e);
        }
        return false;
    }

    // Clear progress from localStorage (on successful submission)
    function clearProgress() {
        try {
            localStorage.removeItem(STORAGE_KEY);
        } catch (e) {
            console.warn('Failed to clear progress from localStorage:', e);
        }
    }

    // Show recovery message when loading saved progress
    function showRecoveryMessage() {
        const quizArea = document.getElementById('quizArea');
        if (!quizArea) return;

        // Count answered questions
        const answeredCount = userAnswers.filter(a => a && a.length > 0).length;

        // Create notification
        const notification = document.createElement('div');
        notification.id = 'cache-notification';
        notification.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; z-index: 1000; background: #10B981; color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 12px; animation: slideIn 0.3s ease-out;">
                <i class="ph-fill ph-check-circle text-xl"></i>
                <div>
                    <div style="font-weight: 600; font-size: 14px;">Jawaban Dipulihkan!</div>
                    <div style="font-size: 12px; opacity: 0.9;">${answeredCount}/${questions.length} pertanyaan telah dijawab</div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; padding: 4px; margin-left: 8px;">
                    <i class="ph ph-x"></i>
                </button>
            </div>
            <style>
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            </style>
        `;
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            const notif = document.getElementById('cache-notification');
            if (notif) notif.remove();
        }, 5000);
    }

    // ==========================================
    // QUIZ FUNCTIONS
    // ==========================================

    // Initialize quiz
    function initQuiz() {
        // Initialize empty answers array
        userAnswers = new Array(questions.length).fill(null).map(() => []);

        // Try to load from localStorage
        loadProgress();

        if (questions.length > 0) {
            renderQuestion();
            startTimer();
        } else {
            document.getElementById('qText').innerText = "Belum ada pertanyaan.";
            document.getElementById('optionsContainer').innerHTML = "";
            const timerBadge = document.getElementById('examTimer');
            if (timerBadge) timerBadge.style.display = "none";
        }
    }

    // Timer countdown
    function startTimer() {
        const timeDisplay = document.getElementById('timeDisplay');
        if (timeRemaining <= 0) {
            const timerBadge = document.getElementById('examTimer');
            if (timerBadge) timerBadge.style.display = "none";
            return;
        }

        timerInterval = setInterval(() => {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert("Waktu habis! Jawaban Anda akan dikirim secara otomatis.");
                submitTestResult();
            } else {
                const m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
                const s = (timeRemaining % 60).toString().padStart(2, '0');
                if (timeDisplay) timeDisplay.innerText = `${m}:${s}`;
                timeRemaining--;

                // Save time every 30 seconds
                if (timeRemaining % 30 === 0) {
                    saveProgress();
                }
            }
        }, 1000);
    }

    // Render current question
    function renderQuestion() {
        const q = questions[currentIndex];

        document.getElementById('qText').innerText = q.question_text;
        document.getElementById('currNum').innerText = currentIndex + 1;
        document.getElementById('totalNum').innerText = questions.length;

        const percent = ((currentIndex + 1) / questions.length) * 100;
        const progressBar = document.getElementById('progressBar');
        if (progressBar) progressBar.style.width = percent + "%";

        const optionsMap = [
            { code: 'R', text: q.opt_r },
            { code: 'I', text: q.opt_i },
            { code: 'A', text: q.opt_a },
            { code: 'S', text: q.opt_s },
            { code: 'E', text: q.opt_e },
            { code: 'C', text: q.opt_c }
        ];

        const container = document.getElementById('optionsContainer');
        container.innerHTML = "";

        optionsMap.forEach(opt => {
            const isSelected = userAnswers[currentIndex] && userAnswers[currentIndex].includes(opt.code);

            const baseClass = "option";
            const activeClass = isSelected ? "option selected" : "";

            const btn = document.createElement('div');
            btn.className = `${baseClass} ${activeClass}`.trim();
            btn.innerText = opt.text;

            if (isSelected) {
                const badge = document.createElement('div');
                badge.className = "absolute -top-2 -right-2 bg-white text-[#4A90E2] w-6 h-6 rounded-full flex items-center justify-center border-2 border-[#4A90E2] shadow-sm";
                badge.innerHTML = '<i class="ph-bold ph-check text-xs"></i>';
                btn.appendChild(badge);
            }

            btn.onclick = () => toggleAnswer(opt.code);
            container.appendChild(btn);
        });

        // Navigation buttons
        const prevBtn = document.getElementById('btnPrev');
        const nextBtn = document.getElementById('btnNext');

        if (prevBtn) prevBtn.disabled = (currentIndex === 0);

        if (nextBtn) {
            if (currentIndex === questions.length - 1) {
                nextBtn.innerHTML = 'Selesai & Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                nextBtn.className = "btn btn-next finish";
            } else {
                nextBtn.innerHTML = 'Selanjutnya <i class="ph-bold ph-arrow-right"></i>';
                nextBtn.className = "btn btn-next";
            }
        }

        // Update progress indicator
        updateProgressIndicator();
    }

    // Update progress indicator showing answered questions
    function updateProgressIndicator() {
        const answeredCount = userAnswers.filter(a => a && a.length > 0).length;
        const progressText = document.getElementById('progressText');
        if (progressText) {
            progressText.innerText = `${answeredCount}/${questions.length} dijawab`;
        }
    }

    // Toggle answer selection
    function toggleAnswer(code) {
        if (!userAnswers[currentIndex]) {
            userAnswers[currentIndex] = [];
        }

        const currentSelected = userAnswers[currentIndex];
        if (currentSelected.includes(code)) {
            userAnswers[currentIndex] = currentSelected.filter(c => c !== code);
        } else {
            userAnswers[currentIndex].push(code);
        }

        // Save to localStorage immediately when answer changes
        saveProgress();

        renderQuestion();
    }

    // Calculate scores and submit
    function submitTestResult() {
        if (timerInterval) clearInterval(timerInterval);

        const btn = document.getElementById('btnNext');
        if (btn) {
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        // Calculate scores
        let scores = { R: 0, I: 0, A: 0, S: 0, E: 0, C: 0 };

        userAnswers.forEach(answers => {
            if (answers) {
                answers.forEach(code => {
                    if (scores[code] !== undefined) scores[code] += 1;
                });
            }
        });

        // Get top 3 dominant codes
        let sortedCodes = Object.entries(scores)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3)
            .map(item => item[0]);

        const dominantCode = sortedCodes.join("");

        // Beta test mode (no submission)
        if (isBeta) {
            clearProgress();
            setTimeout(() => {
                alert(`Mode Beta Selesai!\nKode Dominan: ${dominantCode}\nTab akan ditutup.`);
                window.close();
            }, 1000);
            return;
        }

        // Submit to backend
        fetch(`/exam/${examId}/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                scores: scores,
                dominant_code: dominantCode
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Clear localStorage cache on successful submission
                clearProgress();

                // Show success message and redirect
                const quizArea = document.getElementById('quizArea');
                if (quizArea) {
                    quizArea.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 4rem; margin-bottom: 20px;">&#10004;</div>
                            <h2 style="font-size: 1.5rem; color: #1e293b; margin-bottom: 10px;">Tes Selesai!</h2>
                            <p style="color: #64748b; margin-bottom: 30px;">Jawaban Anda telah disimpan.</p>
                            <a href="${data.redirect_url}" style="display: inline-block; padding: 14px 28px; background: #4A90E2; color: white; border-radius: 10px; font-weight: 600; text-decoration: none;">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    `;
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat menyimpan data. Silakan coba lagi.");
            if (btn) {
                btn.innerHTML = 'Selesai & Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                btn.disabled = false;
            }
        });
    }

    // Event listeners
    const btnNext = document.getElementById('btnNext');
    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (userAnswers[currentIndex] && userAnswers[currentIndex].length === 0) {
                alert("Mohon pilih setidaknya satu jawaban.");
                return;
            }

            if (currentIndex < questions.length - 1) {
                currentIndex++;
                saveProgress();
                renderQuestion();
            } else {
                submitTestResult();
            }
        });
    }

    const btnPrev = document.getElementById('btnPrev');
    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                saveProgress();
                renderQuestion();
            }
        });
    }

    // Warn before leaving page
    window.addEventListener('beforeunload', (e) => {
        if (questions.length > 0 && !isBeta) {
            saveProgress();
            e.preventDefault();
            e.returnValue = 'Anda yakin ingin meninggalkan halaman ini? Progress Anda akan disimpan.';
        }
    });

    // Save on visibility change
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden' && questions.length > 0) {
            saveProgress();
        }
    });

    // Start quiz
    initQuiz();
});
