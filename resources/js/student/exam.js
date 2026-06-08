/**
 * Exam/Quiz JavaScript
 * Handles quiz logic, timer, multi-select answers, and submission
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

    // Initialize quiz
    function initQuiz() {
        userAnswers = new Array(questions.length).fill(null).map(() => []);
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
                alert("⏰ Waktu habis! Jawaban Anda akan dikirim secara otomatis.");
                submitTestResult();
            } else {
                const m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
                const s = (timeRemaining % 60).toString().padStart(2, '0');
                if (timeDisplay) timeDisplay.innerText = `${m}:${s}`;
                timeRemaining--;
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
            const isSelected = userAnswers[currentIndex].includes(opt.code);

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
                nextBtn.innerHTML = 'Selesai& Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                nextBtn.className = "btn btn-next finish";
            } else {
                nextBtn.innerHTML = 'Selanjutnya <i class="ph-bold ph-arrow-right"></i>';
                nextBtn.className = "btn btn-next";
            }
        }
    }

    // Toggle answer selection
    function toggleAnswer(code) {
        const currentSelected = userAnswers[currentIndex];
        if (currentSelected.includes(code)) {
            userAnswers[currentIndex] = currentSelected.filter(c => c !== code);
        } else {
            userAnswers[currentIndex].push(code);
        }
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
            answers.forEach(code => {
                if (scores[code] !== undefined) scores[code] += 1;
            });
        });

        // Get top 3 dominant codes
        let sortedCodes = Object.entries(scores)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3)
            .map(item => item[0]);

        const dominantCode = sortedCodes.join("");

        // Beta test mode (no submission)
        if (isBeta) {
            setTimeout(() => {
                alert(`✅ Mode Beta Selesai!\nKode Dominan: ${dominantCode}\nTab akan ditutup.`);
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
                // Show simple success message and redirect
                const quizArea = document.getElementById('quizArea');
                if (quizArea) {
                    quizArea.innerHTML = `
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 4rem; margin-bottom: 20px;">✅</div>
                            <h2 style="font-size: 1.5rem; color: #1e293b; margin-bottom: 10px;">Tes Selesai!</h2>
                            <p style="color: #64748b; margin-bottom: 30px;">Jawaban Anda telah disimpan dan akan direview oleh admin.</p>
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
            if (userAnswers[currentIndex].length === 0) {
                alert("Mohon pilih setidaknya satu jawaban yang sesuai dengan Anda.");
                return;
            }

            if (currentIndex < questions.length - 1) {
                currentIndex++;
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
                renderQuestion();
            }
        });
    }

    // Start quiz
    initQuiz();
});