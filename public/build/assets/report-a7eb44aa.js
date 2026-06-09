document.addEventListener("DOMContentLoaded",()=>{if(!document.getElementById("reportContainer"))return;typeof p=="function"&&p();const a=document.getElementById("downloadPdf");a&&a.addEventListener("click",v)});function p(){if(!window.reportData)return;const e={R:window.reportData.score_r||0,I:window.reportData.score_i||0,A:window.reportData.score_a||0,S:window.reportData.score_s||0,E:window.reportData.score_e||0,C:window.reportData.score_c||0},a=Object.values(e),n=Math.max(...a),d=Math.max(10,Math.min(window.reportData.max_score||60,Math.ceil(n*1.5)));Object.keys(e).forEach(r=>{const t=e[r]/d*100;h(t,`bar-${r}`)})}function h(e,a){const n=document.getElementById(a);n&&(n.style.width=e+"%")}async function v(){const e=document.getElementById("pdf-overlay"),a=document.getElementById("pdf-overlay-title"),n=document.getElementById("pdf-overlay-subtitle"),d=document.getElementById("pdf-progress-bar");function r(s,y,g){d&&(d.style.width=s+"%"),a&&(a.textContent=y||""),n&&(n.textContent=g||"")}e.classList.add("active"),r(10,"Menyiapkan...","Mengambil data");const t=document.getElementById("action-buttons");t&&(t.style.display="none"),r(25,"Memproses...","Membangun dokumen");const l=document.getElementById("report-content");if(!l){alert("Laporan tidak ditemukan."),t&&(t.style.display="flex"),e.classList.remove("active");return}const f=l.innerHTML,o=document.createElement("iframe");o.style.cssText="position:fixed;top:0;left:0;width:800px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-9999;",o.setAttribute("id","pdf-iframe"),document.body.appendChild(o);const i=o.contentDocument||o.contentWindow.document;i.open(),i.write(`<!DOCTYPE html>
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
<body>${f}
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
</html>`),i.close(),r(50,"Membuat PDF...","Mohon tunggu");var u=setTimeout(function(){console.log("PDF timeout - cleaning up"),c=!0,o.parentNode&&o.remove(),t&&(t.style.display="flex"),e.classList.remove("active")},2e4),c=!1;function m(s){c||(s.data==="pdf-success"||s.data==="pdf-error")&&(c=!0,clearTimeout(u),window.removeEventListener("message",m),o.parentNode&&o.remove(),r(100,"Selesai!",s.data==="pdf-success"?"PDF berhasil diunduh":"Gagal"),setTimeout(function(){t&&(t.style.display="flex"),e.classList.remove("active")},1e3))}window.addEventListener("message",m)}
