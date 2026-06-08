/**
 * Report JavaScript
 * Handles report rendering, charts, and PDF export
 */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.reportData === 'undefined') return;

    const mockResult = window.reportData;

    // Run render after short delay
    setTimeout(() => {
        renderReport();
    }, 100);
});

// Make these functions globally accessible
window.renderReport = function() {
    const data = window.reportData;
    if (!data) return;

    const loading = document.getElementById('loading');
    if (loading) loading.style.display = 'none';

    // Format Date
    const dateObj = new Date(data.created_at);
    const testDate = document.getElementById('testDate');
    if (testDate) {
        testDate.innerText = dateObj.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
    }

    // Show Dominant Code
    const domCode = document.getElementById('domCode');
    if (domCode) domCode.innerText = data.dominant_code;

    // Dynamic max score based on exam (60 questions = max 60 points, etc.)
    const maxDisplayScore = data.max_score || 60;

    // Update Progress Bars with dynamic max
    window.updateBar('barR', 'scoreR', data.scores.R, maxDisplayScore);
    window.updateBar('barI', 'scoreI', data.scores.I, maxDisplayScore);
    window.updateBar('barA', 'scoreA', data.scores.A, maxDisplayScore);
    window.updateBar('barS', 'scoreS', data.scores.S, maxDisplayScore);
    window.updateBar('barE', 'scoreE', data.scores.E, maxDisplayScore);
    window.updateBar('barC', 'scoreC', data.scores.C, maxDisplayScore);

    // Render Chart.js with dynamic Y-axis max
    const canvas = document.getElementById('riasecChart');
    if (canvas && typeof Chart !== 'undefined') {
        const ctx = canvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(74, 144, 226, 0.5)');
        gradient.addColorStop(1, 'rgba(74, 144, 226, 0.0)');

        // Chart colors for each RIASEC dimension
        const chartColors = {
            R: '#EF4444', // red
            I: '#3B82F6', // blue
            A: '#EAB308', // yellow
            S: '#22C55E', // green
            E: '#A855F7', // purple
            C: '#6B7280'  // gray
        };

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
                    pointRadius: 6,
                    pointHoverRadius: 8,
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
                            stepSize: Math.ceil(maxDisplayScore / 6),
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
};

window.updateBar = function(barId, textId, score, max) {
    let percentage = (score / max) * 100;
    if (percentage > 100) percentage = 100;

    const bar = document.getElementById(barId);
    const text = document.getElementById(textId);

    if (text) text.innerText = `${score} Poin`;
    if (bar) {
        setTimeout(() => {
            bar.style.width = percentage + "%";
        }, 100);
    }
};

window.downloadPDF = function() {
    const element = document.getElementById('report-content');
    const buttons = document.getElementById('action-buttons');
    const data = window.reportData;

    if (!element || !data) return;

    if (buttons) buttons.style.display = 'none';

    const opt = {
        margin: [0.5, 0.5, 0.5, 0.5],
        filename: 'Laporan_RIASEC_' + data.dominant_code + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    if (typeof html2pdf !== 'undefined') {
        html2pdf().set(opt).from(element).save().then(() => {
            if (buttons) buttons.style.display = 'flex';
        });
    }
};