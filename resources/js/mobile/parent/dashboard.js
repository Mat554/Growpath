/**
 * Mobile Parent Dashboard Script
 * Handles tab navigation, score animations, chart, and PDF download
 */

(function() {
    'use strict';

    let currentTab = 'scores';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initReportData();
    });

    /**
     * Initialize report data and animations
     */
    function initReportData() {
        if (window.reportData && window.reportData.scores) {
            animateScoreBars(window.reportData.scores);
            initChart();
        }
    }

    /**
     * Animate score bars with the given scores
     */
    function animateScoreBars(scores) {
        const riasecKeys = ['R', 'I', 'A', 'S', 'E', 'C'];
        // Calculate dynamic max score based on actual scores
        const scoresArray = [scores.R || 0, scores.I || 0, scores.A || 0, scores.S || 0, scores.E || 0, scores.C || 0];
        const maxActualScore = Math.max(...scoresArray);
        const maxDisplayScore = Math.max(10, Math.min(window.reportData.max_score || 60, Math.ceil(maxActualScore * 1.5)));

        riasecKeys.forEach(function(key) {
            const score = scores[key] || 0;
            const percentage = (score / maxDisplayScore) * 100;

            const scoreEl = document.getElementById('score' + key);
            if (scoreEl) {
                scoreEl.textContent = score;
            }

            const barEl = document.getElementById('bar' + key);
            if (barEl) {
                setTimeout(function() {
                    barEl.style.width = percentage + '%';
                }, 300);
            }
        });

        // Set dominant code
        const domCodeEl = document.getElementById('domCode');
        if (domCodeEl && window.reportData && window.reportData.dominant_code) {
            setTimeout(function() {
                domCodeEl.textContent = window.reportData.dominant_code;
            }, 500);
        }
    }

    /**
     * Initialize Chart.js radar chart
     */
    function initChart() {
        const ctx = document.getElementById('riasecChart');
        if (!ctx) return;

        const scores = window.reportData.scores;
        // Calculate dynamic max score based on actual scores
        const scoresArray = [scores.R || 0, scores.I || 0, scores.A || 0, scores.S || 0, scores.E || 0, scores.C || 0];
        const maxActualScore = Math.max(...scoresArray);
        const maxDisplayScore = Math.max(10, Math.min(window.reportData.max_score || 60, Math.ceil(maxActualScore * 1.5)));

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['R', 'I', 'A', 'S', 'E', 'C'],
                datasets: [{
                    label: 'Skor',
                    data: [scores.R || 0, scores.I || 0, scores.A || 0, scores.S || 0, scores.E || 0, scores.C || 0],
                    fill: true,
                    backgroundColor: 'rgba(74, 144, 226, 0.2)',
                    borderColor: '#4A90E2',
                    pointBackgroundColor: '#4A90E2',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4A90E2',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: maxDisplayScore,
                        ticks: {
                            stepSize: Math.max(1, Math.ceil(maxDisplayScore / 5)),
                            display: false
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        pointLabels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#64748B'
                        }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    /**
     * Switch between tabs
     */
    window.switchTab = function(tabName) {
        currentTab = tabName;

        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(function(btn) {
            btn.classList.remove('active', 'text-[#4A90E2]', 'bg-[#EBF5FF]');
            btn.classList.add('text-gray-500', 'border', 'border-gray-200');
        });

        const activeTab = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
        if (activeTab) {
            activeTab.classList.add('active', 'text-[#4A90E2]', 'bg-[#EBF5FF]');
            activeTab.classList.remove('text-gray-500', 'border', 'border-gray-200');
        }

        // Update content
        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.classList.add('hidden');
        });

        const activeContent = document.getElementById('content' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
        if (activeContent) {
            activeContent.classList.remove('hidden');
        }
    };

    /**
     * Download PDF of the report
     */
    window.downloadPDF = function() {
        const overlay = document.getElementById('pdf-overlay');
        if (overlay) overlay.style.display = 'flex';

        const reportContent = document.getElementById('report-content');
        if (!reportContent) {
            alert('Laporan tidak ditemukan.');
            return;
        }

        const options = {
            margin: 0.5,
            filename: 'laporan-growpath.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(options).from(reportContent).save().then(function() {
            if (overlay) overlay.style.display = 'none';
        }).catch(function(error) {
            console.error('PDF generation failed:', error);
            alert('Gagal membuat PDF. Silakan coba lagi.');
            if (overlay) overlay.style.display = 'none';
        });
    };

})();