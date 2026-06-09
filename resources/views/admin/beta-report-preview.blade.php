<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil - Beta Test Preview - Growpath</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.reportData = {
            created_at: "{{ now() }}",
            scores: {
                R: {{ $scores['R'] ?? 0 }},
                I: {{ $scores['I'] ?? 0 }},
                A: {{ $scores['A'] ?? 0 }},
                S: {{ $scores['S'] ?? 0 }},
                E: {{ $scores['E'] ?? 0 }},
                C: {{ $scores['C'] ?? 0 }}
            },
            dominant_code: "{{ $dominantCode }}",
            max_score: {{ $maxScore ?? 60 }}
        };
    </script>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #F4F7F6;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .back-btn:hover { background: #4b5563; }
        .preview-badge {
            background: #FF9F43;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #4A90E2, #6DD5FA);
            padding: 30px 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .card-header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .card-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        .header-right {
            text-align: right;
        }
        .header-name {
            font-size: 1.2rem;
            font-weight: 700;
        }
        .header-date {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        .card-body {
            padding: 30px 40px;
        }
        .dominant-box {
            background: #EBF5FF;
            border-left: 6px solid #4A90E2;
            padding: 24px;
            border-radius: 0 12px 12px 0;
            margin-bottom: 30px;
            display: flex;
            gap: 30px;
            align-items: center;
        }
        .dominant-code {
            text-align: center;
            min-width: 100px;
        }
        .dominant-label {
            font-size: 0.75rem;
            color: #4A90E2;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .dominant-value {
            font-size: 3rem;
            font-weight: 800;
            color: #4A90E2;
            letter-spacing: 4px;
        }
        .dominant-info {
            flex: 1;
        }
        .dominant-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .dominant-desc {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .section-title {
            font-size: 1.1rem;
            font-semibold;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .score-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        @media (max-width: 600px) {
            .score-grid { grid-template-columns: 1fr; }
            .card-body { padding: 24px; }
            .dominant-box { flex-direction: column; text-align: center; }
            .card-header-content { flex-direction: column; gap: 16px; text-align: center; }
            .header-right { text-align: center; }
        }
        .score-item {
            margin-bottom: 8px;
        }
        .score-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }
        .score-label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #374151;
        }
        .score-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .score-value {
            font-weight: 700;
            color: #1f2937;
        }
        .score-bar {
            width: 100%;
            height: 8px;
            background: #f3f4f6;
            border-radius: 10px;
            overflow: hidden;
        }
        .score-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }
        .chart-container {
            width: 100%;
            height: 320px;
            background: white;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
        }
        .info-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        .info-card.orange {
            background: #fff7ed;
            border-color: #fed7aa;
        }
        .info-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-list {
            list-style: disc;
            padding-left: 20px;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .info-list li {
            margin-bottom: 8px;
        }
        .actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }
        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e5e7eb;
        }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-primary {
            background: #4A90E2;
            color: white;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }
        .btn-primary:hover { background: #357ABD; }
        .btn-success {
            background: #10B981;
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        .btn-success:hover { background: #059669; }

        /* Animation */
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; opacity: 0; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .bar-fill { transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1); width: 0%; }
    </style>
</head>
<body>

<div class="container">
    <a href="/admin/beta-test-preview" class="back-btn">
        <i class="ph-bold ph-arrow-left"></i> Kembali ke Beta Test
    </a>

    <div class="preview-badge">
        <i class="ph-fill ph-eye"></i> Preview: Bagaimana Orang Tua Melihat Laporan
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-header-content">
                <div>
                    <h1>Laporan Hasil Analisis</h1>
                    <p>Tes Minat Bakat (RIASEC)</p>
                </div>
                <div class="header-right">
                    <div class="header-name">Beta Test</div>
                    <div class="header-date" id="testDate">-</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="animate-fade-in" style="animation-delay: 0.2s;">
                <div class="dominant-box">
                    <div class="dominant-code">
                        <div class="dominant-label">Kode Dominan</div>
                        <div class="dominant-value" id="domCode">{{ $dominantCode }}</div>
                    </div>
                    <div class="dominant-info">
                        <div class="dominant-title">{{ $aiData['judul'] ?? 'Profil RIASEC' }}</div>
                        <div class="dominant-desc">{{ $aiData['deskripsi'] ?? '' }}</div>
                    </div>
                </div>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.4s;">
                <h3 class="section-title">
                    <i class="ph-fill ph-chart-bar text-[#4A90E2]"></i> Rincian Skor Potensi
                </h3>

                <div class="score-grid">
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #ef4444;"></span> Realistic</span>
                            <span class="score-value" id="scoreR">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barR" style="background: #ef4444;"></div>
                        </div>
                    </div>
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #3b82f6;"></span> Investigative</span>
                            <span class="score-value" id="scoreI">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barI" style="background: #3b82f6;"></div>
                        </div>
                    </div>
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #eab308;"></span> Artistic</span>
                            <span class="score-value" id="scoreA">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barA" style="background: #eab308;"></div>
                        </div>
                    </div>
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #22c55e;"></span> Social</span>
                            <span class="score-value" id="scoreS">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barS" style="background: #22c55e;"></div>
                        </div>
                    </div>
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #a855f7;"></span> Enterprising</span>
                            <span class="score-value" id="scoreE">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barE" style="background: #a855f7;"></div>
                        </div>
                    </div>
                    <div class="score-item">
                        <div class="score-header">
                            <span class="score-label"><span class="score-dot" style="background: #6b7280;"></span> Conventional</span>
                            <span class="score-value" id="scoreC">0 Poin</span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill bar-fill" id="barC" style="background: #6b7280;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.5s;">
                <h3 class="section-title">
                    <i class="ph-fill ph-chart-line text-[#4A90E2]"></i> Analisis Visual
                </h3>
                <div class="chart-container">
                    <canvas id="riasecChart"></canvas>
                </div>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.6s;">
                <h3 class="section-title">
                    <i class="ph-fill ph-student text-[#4A90E2]"></i> Rekomendasi Jurusan
                </h3>
                <div class="info-card" style="margin-bottom: 30px;">
                    <ul class="info-list">
                        @if(isset($aiData['jurusan']))
                            @foreach($aiData['jurusan'] as $jurusan)
                                <li>{{ $jurusan }}</li>
                            @endforeach
                        @else
                            <li>Gagal memuat rekomendasi jurusan dari AI.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="animate-fade-in" style="animation-delay: 0.7s;">
                <div class="info-grid">
                    <div>
                        <h3 class="section-title">
                            <i class="ph-fill ph-buildings text-[#4A90E2]"></i> Rekomendasi Kampus
                        </h3>
                        <div class="info-card" style="height: 100%;">
                            <ul class="info-list">
                                @if(isset($aiData['kampus']))
                                    @foreach($aiData['kampus'] as $kampus)
                                        <li>{{ $kampus }}</li>
                                    @endforeach
                                @else
                                    <li class="text-gray-500 italic">Belum ada rekomendasi kampus.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h3 class="section-title">
                            <i class="ph-fill ph-lightbulb text-[#FF9F43]"></i> Tips Belajar
                        </h3>
                        <div class="info-card orange" style="height: 100%;">
                            <ul class="info-list">
                                @if(isset($aiData['tips']))
                                    @foreach($aiData['tips'] as $tips)
                                        <li>{{ $tips }}</li>
                                    @endforeach
                                @else
                                    <li class="text-gray-500 italic">Belum ada tips belajar.</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions animate-fade-in" style="animation-delay: 0.8s;">
                <a href="/admin/beta-test-preview" class="btn btn-secondary">
                    <i class="ph-bold ph-arrow-left"></i> Ulangi Beta Test
                </a>
                <a href="/admin-dashboard" class="btn btn-success">
                    <i class="ph-bold ph-check"></i> Selesai
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Render report similar to parent report
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            renderBetaReport();
        }, 100);
    });

    function renderBetaReport() {
        const data = window.reportData;
        if (!data) return;

        // Format Date
        const dateObj = new Date();
        const testDate = document.getElementById('testDate');
        if (testDate) {
            testDate.innerText = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
        }

        // Show Dominant Code
        const domCode = document.getElementById('domCode');
        if (domCode) domCode.innerText = data.dominant_code;

        // Calculate dynamic max score based on actual scores
        const scores = [data.scores.R, data.scores.I, data.scores.A, data.scores.S, data.scores.E, data.scores.C];
        const maxActualScore = Math.max(...scores);
        const maxDisplayScore = Math.max(10, Math.min(data.max_score || 60, Math.ceil(maxActualScore * 1.5)));

        // Update Progress Bars
        updateBar('barR', 'scoreR', data.scores.R, maxDisplayScore);
        updateBar('barI', 'scoreI', data.scores.I, maxDisplayScore);
        updateBar('barA', 'scoreA', data.scores.A, maxDisplayScore);
        updateBar('barS', 'scoreS', data.scores.S, maxDisplayScore);
        updateBar('barE', 'scoreE', data.scores.E, maxDisplayScore);
        updateBar('barC', 'scoreC', data.scores.C, maxDisplayScore);

        // Render Line Chart (same as parent report)
        const canvas = document.getElementById('riasecChart');
        if (canvas && typeof Chart !== 'undefined') {
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(74, 144, 226, 0.5)');
            gradient.addColorStop(1, 'rgba(74, 144, 226, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
                    datasets: [{
                        label: 'Poin',
                        data: [data.scores.R, data.scores.I, data.scores.A, data.scores.S, data.scores.E, data.scores.C],
                        borderColor: '#4A90E2',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4A90E2',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxDisplayScore,
                            grid: { color: '#f3f4f6', borderDash: [5, 5] },
                            border: { display: false },
                            ticks: {
                                stepSize: Math.max(1, Math.ceil(maxDisplayScore / 6)),
                                font: { family: "'Poppins', sans-serif" },
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: { font: { family: "'Poppins', sans-serif", weight: '500' }, color: '#6b7280' }
                        }
                    }
                }
            });
        }
    }

    function updateBar(barId, textId, score, max) {
        let percentage = (score / max) * 100;
        if (percentage > 100) percentage = 100;

        const bar = document.getElementById(barId);
        const text = document.getElementById(textId);

        if (text) text.innerText = score + ' Poin';
        if (bar) {
            setTimeout(() => {
                bar.style.width = percentage + '%';
            }, 100);
        }
    }
</script>

</body>
</html>