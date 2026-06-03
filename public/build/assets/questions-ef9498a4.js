window.globalQuestionsData=window.globalQuestionsData||[];window.selectedQuestionIds=new Set;window.loadQuestions=function(){const e=document.getElementById("questionTable");if(!e)return;e.innerHTML="";const o=document.getElementById("statQuestions");if(o&&(o.innerText=window.globalQuestionsData.length),window.globalQuestionsData.length===0){e.innerHTML='<tr><td colspan="3" class="p-8 text-center text-gray-400">Belum ada soal di database.</td></tr>';return}window.globalQuestionsData.forEach(s=>{const t=s.is_active==1,n=t?'<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase">Tayang</span>':'<span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase">Draft</span>',a=t?"bg-red-500 hover:bg-red-600":"bg-[#4A90E2] hover:bg-[#357ABD]",r=t?"Tarik":"Publish";e.innerHTML+=`
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <td class="p-4 text-sm text-gray-700">${s.question_text}</td>
                <td class="p-4">${n}</td>
                <td class="p-4 text-right">
                    <form action="/admin-dashboard/question/${s.id}/toggle" method="POST" class="inline">
                        <input type="hidden" name="_token" value="${window.csrfToken}">
                        <button type="submit" class="px-3 py-1.5 ${a} text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                            ${r}
                        </button>
                    </form>
                </td>
            </tr>`})};window.loadForPublisher=function(){const e=document.getElementById("publisherList");if(!e)return;e.innerHTML="";const o=window.globalQuestionsData.filter(t=>t.is_active==1);if(o.length===0){e.innerHTML='<div class="text-center p-8 text-gray-400">Belum ada soal yang berstatus TAYANG.<br><small class="text-xs">Silakan publish soal di menu Kelola Soal terlebih dahulu.</small></div>';return}o.forEach(t=>{const n=window.selectedQuestionIds.has(t.id),a=n?"border-[#4A90E2] bg-[#EBF5FF]":"border-gray-200 bg-white hover:bg-gray-50 hover:border-[#4A90E2]",r=n?"text-[#4A90E2]":"text-gray-300",d=n?"ph-fill ph-check-square":"ph ph-square",i=`
        <div class="p-4 rounded-xl border mb-3 cursor-pointer transition-all flex gap-4 items-start ${a}" onclick="window.toggleSelection(${t.id})">
            <i class="${d} text-2xl ${r} mt-0.5"></i>
            <div class="flex-1">
                <div class="font-semibold text-sm text-gray-800 mb-2">${t.question_text}</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">R:</strong> ${t.opt_r}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">I:</strong> ${t.opt_i}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">A:</strong> ${t.opt_a}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">S:</strong> ${t.opt_s}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">E:</strong> ${t.opt_e}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">C:</strong> ${t.opt_c}</span>
                </div>
            </div>
        </div>`;e.innerHTML+=i});const s=document.getElementById("totalSelected");s&&(s.innerText=window.selectedQuestionIds.size+" Item")};window.toggleSelection=function(e){window.selectedQuestionIds.has(e)?window.selectedQuestionIds.delete(e):window.selectedQuestionIds.add(e),window.loadForPublisher()};document.addEventListener("DOMContentLoaded",()=>{typeof loadQuestions=="function"&&loadQuestions()});
