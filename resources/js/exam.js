// Sensor memastikan file hidup
console.log("✅ File exam.js berhasil dijalankan!");

document.addEventListener('DOMContentLoaded', () => {
    
    // Pastikan script hanya jalan di halaman ujian
    const quizArea = document.getElementById('quizArea');
    if (!quizArea) return; 

    // 1. Ambil data dari "Jembatan" Blade
    const questions = window.examQuestions || [];
    const isBeta = window.isBetaMode || false;
    const examId = window.examId || 0;
    const csrfToken = window.csrfToken || "";
    let timeRemaining = (window.examDuration || 0) * 60; // Konversi menit ke detik

    // 2. State Aplikasi
    let currentIndex = 0;
    let userAnswers = [];
    let timerInterval = null;

    // 3. Inisialisasi Ujian
    function initQuiz() {
        userAnswers = new Array(questions.length).fill(null).map(() => []);
        if (questions.length > 0) {
            renderQuestion();
            startTimer();
        } else {
            document.getElementById('qText').innerText = "Belum ada pertanyaan.";
            document.getElementById('optionsContainer').innerHTML = "";
            const timerBadge = document.getElementById('examTimer');
            if(timerBadge) timerBadge.style.display = "none";
        }
    }

    // 4. Fungsi Timer Mundur
    function startTimer() {
        const timeDisplay = document.getElementById('timeDisplay');
        if (timeRemaining <= 0) {
            const timerBadge = document.getElementById('examTimer');
            if(timerBadge) timerBadge.style.display = "none";
            return;
        }

        timerInterval = setInterval(() => {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert("⏰ Waktu habis! Jawaban Anda akan dikirim secara otomatis.");
                submitTestResult(); // Paksa submit jika waktu habis
            } else {
                const m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
                const s = (timeRemaining % 60).toString().padStart(2, '0');
                if (timeDisplay) timeDisplay.innerText = `${m}:${s}`;
                timeRemaining--;
            }
        }, 1000);
    }

    // 5. Render Tampilan Soal & Opsi
    function renderQuestion() {
        const q = questions[currentIndex];
        
        document.getElementById('qText').innerText = q.question_text;
        document.getElementById('currNum').innerText = currentIndex + 1;
        document.getElementById('totalNum').innerText = questions.length;
        
        const percent = ((currentIndex + 1) / questions.length) * 100;
        const progressBar = document.getElementById('progressBar');
        if (progressBar) progressBar.style.width = percent + "%";

        const optionsMap = [
            { code: 'R', text: q.opt_r }, { code: 'I', text: q.opt_i },
            { code: 'A', text: q.opt_a }, { code: 'S', text: q.opt_s },
            { code: 'E', text: q.opt_e }, { code: 'C', text: q.opt_c }
        ];

        const container = document.getElementById('optionsContainer');
        container.innerHTML = "";

        optionsMap.forEach(opt => {
            const isSelected = userAnswers[currentIndex].includes(opt.code);
            
            const baseClass = "p-5 border-2 rounded-2xl cursor-pointer transition-all flex items-center justify-center text-center font-medium min-h-[80px] relative group";
            const activeClass = isSelected 
                ? "bg-[#4A90E2] border-[#4A90E2] text-white shadow-md transform -translate-y-1" 
                : "bg-white border-gray-100 text-gray-600 hover:border-[#4A90E2] hover:bg-blue-50";

            const btn = document.createElement('div');
            btn.className = `${baseClass} ${activeClass}`;
            btn.innerText = opt.text;

            if(isSelected) {
                const badge = document.createElement('div');
                badge.className = "absolute -top-2 -right-2 bg-white text-[#4A90E2] w-6 h-6 rounded-full flex items-center justify-center border-2 border-[#4A90E2] shadow-sm";
                badge.innerHTML = '<i class="ph-bold ph-check text-xs"></i>';
                btn.appendChild(badge);
            }

            btn.onclick = () => toggleAnswer(opt.code);
            container.appendChild(btn);
        });

        const prevBtn = document.getElementById('btnPrev');
        const nextBtn = document.getElementById('btnNext');

        if(prevBtn) prevBtn.disabled = (currentIndex === 0);

        if(nextBtn) {
            if(currentIndex === questions.length - 1) {
                nextBtn.innerHTML = 'Selesai & Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                nextBtn.className = "px-8 py-3 bg-[#2ECC71] hover:bg-[#27ae60] text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-2";
            } else {
                nextBtn.innerHTML = 'Selanjutnya <i class="ph-bold ph-arrow-right"></i>';
                nextBtn.className = "px-8 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold shadow-lg shadow-[#4A90E2]/30 transition-all transform hover:-translate-y-1 flex items-center gap-2";
            }
        }
    }

    // 6. Logic Pilihan Ganda (Multi-Select)
    function toggleAnswer(code) {
        const currentSelected = userAnswers[currentIndex];
        if (currentSelected.includes(code)) {
            userAnswers[currentIndex] = currentSelected.filter(c => c !== code);
        } else {
            userAnswers[currentIndex].push(code);
        }
        renderQuestion();
    }

    // 7. Event Listener Navigasi
    const btnNext = document.getElementById('btnNext');
    if(btnNext) {
        btnNext.addEventListener('click', () => {
            if(userAnswers[currentIndex].length === 0) {
                alert("Mohon pilih setidaknya satu jawaban yang sesuai dengan Anda.");
                return;
            }

            if(currentIndex < questions.length - 1) {
                currentIndex++;
                renderQuestion();
            } else {
                submitTestResult();
            }
        });
    }

    const btnPrev = document.getElementById('btnPrev');
    if(btnPrev) {
        btnPrev.addEventListener('click', () => {
            if(currentIndex > 0) {
                currentIndex--;
                renderQuestion();
            }
        });
    }

    // 8. Submit, Hitung Skor, & Kirim ke Backend
    function submitTestResult() {
        if (timerInterval) clearInterval(timerInterval); // Hentikan timer

        const btn = document.getElementById('btnNext');
        if (btn) {
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
            btn.disabled = true;
        }

        let scores = { R:0, I:0, A:0, S:0, E:0, C:0 };
        
        userAnswers.forEach(answers => {
            answers.forEach(code => {
                if(scores[code] !== undefined) scores[code] += 1;
            });
        });

        let sortedCodes = Object.entries(scores)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3)
            .map(item => item[0]);
        
        const dominantCode = sortedCodes.join("");

        // LOGIKA UNTUK BETA TEST ADMIN
        if (isBeta) {
            setTimeout(() => {
                alert(`✅ Mode Beta Selesai!\nKode Dominan: ${dominantCode}\nTab akan ditutup.`);
                window.close();
            }, 1000);
            return;
        }

        // LOGIKA UNTUK SISWA SUNGGUHAN (Simpan ke DB)
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
            if(data.success) {
                alert(`✅ Tes Berhasil Disimpan!\nKode Dominan Anda: ${dominantCode}`);
                window.location.href = data.redirect_url; // Menuju halaman Laporan
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat menyimpan data. Silakan coba lagi.");
            if(btn) {
                btn.innerHTML = 'Selesai & Kirim <i class="ph-bold ph-paper-plane-right"></i>';
                btn.disabled = false;
            }
        });
    }

    // Mulai eksekusi
    initQuiz();
});