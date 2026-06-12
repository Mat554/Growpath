<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Tes - Growpath</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="ph ph-arrow-left text-xl"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Hasil Tes</h1>
            </div>
            <p class="text-gray-500 text-sm">Lihat semua laporan hasil tes siswa</p>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[250px]">
                    <div class="relative">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama siswa atau orang tua..."
                            class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>
                </div>
                <div class="w-48">
                    <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition-all">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-semibold transition-all">
                    <i class="ph ph-funnel text-lg"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.laporan.index') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl font-semibold transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Reports Table -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hasil</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Orang Tua</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $report->user->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $report->user->kelas ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-blue-500 font-bold tracking-widest">{{ $report->dominant_code ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($report->user->parents->isNotEmpty())
                                    <div class="flex flex-col gap-1">
                                        @foreach($report->user->parents as $parent)
                                            <div class="text-gray-700 text-sm flex items-center gap-1.5">
                                                <i class="ph ph-user-circle text-blue-400"></i>
                                                {{ $parent->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Belum terhubung</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.laporan.view', $report->id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-all">
                                    <i class="ph ph-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
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
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $reports->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</body>
</html>