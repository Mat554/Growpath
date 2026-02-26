// resources/js/admin-dashboard.js

// 1. Ambil data dari jembatan Blade
let globalQuestionsData = window.globalQuestionsData || [];

// 2. Navigation Logic (Tempelkan ke window agar bisa dipanggil onclick di HTML)
window.showSection = function(sectionId) {
    document.querySelectorAll('.section').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('aside button').forEach(el => {
        el.classList.remove('bg-[#EBF5FF]', 'text-[#4A90E2]');
        el.classList.add('text-gray-500', 'hover:bg-gray-50');
    });

    document.getElementById(sectionId).classList.add('active');
    
    const navBtn = document.getElementById('nav-' + sectionId);
    if(navBtn) {
        navBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
        navBtn.classList.add('bg-[#EBF5FF]', 'text-[#4A90E2]');
    }

    if(sectionId === 'publish' || sectionId === 'overview') loadQuestions();
    if(sectionId === 'publisher-v2') loadForPublisher();
}


// ===============================================================
// 3. PUBLISHER V2 LOGIC
// ===============================================================
window.selectedQuestionIds = new Set(); // Jadikan global

window.loadForPublisher = function() {
    const container = document.getElementById('publisherList');
    if (!container) return; 
    
    container.innerHTML = ''; 

    // --- TAMBAHAN BARU: Filter hanya soal yang berstatus TAYANG ---
    const activeQuestions = window.globalQuestionsData.filter(q => q.is_active == 1);

    // Cek jika tidak ada soal yang tayang
    if (activeQuestions.length === 0) {
        container.innerHTML = '<div class="text-center p-8 text-gray-400">Belum ada soal yang berstatus TAYANG.<br><small class="text-xs">Silakan publish soal di menu Kelola Soal terlebih dahulu.</small></div>';
        return;
    }

    // Looping menggunakan data yang sudah di-filter (activeQuestions)
    activeQuestions.forEach(q => {
        const isSelected = window.selectedQuestionIds.has(q.id);
        const activeClass = isSelected ? 'border-[#4A90E2] bg-[#EBF5FF]' : 'border-gray-200 bg-white hover:bg-gray-50 hover:border-[#4A90E2]';
        const checkIconColor = isSelected ? 'text-[#4A90E2]' : 'text-gray-300';
        const iconClass = isSelected ? 'ph-fill ph-check-square' : 'ph ph-square';

        const itemHtml = `
        <div class="p-4 rounded-xl border mb-3 cursor-pointer transition-all flex gap-4 items-start ${activeClass}" onclick="window.toggleSelection(${q.id})">
            <i class="${iconClass} text-2xl ${checkIconColor} mt-0.5"></i>
            <div class="flex-1">
                <div class="font-semibold text-sm text-gray-800 mb-2">${q.question_text}</div>
                <div class="flex flex-wrap gap-1.5 mt-2">
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">R:</strong> ${q.opt_r}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">I:</strong> ${q.opt_i}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">A:</strong> ${q.opt_a}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">S:</strong> ${q.opt_s}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">E:</strong> ${q.opt_e}</span>
                    <span class="px-2 py-0.5 bg-white border border-gray-200 rounded text-[10px] text-gray-600 shadow-sm"><strong class="text-[#4A90E2]">C:</strong> ${q.opt_c}</span>
                </div>
            </div>
        </div>`;
        container.innerHTML += itemHtml;
    });
    
    const totalEl = document.getElementById('totalSelected');
    if(totalEl) totalEl.innerText = window.selectedQuestionIds.size + " Item";
}
window.toggleSelection = function(id) {
    if (window.selectedQuestionIds.has(id)) {
        window.selectedQuestionIds.delete(id);
    } else {
        window.selectedQuestionIds.add(id);
    }
    window.loadForPublisher(); // Refresh list untuk merubah warna yang di-klik
}

// 3. Load Questions Table
window.loadQuestions = function() {
    const tbody = document.getElementById('questionTable');
    if(!tbody) return; // Mencegah error jika elemen belum ada

    tbody.innerHTML = '';
    const statEl = document.getElementById('statQuestions');
    if(statEl) statEl.innerText = globalQuestionsData.length;

    if (globalQuestionsData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-gray-400">Belum ada soal di database.</td></tr>';
    } else {
        globalQuestionsData.forEach(q => {
            const isActive = q.is_active == 1; 
            const statusBadge = isActive 
                ? `<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase">Tayang</span>` 
                : `<span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-bold uppercase">Draft</span>`;
            
            const btnClass = isActive ? 'bg-red-500 hover:bg-red-600' : 'bg-[#4A90E2] hover:bg-[#357ABD]';
            const btnText = isActive ? 'Tarik' : 'Publish';

            tbody.innerHTML += `
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                    <td class="p-4 text-sm text-gray-700">${q.question_text}</td>
                    <td class="p-4">${statusBadge}</td>
                    <td class="p-4 text-right">
                        <form action="/admin-dashboard/question/${q.id}/toggle" method="POST" class="inline">
                            <input type="hidden" name="_token" value="${window.csrfToken}">
                            <button type="submit" class="px-3 py-1.5 ${btnClass} text-white rounded-lg text-xs font-semibold transition-all shadow-sm">
                                ${btnText}
                            </button>
                        </form>
                    </td>
                </tr>`;
        });
    }
}

window.startBetaTest = function() {
    if(window.selectedQuestionIds.size === 0) { 
        alert("Pilih minimal 1 soal untuk Beta Test!"); return; 
    }
    
    // Kita buat form tak terlihat untuk mengirim data ke tab baru
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin-dashboard/beta-test';
    form.target = '_blank'; // Buka di tab baru!

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = window.csrfToken; 
    form.appendChild(csrf);

    const qInput = document.createElement('input');
    qInput.type = 'hidden';
    qInput.name = 'question_ids';
    qInput.value = Array.from(window.selectedQuestionIds).join(','); // Ubah set ke teks "1,2,5"
    form.appendChild(qInput);

    document.body.appendChild(form);
    form.submit(); // Eksekusi
    document.body.removeChild(form);
}

window.compileCard = function() { // Ini untuk tombol Publish
    if(window.selectedQuestionIds.size === 0) { alert("Pilih minimal 1 soal!"); return; }
    
    const title = document.getElementById('cardTitle').value;
    const targetClass = document.getElementById('cardClass').value;
    const time = document.getElementById('cardTime').value;
    const date = document.getElementById('cardDate').value;

    if(!title || !time || !date) { alert("Mohon lengkapi semua Konfigurasi Card!"); return; }

    // Ubah tombol jadi loading
    const btnPublish = event.currentTarget;
    const originalText = btnPublish.innerHTML;
    btnPublish.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

    fetch('/admin-dashboard/publish', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            title: title,
            target_class: targetClass,
            duration_minutes: time,
            exam_date: date,
            question_ids: Array.from(window.selectedQuestionIds)
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert("🚀 " + data.message);
            // Reset form
            window.selectedQuestionIds.clear();
            window.loadForPublisher();
            document.getElementById('cardTitle').value = '';
        }
        btnPublish.innerHTML = originalText;
    });
}

// ... (Pindahkan juga fungsi loadForPublisher, toggleSelection, startBetaTest, dll ke sini) ...
// Pastikan menambahkan awalan `window.` untuk fungsi yang dipanggil lewat onclick di HTML.

// 4. Auto-Run saat halaman selesai dimuat (Pengganti @if session di Blade)
document.addEventListener('DOMContentLoaded', () => {
    if(window.activeTabSession && window.activeTabSession !== "") {
        window.showSection(window.activeTabSession);
    } else {
        window.loadQuestions(); // Default tab
    }
});