/**
 * Admin Monitoring JavaScript
 * Handles real-time data fetching and display
 */

document.addEventListener('DOMContentLoaded', () => {
    const monitoringTableBody = document.getElementById('monitoringTableBody');
    if (!monitoringTableBody) return;

    const examFilter = document.getElementById('examFilter');
    const refreshBtn = document.getElementById('btnRefresh');
    const refreshIcon = document.getElementById('refreshIcon');

    let autoRefreshInterval = null;

    /**
     * Fetch and render monitoring data
     */
    window.fetchMonitoringData = function() {
        const examId = examFilter?.value || '';

        // UI Loading State
        if (refreshIcon) refreshIcon.classList.add('animate-spin');
        if (refreshBtn) refreshBtn.disabled = true;
        monitoringTableBody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400"><i class="ph ph-spinner animate-spin text-2xl mb-2"></i><br>Menarik data terbaru...</td></tr>';

        let url = '/admin/api/monitoring';
        if (examId) url += '?exam_id=' + examId;

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || ''
            }
        })
        .then(res => res.json())
        .then(data => {
            renderTable(data);
        })
        .catch(err => {
            console.error('Error fetching monitoring data:', err);
            monitoringTableBody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-red-500">Gagal menarik data. Silakan coba lagi.</td></tr>';
        })
        .finally(() => {
            if (refreshIcon) refreshIcon.classList.remove('animate-spin');
            if (refreshBtn) refreshBtn.disabled = false;
        });
    };

    /**
     * Render monitoring data in table
     */
    function renderTable(data) {
        if (data.length === 0) {
            monitoringTableBody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada aktivitas siswa saat ini.</td></tr>';
            return;
        }

        monitoringTableBody.innerHTML = data.map(item => {
            let statusBadge = '';
            if (item.status === 'Selesai') {
                statusBadge = '<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase border border-green-100"><i class="ph-bold ph-check"></i> Selesai</span>';
            } else if (item.status === 'Failed') {
                statusBadge = '<span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-xs font-bold uppercase border border-red-100"><i class="ph-bold ph-x"></i> Failed</span>';
            } else {
                statusBadge = '<span class="px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase border border-orange-100"><i class="ph-bold ph-spinner animate-spin"></i> Mengerjakan</span>';
            }

            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 border-b border-gray-50 font-medium text-gray-800">${item.student_name}</td>
                    <td class="p-4 border-b border-gray-50 text-gray-600">${item.student_class}</td>
                    <td class="p-4 border-b border-gray-50 text-gray-600">${item.exam_title}</td>
                    <td class="p-4 border-b border-gray-50">${statusBadge}</td>
                    <td class="p-4 border-b border-gray-50 text-gray-600 font-semibold">${item.progress}</td>
                </tr>
            `;
        }).join('');
    }

    // Event listeners
    if (examFilter) {
        examFilter.addEventListener('change', window.fetchMonitoringData);
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', window.fetchMonitoringData);
    }

    // Initial load
    window.fetchMonitoringData();

    // Auto-refresh every 30 seconds
    autoRefreshInterval = setInterval(window.fetchMonitoringData, 30000);

    // Cleanup on page leave
    window.addEventListener('beforeunload', () => {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    });
});