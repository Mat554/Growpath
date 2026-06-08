<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beta Test Preview - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        window.examQuestions = @json($questions ?? []);
        window.examDuration = {{ $duration ?? 60 }};
        window.examTitle = "{{ $cardTitle ?? 'Beta Test Preview' }}";
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .header h1 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }
        .header .badge {
            background: #FF9F43;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #4A90E2;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .back-btn:hover { background: #357ABD; }
        .card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #eee;
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .progress-fill {
            height: 100%;
            background: #4A90E2;
            transition: width 0.3s ease;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }
        .timer {
            color: #e74c3c;
            font-weight: 600;
        }
        .card-body {
            padding: 24px;
        }
        .question-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: #222;
            margin-bottom: 6px;
        }
        .question-hint {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .option {
            padding: 14px 18px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .option:hover {
            border-color: #4A90E2;
            background: #f0f7ff;
        }
        .option.selected {
            border-color: #4A90E2;
            background: #4A90E2;
            color: white;
        }
        .option .check {
            width: 22px;
            height: 22px;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            flex-shrink: 0;
        }
        .option.selected .check {
            background: white;
            border-color: white;
            color: #4A90E2;
        }
        .card-footer {
            padding: 16px 24px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-back {
            background: #f0f0f0;
            color: #666;
        }
        .btn-back:hover { background: #e0e0e0; }
        .btn-next {
            background: #4A90E2;
            color: white;
        }
        .btn-next:hover { background: #357ABD; }
        .btn-next.finish {
            background: #27ae60;
        }
        .btn-next.finish:hover { background: #219a52; }

        /* Results */
        .results {
            display: none;
            text-align: center;
            padding: 40px 24px;
        }
        .results.active { display: block; }
        .quiz-section.hidden { display: none; }
        .results h2 {
            font-size: 1.5rem;
            margin-bottom: 8px;
            color: #222;
        }
        .results p {
            color: #666;
            margin-bottom: 30px;
        }
        .scores {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .score-item {
            background: #f8f9fa;
            padding: 12px 20px;
            border-radius: 10px;
            min-width: 60px;
        }
        .score-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4A90E2;
        }
        .score-label {
            font-size: 0.75rem;
            color: #888;
        }
        .dominant {
            background: #4A90E2;
            color: white;
            padding: 20px 40px;
            border-radius: 12px;
            display: inline-block;
            margin-bottom: 30px;
        }
        .dominant-label {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 4px;
        }
        .dominant-value {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 6px;
        }
        .all-answers {
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .all-answers h3 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 12px;
        }
        .answer-item {
            padding: 10px 14px;
            background: white;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .answer-item:last-child { margin-bottom: 0; }
        .answer-q {
            font-weight: 600;
            color: #333;
            margin-bottom: 6px;
        }
        .answer-opts {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .answer-opt {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .answer-opt.selected {
            background: #4A90E2;
            color: white;
        }
        .answer-opt.not-selected {
            background: #f0f0f0;
            color: #999;
        }
        .results-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .btn-retry {
            background: #f0f0f0;
            color: #666;
        }
        .btn-retry:hover { background: #e0e0e0; }
        .btn-done {
            background: #27ae60;
            color: white;
        }
        .btn-done:hover { background: #219a52; }
    </style>
</head>
<body>

<div class="container">
    <a href="/admin-dashboard" class="back-btn">
        <i class="ph-bold ph-arrow-left"></i> Kembali ke Admin
    </a>

    <div class="header">
        <h1><i class="ph-fill ph-flask"></i> Beta Test Preview</h1>
        <span class="badge">{{ $cardTitle ?? 'Tes Minat Bakat' }}</span>
    </div>

    <div class="card">
       <div id="quizSection" class="quiz-section">
            <div class="card-header">
                <div class="progress-bar">
                    <div id="progressFill" class="progress-fill" style="width: 0%"></div>
                </div>
                <div class="header-info">
                    <span>Soal <span id="currNum">1</span> dari<span id="totalNum">0</span></span>
                    <span class="timer"><i class="ph-fill ph-timer"></i> <span id="timerDisplay">00:00</span></span>
                </div>
            </div>

            <div class="card-body">
                <h2 id="questionText" class="question-text">Memuat...</h2>
                <p class="question-hint">*Pilih semua yang sesuai dengan Anda</p>

                <div id="optionsContainer" class="options">
                </div>
            </div>

            <div class="card-footer">
                <button id="btnPrev" class="btn btn-back" onclick="prevQuestion()">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </button>
                <button id="btnNext" class="btn btn-next" onclick="nextQuestion()">
                    Selanjutnya <i class="ph-bold ph-arrow-right"></i>
                </button>
            </div>
        </div>

        <div id="resultsSection" class="results">
            <h2>Simulasi Selesai!</h2>
            <p>Profil Minat RIASEC Anda:</p>

            <div class="scores">
                <div class="score-item"><div class="score-value" id="scoreR">0</div><div class="score-label">R</div></div>
                <div class="score-item"><div class="score-value" id="scoreI">0</div><div class="score-label">I</div></div>
                <div class="score-item"><div class="score-value" id="scoreA">0</div><div class="score-label">A</div></div>
                <div class="score-item"><div class="score-value" id="scoreS">0</div><div class="score-label">S</div></div>
                <div class="score-item"><div class="score-value" id="scoreE">0</div><div class="score-label">E</div></div>
                <div class="score-item"><div class="score-value" id="scoreC">0</div><div class="score-label">C</div></div>
            </div>

            <div class="dominant">
                <div class="dominant-label">Kode Kepribadian (Top 3)</div>
                <div class="dominant-value" id="finalCode">---</div>
            </div>

            <div class="all-answers">
                <h3>Jawaban Anda:</h3>
                <div id="allAnswersList"></div>
            </div>

            <div class="results-actions">
                <button class="btn btn-retry" onclick="restartTest()">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Ulangi
                </button>
                <button id="btnViewReport" class="btn btn-done" onclick="viewParentReport()">
                    <i class="ph-bold ph-eye"></i> Lihat Preview Laporan Orang Tua
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const questions = window.examQuestions || [];

    const optionsMap = [
        { code: 'R', key: 'opt_r' },
        { code: 'I', key: 'opt_i' },
        { code: 'A', key: 'opt_a' },
        { code: 'S', key: 'opt_s' },
        { code: 'E', key: 'opt_e' },
        { code: 'C', key: 'opt_c' }
    ];

    // Helper to get option text from question
    function getOptionText(q, opt) {
        return q[opt.key] || '';
    }

    let currentIndex = 0;
    let userAnswers = new Array(questions.length).fill(null).map(() => []);
    let timeRemaining = (window.examDuration || 60) * 60;
    let timerInterval = null;

    function init() {
        document.getElementById('totalNum').innerText = questions.length;
        if (questions.length > 0) {
            renderQuestion();
            startTimer();
        }
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert("Waktu habis!");
                finishTest();
                return;
            }
            const m = Math.floor(timeRemaining / 60).toString().padStart(2, '0');
            const s = (timeRemaining % 60).toString().padStart(2, '0');
            document.getElementById('timerDisplay').innerText = `${m}:${s}`;
            timeRemaining--;
        }, 1000);
    }

    function renderQuestion() {
        const q = questions[currentIndex];
        document.getElementById('questionText').innerText = q.question_text;
        document.getElementById('currNum').innerText = currentIndex + 1;
        document.getElementById('progressFill').style.width = `${((currentIndex + 1) / questions.length) * 100}%`;

        const container = document.getElementById('optionsContainer');
        container.innerHTML = '';

        optionsMap.forEach(opt => {
            const isSelected = userAnswers[currentIndex].includes(opt.code);
            const div = document.createElement('div');
            div.className = `option ${isSelected ? 'selected' : ''}`;
            div.innerHTML = `<span class="check">${isSelected ? '✓' : ''}</span> ${getOptionText(q, opt)}`;
            div.onclick = () => toggleAnswer(opt.code);
            container.appendChild(div);
        });

        document.getElementById('btnPrev').style.visibility = currentIndex === 0 ? 'hidden' : 'visible';
        const btnNext = document.getElementById('btnNext');
        if (currentIndex === questions.length - 1) {
            btnNext.innerHTML = 'Selesai <i class="ph-bold ph-check"></i>';
            btnNext.classList.add('finish');
        } else {
            btnNext.innerHTML = 'Selanjutnya <i class="ph-bold ph-arrow-right"></i>';
            btnNext.classList.remove('finish');
        }
    }

    function toggleAnswer(code) {
        const idx = userAnswers[currentIndex].indexOf(code);
        if (idx > -1) {
            userAnswers[currentIndex].splice(idx, 1);
        } else {
            userAnswers[currentIndex].push(code);
        }
        renderQuestion();
    }

    function prevQuestion() {
        if (currentIndex > 0) {
            currentIndex--;
            renderQuestion();
        }
    }

    function nextQuestion() {
        if (userAnswers[currentIndex].length === 0) {
            alert('Pilih setidaknya satu jawaban.');
            return;
        }
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            finishTest();
        }
    }

    function finishTest() {
        clearInterval(timerInterval);

        let scores = { R: 0, I: 0, A: 0, S: 0, E: 0, C: 0 };
        userAnswers.forEach(answers => {
            answers.forEach(code => {
                if (scores[code] !== undefined) scores[code]++;
            });
        });

        document.getElementById('scoreR').innerText = scores.R;
        document.getElementById('scoreI').innerText = scores.I;
        document.getElementById('scoreA').innerText = scores.A;
        document.getElementById('scoreS').innerText = scores.S;
        document.getElementById('scoreE').innerText = scores.E;
        document.getElementById('scoreC').innerText = scores.C;

        let sortedCodes = Object.entries(scores)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3)
            .map(item => item[0]);
        document.getElementById('finalCode').innerText = sortedCodes.join('');

        // Render all answers
        const allAnswersList = document.getElementById('allAnswersList');
        allAnswersList.innerHTML = '';
        questions.forEach((q, i) => {
            const div = document.createElement('div');
            div.className = 'answer-item';
            let optsHtml = '<div class="answer-opts">';
            optionsMap.forEach(opt => {
                const isSelected = userAnswers[i].includes(opt.code);
                optsHtml += `<span class="answer-opt ${isSelected ? 'selected' : 'not-selected'}">${opt.code}: ${getOptionText(q, opt)}</span>`;
            });
            optsHtml += '</div>';
            div.innerHTML = `<div class="answer-q">${i + 1}. ${q.question_text}</div>${optsHtml}`;
            allAnswersList.appendChild(div);
        });

        document.getElementById('quizSection').classList.add('hidden');
        document.getElementById('resultsSection').classList.add('active');
    }

    function restartTest() {
        currentIndex = 0;
        userAnswers = new Array(questions.length).fill(null).map(() => []);
        timeRemaining = (window.examDuration || 60) * 60;
        document.getElementById('quizSection').classList.remove('hidden');
        document.getElementById('resultsSection').classList.remove('active');
        renderQuestion();
        startTimer();
    }

    function viewParentReport() {
        // Calculate scores
        let scores = { R: 0, I: 0, A: 0, S: 0, E: 0, C: 0 };
        userAnswers.forEach(answers => {
            answers.forEach(code => {
                if (scores[code] !== undefined) scores[code]++;
            });
        });

        // Get dominant code
        let sortedCodes = Object.entries(scores)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 3)
            .map(item => item[0]);
        const dominantCode = sortedCodes.join('');

        const btn = document.getElementById('btnViewReport');
        if (btn) {
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Memuat...';
            btn.disabled = true;
        }

        // Store results in session and redirect
        fetch('/admin/beta-store-results', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                scores: scores,
                dominant_code: dominantCode
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                window.location.href = data.redirect_url;
            } else {
                alert('Terjadi kesalahan.');
                if (btn) {
                    btn.innerHTML = '<i class="ph-bold ph-eye"></i> Lihat Preview Laporan Orang Tua';
                    btn.disabled = false;
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan.');
            if (btn) {
                btn.innerHTML = '<i class="ph-bold ph-eye"></i> Lihat Preview Laporan Orang Tua';
                btn.disabled = false;
            }
        });
    }

    init();
</script>
</body>
</html>