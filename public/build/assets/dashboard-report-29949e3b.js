document.addEventListener("DOMContentLoaded",()=>{typeof window.reportData<"u"&&h()});function h(){setTimeout(()=>{const r=document.getElementById("loading");r&&(r.style.display="none");const e=window.reportData;if(!e)return;const s=new Date(e.created_at),i=document.getElementById("testDate");i&&(i.innerText=s.toLocaleDateString("id-ID",{day:"numeric",month:"long",year:"numeric"}));const a=document.getElementById("domCode");a&&(a.innerText=e.dominant_code);const t=[e.scores.R,e.scores.I,e.scores.A,e.scores.S,e.scores.E,e.scores.C],d=Math.max(...t),n=Math.max(10,Math.min(e.max_score||60,Math.ceil(d*1.5)));l("barR","scoreR",e.scores.R,n),l("barI","scoreI",e.scores.I,n),l("barA","scoreA",e.scores.A,n),l("barS","scoreS",e.scores.S,n),l("barE","scoreE",e.scores.E,n),l("barC","scoreC",e.scores.C,n);const o=document.getElementById("riasecChart");if(o&&typeof Chart<"u"){const c=o.getContext("2d"),m=c.createLinearGradient(0,0,0,300);m.addColorStop(0,"rgba(74, 144, 226, 0.5)"),m.addColorStop(1,"rgba(74, 144, 226, 0.0)"),new Chart(c,{type:"line",data:{labels:["Realistic","Investigative","Artistic","Social","Enterprising","Conventional"],datasets:[{label:"Poin",data:[e.scores.R,e.scores.I,e.scores.A,e.scores.S,e.scores.E,e.scores.C],borderColor:"#4A90E2",backgroundColor:m,borderWidth:3,pointBackgroundColor:"#ffffff",pointBorderColor:"#4A90E2",pointBorderWidth:2,pointRadius:6,pointHoverRadius:8,fill:!0,tension:.4}]},options:{animation:{onComplete:()=>{window._chartReady=!0}},responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1}},scales:{y:{beginAtZero:!0,max:n,grid:{color:"#f3f4f6",borderDash:[5,5]},border:{display:!1},ticks:{stepSize:Math.max(1,Math.ceil(n/6)),font:{family:"'Poppins', sans-serif"},color:"#9ca3af"}},x:{grid:{display:!1},border:{display:!1},ticks:{font:{family:"'Poppins', sans-serif",weight:"500"},color:"#6b7280"}}}}})}},500)}function l(r,e,s,i){let a=s/i*100;a>100&&(a=100);const t=document.getElementById(r),d=document.getElementById(e);d&&(d.innerText=`${s} Poin`),t&&setTimeout(()=>{t.style.width=a+"%"},100)}window.downloadPDF=async function(){const r=document.getElementById("pdf-overlay"),e=document.getElementById("pdf-overlay-title"),s=document.getElementById("pdf-overlay-subtitle"),i=document.getElementById("pdf-progress-bar");function a(p,y,g){i&&(i.style.width=p+"%"),e&&(e.textContent=y||""),s&&(s.textContent=g||"")}r.classList.add("active"),a(10,"Menyiapkan...","Mengambil data");const t=document.getElementById("action-buttons");t&&(t.style.display="none"),a(25,"Memproses...","Membangun dokumen");const d=document.getElementById("report-content");if(!d){alert("Laporan tidak ditemukan."),t&&(t.style.display="flex"),r.classList.remove("active");return}const n=d.innerHTML,o=document.createElement("iframe");o.style.cssText="position:fixed;top:0;left:0;width:800px;height:100vh;opacity:0;pointer-events:none;border:none;z-index:-9999;",o.setAttribute("id","pdf-iframe"),document.body.appendChild(o);const c=o.contentDocument||o.contentWindow.document;c.open(),c.write(`<!DOCTYPE html>
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
<body>${n}
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
</html>`),c.close(),a(50,"Membuat PDF...","Mohon tunggu");var m=setTimeout(function(){console.log("PDF timeout - cleaning up"),f=!0,o.parentNode&&o.remove(),t&&(t.style.display="flex"),r.classList.remove("active")},2e4),f=!1;function u(p){f||(p.data==="pdf-success"||p.data==="pdf-error")&&(f=!0,clearTimeout(m),window.removeEventListener("message",u),o.parentNode&&o.remove(),a(100,"Selesai!",p.data==="pdf-success"?"PDF berhasil diunduh":"Gagal"),setTimeout(function(){t&&(t.style.display="flex"),r.classList.remove("active")},1e3))}window.addEventListener("message",u)};
