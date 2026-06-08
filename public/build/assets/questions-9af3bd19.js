window.globalQuestionsData=window.globalQuestionsData||[];window.selectedQuestionIds=new Set;window.currentPublisherFolder=null;document.addEventListener("DOMContentLoaded",()=>{window.loadQuestionArchive(),window.initPublisherFolders()});window.initPublisherFolders=function(){window.updatePublisherFolderCounts()};window.loadForPublisher=function(){window.updatePublisherFolderCounts(),window.closePublisherFolder(),window.selectedQuestionIds.clear(),window.updateTotalSelected()};window.updatePublisherFolderCounts=function(){const e={10:0,11:0,12:0};window.globalQuestionsData.forEach(t=>{t.is_active==1&&t.target_class&&t.target_class.split(",").map(n=>n.trim()).forEach(n=>{e[n]!==void 0&&e[n]++})}),document.getElementById("folder-count-10").innerText=e[10]+" soal",document.getElementById("folder-count-11").innerText=e[11]+" soal",document.getElementById("folder-count-12").innerText=e[12]+" soal"};window.openPublisherFolder=function(e){window.currentPublisherFolder=e,["10","11","12"].forEach(r=>{const l=document.getElementById("folder-btn-"+r);if(l)if(r===e){const a={10:"blue",11:"green",12:"purple"}[r];l.className=`flex-1 bg-${a}-100 hover:bg-${a}-200 border-2 border-${a}-400 rounded-xl p-4 transition-all shadow-sm`}else{const a={10:"blue",11:"green",12:"purple"}[r];l.className=`flex-1 bg-${a}-50 hover:bg-${a}-100 border-2 border-${a}-200 rounded-xl p-4 transition-all`}});const t=document.getElementById("publisherFolderTitle"),o={10:"blue",11:"green",12:"purple"},n={10:"Kelas 10",11:"Kelas 11",12:"Kelas 12"};t&&(t.innerHTML=`<span class="text-${o[e]}-600 font-bold">${n[e]}</span>`);const s=document.getElementById("publisherFolderActions");s&&s.classList.remove("hidden");const i=document.getElementById("publisherFolderActions");i&&(i.className="gap-2 flex"),window.loadPublisherQuestions(e)};window.closePublisherFolder=function(){window.currentPublisherFolder=null,["10","11","12"].forEach(n=>{const s=document.getElementById("folder-btn-"+n);if(s){const r={10:"blue",11:"green",12:"purple"}[n];s.className=`flex-1 bg-${r}-50 hover:bg-${r}-100 border-2 border-${r}-200 rounded-xl p-4 transition-all`}});const e=document.getElementById("publisherFolderTitle");e&&(e.innerText="Pilih folder di atas");const t=document.getElementById("publisherFolderActions");t&&t.classList.add("hidden");const o=document.getElementById("publisherList");o&&(o.innerHTML=`
            <div class="text-center p-8 text-gray-400">
                <i class="ph-fill ph-folder-open text-5xl mb-3 text-gray-300"></i>
                <p>Klik folder di atas untuk melihat soal</p>
            </div>
        `)};window.loadPublisherQuestions=function(e){const t=document.getElementById("publisherList");if(!t)return;t.innerHTML="";const o=window.globalQuestionsData.filter(n=>n.is_active!=1||!n.target_class?!1:n.target_class.split(",").map(i=>i.trim()).includes(e));if(o.length===0){const s={10:"blue",11:"green",12:"purple"}[e];t.innerHTML=`
            <div class="text-center p-8 text-gray-400">
                <i class="ph-fill ph-folder-simple-dashed text-5xl mb-3 text-${s}-200"></i>
                <p>Belum ada soal untuk Kelas ${e}</p>
                <small class="text-xs">Tambahkan soal di menu Kelola Soal</small>
            </div>
        `;return}o.forEach(n=>{const s=window.selectedQuestionIds.has(n.id),r={10:"blue",11:"green",12:"purple"}[e],l=s?`border-${r}-400 bg-${r}-50`:"border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50",d=s?`text-${r}-500`:"text-gray-300",a=s?"ph-fill ph-check-square":"ph ph-square",c=`
        <div class="p-4 rounded-xl border mb-2 cursor-pointer transition-all flex gap-3 items-start ${l}"
             onclick="window.toggleSelection(${n.id})">
            <i class="${a} text-xl ${d} mt-0.5"></i>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-700 line-clamp-2">${n.question_text}</p>
                <div class="flex flex-wrap gap-1 mt-2">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500"><strong class="text-[#4A90E2]">R:</strong> ${n.opt_r.substring(0,15)}${n.opt_r.length>15?"...":""}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500"><strong class="text-[#4A90E2]">I:</strong> ${n.opt_i.substring(0,15)}${n.opt_i.length>15?"...":""}</span>
                </div>
            </div>
        </div>`;t.innerHTML+=c}),window.updateTotalSelected()};window.selectAllInFolder=function(){if(!window.currentPublisherFolder)return;window.globalQuestionsData.filter(t=>t.is_active!=1||!t.target_class?!1:t.target_class.split(",").map(n=>n.trim()).includes(window.currentPublisherFolder)).forEach(t=>{window.selectedQuestionIds.add(t.id)}),window.loadPublisherQuestions(window.currentPublisherFolder)};window.clearFolderSelection=function(){if(!window.currentPublisherFolder)return;window.globalQuestionsData.filter(t=>!window.selectedQuestionIds.has(t.id)||!t.target_class?!1:t.target_class.split(",").map(n=>n.trim()).includes(window.currentPublisherFolder)).forEach(t=>{window.selectedQuestionIds.delete(t.id)}),window.loadPublisherQuestions(window.currentPublisherFolder)};window.updateTotalSelected=function(){const e=document.getElementById("totalSelected");e&&(e.innerText=window.selectedQuestionIds.size+" Item")};window.toggleSelection=function(e){window.selectedQuestionIds.has(e)?window.selectedQuestionIds.delete(e):window.selectedQuestionIds.add(e),window.currentPublisherFolder&&window.loadPublisherQuestions(window.currentPublisherFolder),window.updateTotalSelected()};window.loadQuestionArchive=function(){const e={all:[],10:[],11:[],12:[]};window.globalQuestionsData.forEach(t=>{const o=t.target_class?t.target_class.split(",").map(n=>n.trim()):[];e.all.push(t),o.forEach(n=>{e[n]&&e[n].push(t)})}),window.renderArchiveColumn("10",e[10]),window.renderArchiveColumn("11",e[11]),window.renderArchiveColumn("12",e[12]),window.renderAllQuestionsList(e.all),document.getElementById("count-class-10").innerText=e[10].length,document.getElementById("count-class-11").innerText=e[11].length,document.getElementById("count-class-12").innerText=e[12].length,document.getElementById("count-all").innerText=e.all.length,window.updatePublisherFolderCounts()};window.renderArchiveColumn=function(e,t){const o=document.getElementById("archive-class-"+e);if(o){if(t.length===0){o.innerHTML='<div class="text-center py-8 text-gray-400 text-sm">Seret soal ke sini</div>';return}o.innerHTML=t.map(n=>window.createArchiveCard(n,e)).join("")}};window.renderAllQuestionsList=function(e){const t=document.getElementById("allQuestionsList");if(t){if(e.length===0){t.innerHTML='<div class="text-center py-8 text-gray-400 text-sm">Belum ada soal.</div>';return}t.innerHTML=e.map(o=>window.createAllQuestionsCard(o)).join("")}};window.createArchiveCard=function(e,t){const n={10:"blue",11:"green",12:"purple"}[t]||"gray",s=e.is_active==1;return`
        <div class="question-card bg-white p-3 rounded-lg border border-${n}-200 shadow-sm mb-2"
             data-question-id="${e.id}">
            <div class="flex items-start gap-2">
                <div class="cursor-grab active:cursor-grabbing" draggable="true"
                     ondragstart="window.handleArchiveDragStart(event, ${e.id})"
                     ondragend="window.handleArchiveDragEnd(event)">
                    <i class="ph-fill ph-dots-six-vertical text-gray-400 mt-1 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 line-clamp-2">${e.question_text}</p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">
                            <strong class="text-[#4A90E2]">R:</strong> ${e.opt_r.substring(0,20)}${e.opt_r.length>20?"...":""}
                        </span>
                        <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-500">
                            <strong class="text-[#4A90E2]">I:</strong> ${e.opt_i.substring(0,20)}${e.opt_i.length>20?"...":""}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="px-2 py-0.5 bg-${n}-50 text-${n}-600 rounded text-[10px] font-semibold">Kelas ${t}</span>
                        <button onclick="window.toggleQuestionStatus(${e.id})"
                                class="px-2 py-0.5 rounded text-[10px] font-semibold transition-all ${s?"bg-green-500 hover:bg-green-600 text-white":"bg-gray-300 hover:bg-gray-400 text-gray-600"}">
                            <i class="ph-fill ph-power ${s?"":"hidden"}"></i>
                            <i class="ph ph-power ${s?"hidden":""}"></i>
                            ${s?"Aktif":"Draft"}
                        </button>
                    </div>
                    <div class="flex items-center gap-1 mt-2">
                        <button onclick="window.removeFromClass(${e.id}, '${t}')" class="px-2 py-0.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded text-[10px] font-semibold transition-all">
                            <i class="ph-fill ph-arrow-square-out mr-0.5"></i> Hapus dari Kelas
                        </button>
                        <button onclick="window.deleteQuestion(${e.id})" class="px-2 py-0.5 bg-red-50 hover:bg-red-100 text-red-500 rounded text-[10px] font-semibold transition-all">
                            <i class="ph-fill ph-trash mr-0.5"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `};window.createAllQuestionsCard=function(e){const t=e.target_class?e.target_class.split(",").map(i=>i.trim()):[],o=t.length>0?t.map(i=>`<span class="px-2 py-0.5 bg-[#EBF5FF] text-[#4A90E2] rounded text-[10px] font-semibold">K${i}</span>`).join(""):'<span class="px-2 py-0.5 bg-gray-100 text-gray-400 rounded text-[10px] font-semibold">-</span>',n=e.is_active==1,s=t.length>0;return`
        <div class="question-card bg-white p-3 rounded-lg border border-gray-200 shadow-sm mb-2"
             data-question-id="${e.id}">
            <div class="flex items-start gap-3">
                <div class="cursor-grab active:cursor-grabbing mt-0.5" draggable="true"
                     ondragstart="window.handleArchiveDragStart(event, ${e.id})"
                     ondragend="window.handleArchiveDragEnd(event)">
                    <i class="ph-fill ph-dots-six-vertical text-gray-400 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-700 line-clamp-2">${e.question_text}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        ${o}
                        <button onclick="window.toggleQuestionStatus(${e.id})"
                                class="px-2 py-0.5 rounded text-[10px] font-semibold transition-all ${n?"bg-green-500 hover:bg-green-600 text-white":"bg-gray-300 hover:bg-gray-400 text-gray-600"}">
                            <i class="ph-fill ph-power ${n?"":"hidden"}"></i>
                            <i class="ph ph-power ${n?"hidden":""}"></i>
                            ${n?"Aktif":"Draft"}
                        </button>
                    </div>
                    <div class="flex items-center gap-1 mt-2">
                        ${s?`
                        <button onclick="window.removeFromAllClasses(${e.id})" class="px-2 py-0.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-600 rounded text-[10px] font-semibold transition-all">
                            <i class="ph-fill ph-arrow-square-out mr-0.5"></i> Hapus dari Semua Kelas
                        </button>`:""}
                        <button onclick="window.deleteQuestion(${e.id})" class="px-2 py-0.5 bg-red-50 hover:bg-red-100 text-red-500 rounded text-[10px] font-semibold transition-all">
                            <i class="ph-fill ph-trash mr-0.5"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `};window.draggedQuestionId=null;window.draggedFromClass=null;window.handleArchiveDragStart=function(e,t){window.draggedQuestionId=t,e.target.classList.add("opacity-50","scale-95"),e.dataTransfer.effectAllowed="move",e.dataTransfer.setData("text/plain",t)};window.handleArchiveDragEnd=function(e){e.target.classList.remove("opacity-50","scale-95"),window.draggedQuestionId=null,window.draggedFromClass=null,document.querySelectorAll(".drag-over").forEach(t=>{t.classList.remove("drag-over","border-solid")})};window.handleColumnDragOver=function(e){e.preventDefault(),e.dataTransfer.dropEffect="move",e.currentTarget.classList.add("drag-over")};window.handleColumnDragLeave=function(e){e.currentTarget.classList.remove("drag-over")};window.handleColumnDrop=function(e,t){if(e.preventDefault(),e.currentTarget.classList.remove("drag-over"),!window.draggedQuestionId)return;const o=window.draggedQuestionId,n=window.globalQuestionsData.find(r=>r.id==o);if(!n)return;let s=n.target_class?n.target_class.split(",").map(r=>r.trim()):[];s.includes(t)||s.push(t),s.sort((r,l)=>r-l);const i=s.join(",");window.updateQuestionClass(o,i)};window.updateQuestionClass=function(e,t){fetch(`/admin-dashboard/question/${e}/class`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":window.csrfToken,Accept:"application/json"},body:JSON.stringify({target_class:t})}).then(async o=>{if(!o.ok){const n=await o.json().catch(()=>({}));throw new Error(n.message||"Gagal mengupdate kelas soal.")}return o.json()}).then(o=>{if(o.success){const n=window.globalQuestionsData.find(s=>s.id==e);n&&(n.target_class=t),window.loadQuestionArchive()}}).catch(o=>{console.error("Error updating class:",o),alert("Gagal mengupdate kelas: "+o.message)})};window.toggleQuestionStatus=function(e){fetch(`/admin-dashboard/question/${e}/toggle`,{method:"POST",headers:{"X-CSRF-TOKEN":window.csrfToken,Accept:"application/json"}}).then(t=>t.json()).then(t=>{if(t.success){const o=window.globalQuestionsData.find(n=>n.id==e);o&&(o.is_active=o.is_active==1?0:1),window.loadQuestionArchive()}}).catch(t=>{console.error("Error toggling status:",t)})};window.removeFromClass=function(e,t){const o=window.globalQuestionsData.find(i=>i.id==e);if(!o)return;let n=o.target_class?o.target_class.split(",").map(i=>i.trim()):[];n=n.filter(i=>i!==t);const s=n.join(",");window.updateQuestionClass(e,s)};window.removeFromAllClasses=function(e){confirm("Hapus soal dari semua kelas? Soal akan tetap Aktif tapi tidak ada di arsip manapun.")&&window.updateQuestionClass(e,"")};window.removeFromActive=function(e){confirm("Hapus soal dari status Aktif? Soal akan menjadi Draft.")&&fetch(`/admin-dashboard/question/${e}/remove`,{method:"POST",headers:{"X-CSRF-TOKEN":window.csrfToken,Accept:"application/json"}}).then(t=>t.json()).then(t=>{if(t.success){const o=window.globalQuestionsData.find(n=>n.id==e);o&&(o.is_active=0),window.loadQuestionArchive()}}).catch(t=>{console.error("Error removing from active:",t),alert("Gagal menghapus dari aktif.")})};window.deleteQuestion=function(e){confirm("Yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.")&&fetch(`/admin-dashboard/question/${e}/delete`,{method:"POST",headers:{"X-CSRF-TOKEN":window.csrfToken,Accept:"application/json"}}).then(t=>t.json()).then(t=>{t.success&&(window.globalQuestionsData=window.globalQuestionsData.filter(o=>o.id!=e),window.selectedQuestionIds.delete(e),window.loadQuestionArchive(),window.updatePublisherFolderCounts())}).catch(t=>{console.error("Error deleting question:",t),alert("Gagal menghapus soal.")})};
