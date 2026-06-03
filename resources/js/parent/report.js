/**
 * Parent Report JavaScript
 * Handles child's report rendering and PDF export
 */

document.addEventListener('DOMContentLoaded', () => {
    const reportContainer = document.getElementById('reportContainer');
    if (!reportContainer) return;

    // Render report if function exists
    if (typeof renderReport === 'function') {
        renderReport();
    }

    // PDF download button
    const downloadBtn = document.getElementById('downloadPdf');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadPDF);
    }
});

// Re-use functions from student/report.js
// These are shared between student and parent views

function renderReport() {
    if (!window.reportData) return;

    const scores = {
        R: window.reportData.score_r || 0,
        I: window.reportData.score_i || 0,
        A: window.reportData.score_a || 0,
        S: window.reportData.score_s || 0,
        E: window.reportData.score_e || 0,
        C: window.reportData.score_c || 0
    };

    const maxScore = Math.max(...Object.values(scores), 1);

    Object.keys(scores).forEach(key => {
        const percent = (scores[key] / maxScore) * 100;
        updateBar(percent, `bar-${key}`);
    });
}

function updateBar(percent, elementId) {
    const bar = document.getElementById(elementId);
    if (bar) {
        bar.style.width = percent + '%';
    }
}

async function downloadPDF() {
            const overlay = document.getElementById('pdf-overlay');
            const overlayTitle = document.getElementById('pdf-overlay-title');
            const overlaySubtitle = document.getElementById('pdf-overlay-subtitle');
            const progressBar = document.getElementById('pdf-progress-bar');

            function setProgress(pct, title, subtitle) {
                progressBar.style.width = pct + '%';
                if (title) overlayTitle.textContent = title;
                if (subtitle) overlaySubtitle.textContent = subtitle;
            }

            overlay.classList.add('active');
            setProgress(0, 'Menyiapkan laporan...', 'Mengambil data hasil tes');
            await new Promise(r => setTimeout(r, 300));

            setProgress(15, 'Memproses grafik...', 'Mengkonversi chart ke gambar');
            if (!window._chartReady) {
                await new Promise(r => setTimeout(r, 1000));
            }
            const canvas = document.getElementById('riasecChart');
            let chartImageSrc = '';
            if (canvas && canvas.width > 0) {
                chartImageSrc = canvas.toDataURL('image/png', 1.0);
            }

            await new Promise(r => setTimeout(r, 200));
            setProgress(30, 'Menyusun halaman...', 'Membangun layout dokumen');

            const buttons = document.getElementById('action-buttons');
            if (buttons) buttons.style.display = 'none';
            const reportHTML = document.getElementById('report-content').innerHTML;
            if (buttons) buttons.style.display = 'flex';

            await new Promise(r => setTimeout(r, 200));
            setProgress(50, 'Membuat dokumen...', 'Memuat font dan aset');

            const iframe = document.createElement('iframe');
            iframe.style.cssText = 'position:fixed;top:0;left:0;width:750px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-1;';
            iframe.setAttribute('data-pdf-frame', 'true');
            document.body.appendChild(iframe);

            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframeDoc.open();
          iframeDoc.write(`<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"><\/script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"><\/script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Poppins', sans-serif; background: white; color: #333; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
.flex { display: flex !important; }
.flex-col { flex-direction: row !important; }
.flex-1 { flex: 1 !important; }
.flex-shrink-0 { flex-shrink: 0; }
.md\\:flex-row { flex-direction: row !important; }
.md\\:text-left { text-align: left !important; }
.md\\:border-l { border-left: 1px solid #dbeafe !important; }
.md\\:border-t-0 { border-top: none !important; }
.md\\:pl-6 { padding-left: 1.5rem !important; }
.md\\:pt-0 { padding-top: 0 !important; }
.md\\:block { display: block !important; }
.md\\:p-10 { padding: 2.5rem !important; }
.md\\:grid-cols-2 { grid-template-columns: repeat(2, 1fr) !important; }
.md\\:text-3xl { font-size: 1.875rem !important; }
.md\\:text-5xl { font-size: 3rem !important; }
.md\\:-mx-10 { margin-left: -2.5rem !important; margin-right: -2.5rem !important; }
.md\\:px-10 { padding-left: 2.5rem !important; padding-right: 2.5rem !important; }
.hidden { display: none !important; }
.hidden.md\\:block { display: block !important; }
.grid { display: grid; }
.grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
.gap-x-8 { column-gap: 2rem; }
.gap-y-6 { row-gap: 1.5rem; }
.gap-2 { gap: 0.5rem; }
.gap-4 { gap: 1rem; }
.gap-6 { gap: 1.5rem; }
.gap-8 { gap: 2rem; }
.items-center { align-items: center !important; }
.items-start { align-items: flex-start; }
.justify-between { justify-content: space-between !important; }
.justify-center { justify-content: center; }
.text-center { text-align: center; }
.text-left { text-align: left; }
.text-right { text-align: right !important; }
.w-full { width: 100%; }
.h-full { height: 100%; }
.min-w-\\[120px\\] { min-width: 120px; }
.w-2 { width: 0.5rem; }
.h-2 { height: 0.5rem; }
.w-64 { width: 16rem; }
.h-64 { height: 16rem; }
.w-48 { width: 12rem; }
.h-48 { height: 12rem; }
.w-\\[320px\\] { width: 320px; }
.h-\\[320px\\] { height: 320px; }
.h-2\\.5 { height: 0.625rem; }
.p-4 { padding: 1rem; }
.p-5 { padding: 1.25rem; }
.p-6 { padding: 1.5rem; }
.p-8 { padding: 2rem; }
.px-8 { padding-left: 2rem !important; padding-right: 2rem !important; }
.-mx-8 { margin-left: -2rem !important; margin-right: -2rem !important; }
.pt-4 { padding-top: 1rem; }
.pt-6 { padding-top: 1.5rem; }
.pb-2 { padding-bottom: 0.5rem; }
.mb-1 { margin-bottom: 0.25rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-4 { margin-bottom: 1rem; }
.mb-6 { margin-bottom: 1.5rem; }
.mb-8 { margin-bottom: 2rem; }
.mt-6 { margin-top: 1.5rem; }
.mt-10 { margin-top: 2.5rem; }
.text-xs { font-size: 0.75rem; }
.text-sm { font-size: 0.875rem; }
.text-lg { font-size: 1.125rem; }
.text-2xl { font-size: 1.5rem; }
.text-3xl { font-size: 1.875rem; }
.text-4xl { font-size: 2.25rem; }
.text-5xl { font-size: 3rem; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
.font-extrabold { font-weight: 800; }
.leading-relaxed { line-height: 1.625; }
.tracking-widest { letter-spacing: 0.1em; }
.tracking-wider { letter-spacing: 0.05em; }
.uppercase { text-transform: uppercase; }
.italic { font-style: italic; }
.opacity-90 { opacity: 0.9; }
.opacity-80 { opacity: 0.8; }
.text-white { color: white !important; }
.text-\\[\\#4A90E2\\] { color: #4A90E2 !important; }
.text-\\[\\#FF9F43\\] { color: #FF9F43 !important; }
.text-gray-800 { color: #1f2937 !important; }
.text-gray-700 { color: #374151 !important; }
.text-gray-600 { color: #4b5563 !important; }
.text-gray-500 { color: #6b7280 !important; }
.text-gray-900 { color: #111827 !important; }
.bg-gradient-to-r { background: linear-gradient(to right, #4A90E2, #6DD5FA) !important; }
.bg-\\[\\#EBF5FF\\] { background-color: #EBF5FF !important; }
.bg-gray-50 { background-color: #f9fafb !important; }
.bg-gray-100 { background-color: #f3f4f6 !important; }
.bg-white { background-color: white !important; }
.bg-white\\/10 { background: rgba(255,255,255,0.1) !important; }
.bg-blue-50\\/50 { background-color: rgba(239,246,255,0.5) !important; }
.bg-orange-50\\/50 { background-color: rgba(255,247,237,0.5) !important; }
.bg-red-500 { background-color: #ef4444 !important; }
.bg-blue-500 { background-color: #3b82f6 !important; }
.bg-yellow-400 { background-color: #facc15 !important; }
.bg-green-500 { background-color: #22c55e !important; }
.bg-purple-500 { background-color: #a855f7 !important; }
.bg-gray-500 { background-color: #6b7280 !important; }
.rounded-full { border-radius: 9999px; }
.rounded-xl { border-radius: 0.75rem; }
.rounded-r-xl { border-radius: 0 !important; }
.border-b { border-bottom: 1px solid; }
.border-l { border-left: 1px solid; }
.border-l-\\[6px\\] { border-left-width: 6px !important; }
.border-\\[\\#4A90E2\\] { border-color: #4A90E2 !important; }
.border-gray-100 { border-color: #f3f4f6 !important; }
.border-blue-100 { border-color: #dbeafe !important; }
.border-orange-100 { border-color: #ffedd5 !important; }
.shadow-sm { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
.relative { position: relative; }
.absolute { position: absolute; }
.inset-0 { top:0; right:0; bottom:0; left:0; }
.z-10 { z-index: 10; }
.overflow-hidden { overflow: visible !important; }
.top-\\[-50\\%\\] { top: -50%; }
.right-\\[-10\\%\\] { right: -10%; }
.bottom-\\[-50\\%\\] { bottom: -50%; }
.left-\\[-10\\%\\] { left: -10%; }
.blur-2xl { filter: blur(40px); }
.bar-bg { background-color: #f3f4f6 !important; }
.bar-fill { height: 100%; border-radius: 9999px; transition: none !important; }
.list-disc { list-style-type: disc; }
.list-inside { list-style-position: inside; }
.space-y-2 > * + * { margin-top: 0.5rem; }
.no-print { display: none !important; }
.animate-fade-in { opacity: 1 !important; transform: none !important; animation: none !important; }
#loading { display: none !important; }
#action-buttons { display: none !important; }
#pdf-overlay { display: none !important; }
</style>
</head>
<body>
<div id="pdf-root" style="width:750px;background:white;margin:0 auto;padding:0;">
${reportHTML}
</div>
<script>
// Replace canvas with chart image
const canvas = document.querySelector('canvas');
if (canvas && '${chartImageSrc}') {
    const img = document.createElement('img');
    img.src = '${chartImageSrc}';
    img.style.cssText = 'width:100%;height:100%;object-fit:fill;display:block;';
    canvas.parentNode.replaceChild(img, canvas);
}

// Set bar widths
const scores = ${JSON.stringify(mockResult.scores)};
const max = 15;
const barMap = { R:'barR', I:'barI', A:'barA', S:'barS', E:'barE', C:'barC' };
Object.entries(barMap).forEach(([key, id]) => {
    const bar = document.getElementById(id);
    if (bar) bar.style.width = Math.min((scores[key] / max) * 100, 100) + '%';
});

// Set date and dominant code
const dateObj = new Date('${mockResult.created_at}');
const testDateEl = document.getElementById('testDate');
if (testDateEl) testDateEl.innerText = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
const domCodeEl = document.getElementById('domCode');
if (domCodeEl) domCodeEl.innerText = '${mockResult.dominant_code}';

// FIX KODE DOMINAN CARD — stretch edge to edge like header, remove gap
const ebfCard = document.querySelector('[class*="EBF5FF"]');
if (ebfCard) {
    ebfCard.style.cssText += 'display:flex !important;flex-direction:row !important;align-items:center !important;gap:1.5rem !important;margin-left:-2.5rem !important;margin-right:-2.5rem !important;padding-left:2.5rem !important;padding-right:2.5rem !important;border-radius:0 !important;';
    const leftSide = ebfCard.querySelector('[class*="min-w"]');
    if (leftSide) {
        leftSide.style.cssText += 'text-align:center !important;display:flex !important;flex-direction:column !important;align-items:center !important;min-width:120px !important;flex-shrink:0 !important;';
    }
    const rightSide = ebfCard.querySelector('.flex-1');
    if (rightSide) {
        rightSide.style.cssText += 'border-top:none !important;border-left:1px solid #dbeafe !important;padding-top:0 !important;padding-left:1.5rem !important;text-align:left !important;display:flex !important;flex-direction:column !important;';
    }
}

// FIX HEADER ROW
const headerRow = document.querySelector('.relative.z-10');
if (headerRow) {
    headerRow.style.cssText += 'display:flex !important;flex-direction:row !important;justify-content:space-between !important;align-items:center !important;';
    const nameBlock = headerRow.querySelector('.hidden');
    if (nameBlock) nameBlock.style.display = 'block';
}

window.addEventListener('load', async function() {
    await new Promise(r => setTimeout(r, 600));
    const el = document.getElementById('pdf-root');
    const opt = {
        margin: [0.4, 0.4, 0.4, 0.4],
        filename: 'Laporan_RIASEC_${mockResult.dominant_code}.pdf',
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { scale: 2, useCORS: true, scrollX: 0, scrollY: 0, windowWidth: 750, logging: false },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
        pagebreak: { mode: ['css', 'legacy'] }
    };
    await html2pdf().set(opt).from(el).save();
    window.parent.postMessage('pdf-done', '*');
});
<\/script>
</body>
</html>`);
iframeDoc.close();

setProgress(70, 'Merender konten...', 'Sedang memproses halaman');

await new Promise(resolve => {
    window.addEventListener('message', function handler(e) {
        if (e.data === 'pdf-done') {
            window.removeEventListener('message', handler);
            resolve();
        }
    });
});

setProgress(100, 'Selesai!', 'PDF berhasil diunduh ✓');
await new Promise(r => setTimeout(r, 900));

const frame = document.querySelector('iframe[data-pdf-frame]');
if (frame) frame.remove();
overlay.classList.remove('active');
}
