<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Ujian - Growpath</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F6] font-sans flex h-screen overflow-hidden text-[#333]">

    <aside class="w-[260px] bg-white h-full flex flex-col border-r border-gray-200 p-6 z-50 shadow-[0_0_20px_rgba(0,0,0,0.03)]">
        <div class="text-xl font-bold text-[#4A90E2] flex items-center gap-2.5 mb-8">
            <i class="ph-fill ph-gear text-2xl"></i> Admin Panel
        </div>

        <div class="flex-1 flex flex-col gap-1 overflow-y-auto pr-2">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4 mb-2 pl-3">Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-squares-four text-lg"></i> Dashboard
            </a>
            
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2 pl-3">Laporan</div>
            <a href="{{ route('admin.monitoring') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-monitor-play text-lg"></i> Monitoring
            </a>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-medium transition-all mt-4">
                <i class="ph ph-sign-out text-lg"></i> Keluar
            </button>
        </form>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto bg-[#F4F7F6]">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Monitoring Ujian</h2>
                <p class="text-gray-500 text-sm mt-1">Pantau pengerjaan siswa secara langsung.</p>
            </div>
            
            <button onclick="fetchMonitoringData()" id="btnRefresh" class="bg-white px-5 py-2.5 rounded-full shadow-sm flex items-center gap-2 text-sm font-semibold text-[#4A90E2] hover:bg-blue-50 transition-colors border border-blue-100">
                <i class="ph-bold ph-arrows-clockwise text-lg" id="refreshIcon"></i> Refresh Data
            </button>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm">
            
            <div class="mb-6 flex items-center gap-4">
                <label class="text-sm font-medium text-gray-600">Pilih Ujian:</label>
                <select id="examFilter" onchange="fetchMonitoringData()" class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#4A90E2] outline-none min-w-[250px]">
                    <option value="">Semua Ujian Aktif</option>
                    @foreach($activeExams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->title }} (Kelas {{ $exam->target_class }})</option>
                    @endforeach
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Nama Siswa</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Kelas</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Ujian</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Status</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Progres</th>
                        </tr>
                    </thead>
                    <tbody id="monitoringTableBody">
                        <tr><td colspan="5" class="p-8 text-center text-gray-400">Klik Refresh untuk memuat data.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function fetchMonitoringData() {
            const btn = document.getElementById('btnRefresh');
            const icon = document.getElementById('refreshIcon');
            const tbody = document.getElementById('monitoringTableBody');
            const examId = document.getElementById('examFilter').value;
            
            // UI Loading State
            icon.classList.add('animate-spin');
            btn.disabled = true;
            tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400"><i class="ph ph-spinner animate-spin text-2xl mb-2"></i><br>Menarik data terbaru...</td></tr>';

            // Fetch API to Laravel Backend
            fetch(`/admin/api/monitoring?exam_id=${examId}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    
                    if(data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada aktivitas siswa saat ini.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        let statusBadge = '';
                        // Logika Warna Status
                        if(item.status === 'Selesai') {
                            statusBadge = '<span class="px-3 py-1 bg-[#E8F9F5] text-[#2ECC71] rounded-full text-xs font-bold uppercase border border-green-100"><i class="ph-bold ph-check"></i> Selesai</span>';
                        } else if(item.status === 'Failed') {
                            statusBadge = '<span class="px-3 py-1 bg-red-50 text-red-500 rounded-full text-xs font-bold uppercase border border-red-100"><i class="ph-bold ph-x"></i> Failed</span>';
                        } else {
                            statusBadge = '<span class="px-3 py-1 bg-[#FFF4E5] text-[#FF9F43] rounded-full text-xs font-bold uppercase border border-orange-100"><i class="ph-bold ph-spinner animate-spin"></i> Mengerjakan</span>';
                        }

                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-4 border-b border-gray-50 font-medium text-gray-800">${item.student_name}</td>
                                <td class="p-4 border-b border-gray-50 text-gray-600">${item.student_class}</td>
                                <td class="p-4 border-b border-gray-50 text-gray-600">${item.exam_title}</td>
                                <td class="p-4 border-b border-gray-50">${statusBadge}</td>
                                <td class="p-4 border-b border-gray-50 text-gray-600 font-semibold">${item.progress}</td>
                            </tr>
                        `;
                    });
                })
                .catch(err => {
                    console.error("Error fetching monitoring data:", err);
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-red-500">Gagal menarik data. Silakan coba lagi.</td></tr>';
                })
                .finally(() => {
                    icon.classList.remove('animate-spin');
                    btn.disabled = false;
                });
        }

        // Auto-fetch data when the page loads
        document.addEventListener('DOMContentLoaded', fetchMonitoringData);
    </script>
</body>
</html>