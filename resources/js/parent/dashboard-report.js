/**
 * Parent Dashboard Report JavaScript
 * Handles report rendering, chart, and PDF download for ortu dashboard
 * Data is injected from Blade via window.reportData
 */

document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.reportData !== 'undefined') {
        renderReport();
    }
});

function renderReport() {
    setTimeout(() => {
        const loading = document.getElementById('loading');
        if (loading) loading.style.display = 'none';

        const data = window.reportData;
        if (!data) return;

        // Format date
        const dateObj = new Date(data.created_at);
        const testDateEl = document.getElementById('testDate');
        if (testDateEl) {
            testDateEl.innerText = dateObj.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });
        }

        // Show dominant code
        const domCodeEl = document.getElementById('domCode');
        if (domCodeEl) domCodeEl.innerText = data.dominant_code;

        // Calculate dynamic max score based on actual scores
        const scores = [data.scores.R, data.scores.I, data.scores.A, data.scores.S, data.scores.E, data.scores.C];
        const maxActualScore = Math.max(...scores);
        const maxDisplayScore = Math.max(10, Math.min(data.max_score || 60, Math.ceil(maxActualScore * 1.5)));

        // Update score bars
        updateBar('barR', 'scoreR', data.scores.R, maxDisplayScore);
        updateBar('barI', 'scoreI', data.scores.I, maxDisplayScore);
        updateBar('barA', 'scoreA', data.scores.A, maxDisplayScore);
        updateBar('barS', 'scoreS', data.scores.S, maxDisplayScore);
        updateBar('barE', 'scoreE', data.scores.E, maxDisplayScore);
        updateBar('barC', 'scoreC', data.scores.C, maxDisplayScore);

        // Render Chart.js
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
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    animation: { onComplete: () => { window._chartReady = true; } },
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
    }, 500);
}

function updateBar(barId, textId, score, max) {
    let percentage = (score / max) * 100;
    if (percentage > 100) percentage = 100;
    const bar = document.getElementById(barId);
    const text = document.getElementById(textId);
    if (text) text.innerText = `${score} Poin`;
    if (bar) { setTimeout(() => { bar.style.width = percentage + "%"; }, 100); }
}

window.downloadPDF = async function() {
    const overlay = document.getElementById('pdf-overlay');
    const overlayTitle = document.getElementById('pdf-overlay-title');
    const overlaySubtitle = document.getElementById('pdf-overlay-subtitle');
    const progressBar = document.getElementById('pdf-progress-bar');
    const data = window.reportData;

    function setProgress(pct, title, subtitle) {
        if (progressBar) progressBar.style.width = pct + '%';
        if (overlayTitle) overlayTitle.textContent = title || '';
        if (overlaySubtitle) overlaySubtitle.textContent = subtitle || '';
    }

    overlay.classList.add('active');
    setProgress(10, 'Menyiapkan...', 'Mengambil data');

    const buttons = document.getElementById('action-buttons');
    if (buttons) buttons.style.display = 'none';

    setProgress(25, 'Memproses...', 'Membangun dokumen');

    // Get report HTML
    const reportContent = document.getElementById('report-content');
    if (!reportContent) {
        alert('Laporan tidak ditemukan.');
        if (buttons) buttons.style.display = 'flex';
        overlay.classList.remove('active');
        return;
    }
    const reportHTML = reportContent.innerHTML;

    // Create iframe
    const iframe = document.createElement('iframe');
    iframe.style.cssText = 'position:fixed;top:0;left:0;width:800px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-9999;';
    iframe.setAttribute('id', 'pdf-iframe');
    document.body.appendChild(iframe);

    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write(`<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"><\/script>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Poppins',sans-serif;background:white;color:#333}
</style>
</head>
<body>${reportHTML}
<script>
(function(){
    var el = document.body;
    var data = window.parent.reportData || {};
    var scores = data.scores || {R:0,I:0,A:0,S:0,E:0,C:0};
    var arr = [scores.R,scores.I,scores.A,scores.S,scores.E,scores.C];
    var maxActual = Math.max.apply(null, arr);
    var maxScore = Math.max(10, Math.min(data.max_score||60, Math.ceil(maxActual*1.5)));

    // Update bars
    var map = {R:'barR',I:'barI',A:'barA',S:'barS',E:'barE',C:'barC'};
    for(var k in map){
        var bar = document.getElementById(map[k]);
        if(bar) bar.style.width = Math.min((scores[k]/maxScore)*100,100)+'%';
    }

    // Set date
    var dateEl = document.getElementById('testDate');
    if(dateEl && data.created_at){
        var d = new Date(data.created_at);
        dateEl.textContent = d.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});
    }

    // Set dominant code
    var domEl = document.getElementById('domCode');
    if(domEl && data.dominant_code) domEl.textContent = data.dominant_code;

    // Hide elements
    var hideIds = ['loading','action-buttons','pdf-overlay'];
    hideIds.forEach(function(id){
        var el = document.getElementById(id);
        if(el) el.style.display = 'none';
    });

    var filename = 'Laporan_RIASEC_' + (data.dominant_code || 'report') + '.pdf';
    var opt = {
        margin:[0.3,0.3,0.3,0.3],
        filename:filename,
        image:{type:'jpeg',quality:0.9},
        html2canvas:{scale:1.5,useCORS:true,logging:false},
        jsPDF:{unit:'in',format:'a4',orientation:'portrait'}
    };

    html2pdf().set(opt).from(el).save().then(function(){
        window.parent.postMessage('pdf-success','*');
    }).catch(function(e){
        console.error('PDF error:',e);
        window.parent.postMessage('pdf-error','*');
    });
})();
<\/script>
</body>
</html>`);
    iframeDoc.close();

    setProgress(50, 'Membuat PDF...', 'Mohon tunggu');

    // Wait for message with timeout
    var timeoutId = setTimeout(function() {
        console.log('PDF timeout - cleaning up');
        done = true;
        if (iframe.parentNode) iframe.remove();
        if (buttons) buttons.style.display = 'flex';
        overlay.classList.remove('active');
    }, 20000); // 20 second timeout

    var done = false;
    function handleMessage(e) {
        if (done) return;
        if (e.data === 'pdf-success' || e.data === 'pdf-error') {
            done = true;
            clearTimeout(timeoutId);
            window.removeEventListener('message', handleMessage);
            if (iframe.parentNode) iframe.remove();
            setProgress(100, 'Selesai!', e.data === 'pdf-success' ? 'PDF berhasil diunduh' : 'Gagal');
            setTimeout(function(){
                if (buttons) buttons.style.display = 'flex';
                overlay.classList.remove('active');
            }, 1000);
        }
    }
    window.addEventListener('message', handleMessage);
}