<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Tes - Growpath</title>

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
            <a href="{{ route('admin.laporan.index') }}" class="w-full flex items-center gap-3 px-4 py-3 text-[#4A90E2] bg-[#EBF5FF] rounded-xl font-medium transition-all text-left">
                <i class="ph ph-file-text text-lg"></i> Lihat Laporan
            </a>
            <a href="{{ route('admin.monitoring') }}" class="w-full flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-[#4A90E2] rounded-xl font-medium transition-all text-left">
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
                <h2 class="text-2xl font-semibold text-gray-800">Laporan Hasil Tes</h2>
                <p class="text-gray-500 text-sm mt-1">Lihat semua laporan hasil tes siswa</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white p-6 rounded-2xl shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama siswa, hasil tes, atau orang tua..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>
                </div>
                <div class="w-48">
                    <select name="hasil" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#4A90E2] focus:ring-2 focus:ring-blue-100 transition-all">
                        <option value="">Semua Hasil</option>
                        @foreach(['RIA', 'RIS', 'RSA', 'ERA', 'ECS', 'CSI', 'CIS', 'SEC', 'AIR', 'SIA', 'CSE', 'ESA', 'ASI', 'IRE', 'REA'] as $code)
                            <option value="{{ $code }}" {{ request('hasil') == $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-3 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-xl font-semibold transition-all">
                    <i class="ph ph-funnel text-lg"></i> Filter
                </button>
                @if(request('search') || request('hasil') || request('status'))
                    <a href="{{ route('admin.laporan.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Siswa</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Kelas</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Hasil</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Orang Tua</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm">Tanggal</th>
                            <th class="p-4 border-b-2 border-gray-100 text-gray-500 font-semibold text-sm text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-800">{{ $report->user->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ $report->user->kelas ?? '-' }}
                            </td>
                            <td class="p-4">
                                <span class="text-[#4A90E2] font-bold tracking-widest">{{ $report->dominant_code ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                @if($report->user->parents->isNotEmpty())
                                    <div class="flex flex-col gap-1">
                                        @foreach($report->user->parents as $parent)
                                            <div class="text-gray-700 text-sm flex items-center gap-1.5">
                                                <i class="ph ph-user-circle text-[#4A90E2]"></i>
                                                {{ $parent->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Belum terhubung</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500 text-sm">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.laporan.view', $report->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-lg text-sm font-medium transition-all">
                                    <i class="ph ph-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center">
                                <div class="text-gray-400">
                                    <i class="ph ph-clipboard-text text-4xl mb-3 block"></i>
                                    <p>Belum ada laporan yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">
                {{ $reports->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </main>
</body>
</html>
