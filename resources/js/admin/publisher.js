/**
 * Admin Exam Publisher JavaScript
 * Handles exam card building, beta test, and publishing
 */

/**
 * Start beta test - redirects to dedicated preview page
 */
window.startBetaTest = function() {
    if (!window.selectedQuestionIds || window.selectedQuestionIds.size === 0) {
        alert("Pilih minimal 1 soal untuk Beta Test!");
        return;
    }

    const timeInput = document.getElementById('cardTime');
    const duration = timeInput ? (timeInput.value || 60) : 60;
    const title = document.getElementById('cardTitle')?.value || 'Beta Test Preview';

    const btn = document.querySelector('button[onclick*="startBetaTest"]');
    if (btn) {
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Memuat...';
        btn.disabled = true;
    }

    fetch('/admin-dashboard/beta-test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            question_ids: Array.from(window.selectedQuestionIds).join(','),
            duration: duration,
            title: title
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || 'Terjadi kesalahan.');
            if (btn) {
                btn.innerHTML = '<i class="ph-fill ph-flask"></i> Beta Test';
                btn.disabled = false;
            }
        }
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat memuat Beta Test.');
        if (btn) {
            btn.innerHTML = '<i class="ph-fill ph-flask"></i> Beta Test';
            btn.disabled = false;
        }
    });
};

/**
 * Compile and publish exam card
 */
window.compileCard = function(event) {
    if (window.selectedQuestionIds.size === 0) {
        alert("Pilih minimal 1 soal!");
        return;
    }

    const title = document.getElementById('cardTitle')?.value;
    const targetClass = document.getElementById('cardClass')?.value;
    const time = document.getElementById('cardTime')?.value;
    const startDate = document.getElementById('cardDateStart')?.value;
    const endDate = document.getElementById('cardDateEnd')?.value;

    if (!title || !time || !startDate) {
        alert("Mohon lengkapi Judul Tes dan Tanggal Mulai!");
        return;
    }

    // Get button for loading state
    const btnPublish = event?.currentTarget || document.querySelector('button[onclick*="compileCard"]');
    if (!btnPublish) {
        console.error("Tombol tidak ditemukan!");
        return;
    }

    const originalText = btnPublish.innerHTML;
    btnPublish.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
    btnPublish.disabled = true;

    fetch('/admin-dashboard/publish', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            target_class: targetClass,
            duration_minutes: time,
            exam_date: startDate,
            exam_end_date: endDate || null,
            question_ids: Array.from(window.selectedQuestionIds)
        })
    })
    .then(async res => {
        if (!res.ok) {
            const errData = await res.json().catch(() => ({}));
            throw new Error(errData.message || "Terjadi kesalahan di server database.");
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            alert("🚀 " + data.message);
            // Reset form
            window.selectedQuestionIds.clear();
            window.loadForPublisher();
            window.updatePublisherFolderCounts();
            document.getElementById('cardTitle').value = '';
        }
    })
    .catch(err => {
        console.error("Error Publish:", err);
        alert("Gagal Publish: " + err.message);
    })
    .finally(() => {
        btnPublish.innerHTML = originalText;
        btnPublish.disabled = false;
    });
};