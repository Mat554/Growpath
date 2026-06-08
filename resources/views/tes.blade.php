<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Bakat - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        window.examQuestions = @json($questions ?? []);
        window.isBetaMode = @json(isset($is_beta) && $is_beta);
        window.examDuration = {{ $duration ?? 60 }};
        window.examId = {{ $exam->id ?? 0 }};
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    </script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            background: white;
            width: 100%;
            max-width: 800px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            padding: 24px 32px;
            border-bottom: 1px solid #f0f0f0;
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
        .exam-title {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4A90E2;
            font-weight: 600;
        }
        .timer {
            background: #FEF3C7;
            color: #D97706;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 2px solid #FCD34D;
        }
        .card-body {
            padding: 32px;
        }
        .question-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
 }
        .question-hint {
            color: #94a3b8;
            font-size: 0.85rem;
            margin-bottom: 24px;
        }
        .options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        @media (max-width: 600px) {
            .options { grid-template-columns: 1fr; }
            .card-body { padding: 24px; }
        }
        .option {
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            font-weight: 500;
            color: #475569;
            text-align: center;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .option:hover {
            border-color: #4A90E2;
            background: #eff6ff;
        }
        .option.selected {
            background: #4A90E2;
            border-color: #4A90E2;
            color: white;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }
        .card-footer {
            padding: 20px 32px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back {
            background: #f1f5f9;
            color: #64748b;
        }
        .btn-back:hover { background: #e2e8f0; }
        .btn-next {
            background: #4A90E2;
            color: white;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }
        .btn-next:hover { background: #357ABD; }
        .btn-next.finish {
            background: #10B981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        .btn-next.finish:hover { background: #059669; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <div class="progress-bar">
            <div id="progressBar" class="progress-fill" style="width: 0%"></div>
        </div>
        <div class="header-info">
            <div class="exam-title">
                <i class="ph-fill ph-brain"></i>
                Tes Minat Bakat (RIASEC)
            </div>
            <div class="timer">
                <i class="ph-fill ph-timer"></i>
                <span id="timeDisplay">00:00</span>
            </div>
        </div>
    </div>

    <div id="quizArea" class="card-body">
        <h2 id="qText" class="question-text">Memuat pertanyaan...</h2>
        <p class="question-hint">*Pilih semua yang sesuai dengan diri Anda</p>

        <div id="optionsContainer" class="options">
        </div>
    </div>

    <div class="card-footer">
        <button id="btnPrev" class="btn btn-back" disabled>
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </button>
        <button id="btnNext" class="btn btn-next">
            Selanjutnya <i class="ph-bold ph-arrow-right"></i>
        </button>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@vite('resources/js/student/exam.js')
</body>
</html>