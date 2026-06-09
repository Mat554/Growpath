document.addEventListener("DOMContentLoaded",()=>{typeof window.reportData<"u"&&h()});function h(){setTimeout(()=>{const a=document.getElementById("loading");a&&(a.style.display="none");const t=window.reportData;if(!t)return;const d=new Date(t.created_at),s=document.getElementById("testDate");s&&(s.innerText=d.toLocaleDateString("id-ID",{day:"numeric",month:"long",year:"numeric"}));const r=document.getElementById("domCode");r&&(r.innerText=t.dominant_code);const e=t.max_score||60;l("barR","scoreR",t.scores.R,e),l("barI","scoreI",t.scores.I,e),l("barA","scoreA",t.scores.A,e),l("barS","scoreS",t.scores.S,e),l("barE","scoreE",t.scores.E,e),l("barC","scoreC",t.scores.C,e);const n=document.getElementById("riasecChart");if(n&&typeof Chart<"u"){const m=n.getContext("2d"),i=m.createLinearGradient(0,0,0,300);i.addColorStop(0,"rgba(74, 144, 226, 0.5)"),i.addColorStop(1,"rgba(74, 144, 226, 0.0)");const c=e;new Chart(m,{type:"line",data:{labels:["Realistic","Investigative","Artistic","Social","Enterprising","Conventional"],datasets:[{label:"Poin",data:[t.scores.R,t.scores.I,t.scores.A,t.scores.S,t.scores.E,t.scores.C],borderColor:"#4A90E2",backgroundColor:i,borderWidth:3,pointBackgroundColor:"#ffffff",pointBorderColor:"#4A90E2",pointBorderWidth:2,pointRadius:5,pointHoverRadius:7,fill:!0,tension:.4}]},options:{animation:{onComplete:()=>{window._chartReady=!0}},responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1}},scales:{y:{beginAtZero:!0,max:c,grid:{color:"#f3f4f6",borderDash:[5,5]},border:{display:!1},ticks:{stepSize:Math.max(1,Math.ceil(c/6)),font:{family:"'Poppins', sans-serif"},color:"#9ca3af"}},x:{grid:{display:!1},border:{display:!1},ticks:{font:{family:"'Poppins', sans-serif",weight:"500"},color:"#6b7280"}}}}})}},500)}function l(a,t,d,s){let r=d/s*100;r>100&&(r=100);const e=document.getElementById(a),n=document.getElementById(t);n&&(n.innerText=`${d} Poin`),e&&setTimeout(()=>{e.style.width=r+"%"},100)}window.downloadPDF=async function(){const a=document.getElementById("pdf-overlay"),t=document.getElementById("pdf-overlay-title"),d=document.getElementById("pdf-overlay-subtitle"),s=document.getElementById("pdf-progress-bar"),r=window.reportData;function e(o,g,f){s.style.width=o+"%",g&&(t.textContent=g),f&&(d.textContent=f)}a.classList.add("active"),e(0,"Menyiapkan laporan...","Mengambil data hasil tes"),await new Promise(o=>setTimeout(o,300)),e(15,"Memproses grafik...","Mengkonversi chart ke gambar"),window._chartReady||await new Promise(o=>setTimeout(o,1e3));const n=document.getElementById("riasecChart");let m="";n&&n.width>0&&(m=n.toDataURL("image/png",1)),await new Promise(o=>setTimeout(o,200)),e(30,"Menyusun halaman...","Membangun layout dokumen");const i=document.getElementById("action-buttons");i&&(i.style.display="none");const c=document.getElementById("report-content").innerHTML;i&&(i.style.display="flex"),await new Promise(o=>setTimeout(o,200)),e(50,"Membuat dokumen...","Memuat font dan aset");const p=document.createElement("iframe");p.style.cssText="position:fixed;top:0;left:0;width:750px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-1;",p.setAttribute("data-pdf-frame","true"),document.body.appendChild(p);const b=p.contentDocument||p.contentWindow.document;b.open(),b.write(`<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"><\/script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"><\/script>
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
${c}
</div>
<script>
const canvas = document.querySelector('canvas');
if (canvas && '${m}') {
    const img = document.createElement('img');
    img.src = '${m}';
    img.style.cssText = 'width:100%;height:100%;object-fit:fill;display:block;';
    canvas.parentNode.replaceChild(img, canvas);
}
const scores = ${JSON.stringify(r.scores)};
const max = ${r.max_score||60};
const barMap = { R:'barR', I:'barI', A:'barA', S:'barS', E:'barE', C:'barC' };
Object.entries(barMap).forEach(([key, id]) => {
    const bar = document.getElementById(id);
    if (bar) bar.style.width = Math.min((scores[key] / max) * 100, 100) + '%';
});
const dateObj = new Date('${r.created_at}');
const testDateEl = document.getElementById('testDate');
if (testDateEl) testDateEl.innerText = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
const domCodeEl = document.getElementById('domCode');
if (domCodeEl) domCodeEl.innerText = '${r.dominant_code}';
const ebfCard = document.querySelector('[class*="EBF5FF"]');
if (ebfCard) {
    ebfCard.style.cssText += 'display:flex !important;flex-direction:row !important;align-items:center !important;gap:1.5rem !important;margin-left:-2.5rem !important;margin-right:-2.5rem !important;padding-left:2.5rem !important;padding-right:2.5rem !important;border-radius:0 !important;';
    const leftSide = ebfCard.querySelector('[class*="min-w"]');
    if (leftSide) { leftSide.style.cssText += 'text-align:center !important;display:flex !important;flex-direction:column !important;align-items:center !important;min-width:120px !important;flex-shrink:0 !important;'; }
    const rightSide = ebfCard.querySelector('.flex-1');
    if (rightSide) { rightSide.style.cssText += 'border-top:none !important;border-left:1px solid #dbeafe !important;padding-top:0 !important;padding-left:1.5rem !important;text-align:left !important;display:flex !important;flex-direction:column !important;'; }
}
const headerRow = document.querySelector('.relative.z-10');
if (headerRow) {
    headerRow.style.cssText += 'display:flex !important;flex-direction:row !important;justify-content:space-between !important;align-items:center !important;';
    const nameBlock = headerRow.querySelector('.hidden');
    if (nameBlock) nameBlock.style.display = 'block';
}
window.addEventListener('load', async function() {
    await new Promise(r => setTimeout(r, 600));
    await document.fonts.ready;
    const el = document.getElementById('pdf-root');
    const pdf = new jspdf.jsPDF({ orientation: 'portrait', unit: 'in', format: 'a4' });
    const cvs = await html2canvas(el, {
        scale: 2, useCORS: true, backgroundColor: '#ffffff', width: 750, windowWidth: 750
    });
    const imgData = cvs.toDataURL('image/jpeg', 0.95);
    const pdfW = pdf.internal.pageSize.getWidth();
    const pdfH = pdf.internal.pageSize.getHeight();
    const imgW = cvs.width;
    const imgH = cvs.height;
    const ratio = pdfW / (imgW / 100);
    const scaledH = (imgH / 100) * ratio;
    let pos = 0;
    while (pos < scaledH) {
        pdf.addImage(imgData, 'JPEG', 0, -pos, pdfW, scaledH);
        pos += pdfH;
        if (pos < scaledH) pdf.addPage();
    }
    pdf.save('Laporan_RIASEC_${r.dominant_code}.pdf');
    window.parent.postMessage('pdf-done', '*');
});
<\/script>
</body>
</html>`),b.close(),e(70,"Merender konten...","Sedang memproses halaman"),await new Promise(o=>{window.addEventListener("message",function g(f){f.data==="pdf-done"&&(window.removeEventListener("message",g),o())})}),e(100,"Selesai!","PDF berhasil diunduh ✓"),await new Promise(o=>setTimeout(o,900));const u=document.querySelector("iframe[data-pdf-frame]");u&&u.remove(),a.classList.remove("active")};
