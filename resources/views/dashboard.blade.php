<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-2">
                <i class="fas fa-th-large text-blue-600"></i>
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium">
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>
    </x-slot>

    {{-- CSS untuk Animasi & Styling Tambahan --}}
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hover-lift {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            {{-- 1. HERO SECTION (Welcome) --}}
            <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-white">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 opacity-90">
                </div>

                {{-- Decorative Shapes --}}
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-20 w-80 h-80 bg-pink-500 opacity-20 rounded-full blur-3xl"></div>

                <div class="relative z-10 p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-white space-y-2">
                        <h1 class="text-4xl font-extrabold tracking-tight">
                            Halo, <span class="text-blue-100">{{ Auth::user()->name }}</span>! 👋
                        </h1>
                        <p class="text-blue-100 text-lg max-w-xl font-light">
                            Selamat datang di pusat kendali <span class="font-semibold">Inventaris Biomedis</span>.
                            Pantau aset, kelola peminjaman, dan cek maintenance dalam satu tampilan.
                        </p>
                        <div class="pt-4 flex gap-3">
                            <a href="{{ route('items.index') }}"
                                class="px-6 py-2.5 bg-white text-blue-700 font-bold rounded-full shadow-lg hover:shadow-xl hover:bg-gray-100 transition transform hover:-translate-y-1">
                                Kelola Aset
                            </a>
                            <a href="{{ route('borrowings.index') }}"
                                class="px-6 py-2.5 bg-blue-800 bg-opacity-30 border border-blue-400 text-white font-semibold rounded-full hover:bg-opacity-50 transition backdrop-blur-md">
                                Riwayat Peminjaman
                            </a>
                        </div>
                    </div>

                    {{-- Quick Date/Time Widget (Visual only) --}}
                    <div
                        class="hidden lg:block bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl text-center text-white w-48 shadow-lg">
                        <div class="text-sm uppercase tracking-widest text-blue-200">Sistem</div>
                        <div class="text-3xl font-black mt-1">ONLINE</div>
                        <div class="mt-2 text-xs bg-green-500 text-white px-2 py-1 rounded-full inline-block">
                            <i class="fas fa-check-circle"></i> Terhubung
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. STATS CARDS (Floating & Colorful) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Total Assets --}}
                <div
                    class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 relative overflow-hidden group hover-lift">
                    <div
                        class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <i class="fas fa-boxes text-6xl text-blue-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Aset</p>
                        <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $totalItems ?? 0 }}</h3>
                        <div class="mt-4 flex items-center text-sm text-blue-600 font-medium">
                            <span>Item Terdaftar</span>
                            <i
                                class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                </div>

                {{-- Active Loans --}}
                <div
                    class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 relative overflow-hidden group hover-lift">
                    <div
                        class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <i class="fas fa-hand-holding text-6xl text-amber-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Sedang Dipinjam</p>
                        <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $activeLoans ?? 0 }}</h3>
                        <div class="mt-4 flex items-center text-amber-600 font-medium">
                            <span>Transaksi Aktif</span>
                            <i
                                class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-amber-600"></div>
                </div>

                {{-- Maintenance --}}
                <div
                    class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 relative overflow-hidden group hover-lift">
                    <div
                        class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <i class="fas fa-tools text-6xl text-red-500"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Maintenance</p>
                        <h3
                            class="text-4xl font-black {{ ($maintenanceCount ?? 0) > 0 ? 'text-red-600' : 'text-gray-800' }} mt-2">
                            {{ $maintenanceCount ?? 0 }}
                        </h3>
                        <div class="mt-4 flex items-center text-red-600 font-medium">
                            <span>Perlu Tindakan</span>
                            <i
                                class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-red-400 to-red-600"></div>
                </div>

                {{-- 3D Printing --}}
                <div
                    class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100 relative overflow-hidden group hover-lift">
                    <div
                        class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <i class="fas fa-cube text-6xl text-purple-600"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Fabrikasi</p>
                        <h3 class="text-4xl font-black text-gray-800 mt-2">{{ $activePrints ?? 0 }}</h3>
                        <div class="mt-4 flex items-center text-purple-600 font-medium">
                            <span>Job Berjalan</span>
                            <i
                                class="fas fa-arrow-right ml-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-1"></i>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT: DATA TABLES --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Alert Section (Overdue) --}}
                    @if(isset($overdueLoans) && count($overdueLoans) > 0)
                        <div
                            class="bg-red-50 rounded-2xl p-6 border border-red-100 shadow-sm flex items-start gap-4 animate-pulse">
                            <div class="bg-red-100 p-3 rounded-full text-red-600">
                                <i class="fas fa-bell text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-700">Peringatan Keterlambatan</h3>
                                <p class="text-sm text-red-600 mb-3">Terdapat {{ count($overdueLoans) }} peminjaman yang
                                    melewati batas waktu pengembalian.</p>
                                <div class="space-y-2">
                                    @foreach($overdueLoans as $loan)
                                        <div
                                            class="bg-white p-3 rounded-lg border border-red-200 shadow-sm flex justify-between items-center">
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $loan->item->name ?? 'Unknown item' }}
                                                </div>
                                                <div class="text-xs text-gray-500">Peminjam:
                                                    {{ $loan->borrower->name ?? 'Unknown' }}
                                                </div>
                                            </div>
                                            <div class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">
                                                Telat {{ \Carbon\Carbon::parse($loan->return_date)->diffForHumans() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Recent Borrowings --}}
                    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
                                <p class="text-xs text-gray-500">5 Peminjaman terakhir yang tercatat</p>
                            </div>
                            <a href="{{ route('borrowings.index') }}"
                                class="text-sm text-blue-600 font-bold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                                        <th class="px-6 py-4 bg-gray-50/30">Peminjam</th>
                                        <th class="px-6 py-4 bg-gray-50/30">Barang</th>
                                        <th class="px-6 py-4 bg-gray-50/30">Waktu</th>
                                        <th class="px-6 py-4 bg-gray-50/30 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($recentBorrowings as $loan)
                                        <tr class="hover:bg-blue-50/30 transition duration-200">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                                        {{ substr($loan->borrower->name ?? '?', 0, 1) }}
                                                    </div>
                                                    <span
                                                        class="font-medium text-gray-700">{{ $loan->borrower->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $loan->item->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M') }}
                                                <span
                                                    class="text-xs text-gray-400 ml-1">({{ \Carbon\Carbon::parse($loan->borrow_date)->format('H:i') }})</span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $statusClass = match ($loan->status) {
                                                        'borrowed' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                        'returned' => 'bg-green-100 text-green-700 border-green-200',
                                                        'late' => 'bg-red-100 text-red-700 border-red-200',
                                                        default => 'bg-gray-100 text-gray-700'
                                                    };
                                                    $statusLabel = match ($loan->status) {
                                                        'borrowed' => 'Dipinjam',
                                                        'returned' => 'Kembali',
                                                        'late' => 'Terlambat',
                                                        default => $loan->status
                                                    };
                                                    if (\Carbon\Carbon::parse($loan->return_date)->isPast() && $loan->status == 'borrowed') {
                                                        $statusClass = 'bg-red-100 text-red-700 border-red-200 animate-pulse';
                                                        $statusLabel = 'Terlambat';
                                                    }
                                                @endphp
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                                Belum ada data peminjaman terbaru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: WIDGETS --}}
                <div class="space-y-8">

                    {{-- 1. CHARTS SECTION (NEW) --}}
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                        <h3
                            class="text-lg font-bold text-gray-800 mb-4 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                            Analisis Data
                        </h3>

                        {{-- Chart 1: Status Barang --}}
                        <div class="mb-8">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Distribusi Aset
                            </h4>
                            <div class="relative h-48">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>

                        {{-- Chart 2: Tren Peminjaman --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Tren Peminjaman
                                (7 Hari)</h4>
                            <div class="relative h-32">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Interactive Quick Actions --}}
                    <div
                        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                        {{-- Background Glow --}}
                        <div
                            class="absolute top-0 right-0 w-40 h-40 bg-blue-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20">
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-40 h-40 bg-purple-500 rounded-full mix-blend-overlay filter blur-3xl opacity-20">
                        </div>

                        <h3 class="text-lg font-bold mb-6 relative z-10">Aksi Cepat</h3>
                        <div class="grid grid-cols-2 gap-4 relative z-10">
                            <a href="{{ route('items.create') }}"
                                class="group bg-white/10 hover:bg-white/20 border border-white/10 p-4 rounded-2xl transition flex flex-col items-center justify-center gap-2 backdrop-blur-sm">
                                <i
                                    class="fas fa-plus text-blue-400 text-2xl group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-semibold">Input Barang</span>
                            </a>
                            <a href="{{ route('borrowings.create') }}"
                                class="group bg-white/10 hover:bg-white/20 border border-white/10 p-4 rounded-2xl transition flex flex-col items-center justify-center gap-2 backdrop-blur-sm">
                                <i
                                    class="fas fa-file-contract text-green-400 text-2xl group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-semibold">Form Pinjam</span>
                            </a>
                            <a href="{{ route('maintenances.index') }}"
                                class="group bg-white/10 hover:bg-white/20 border border-white/10 p-4 rounded-2xl transition flex flex-col items-center justify-center gap-2 backdrop-blur-sm">
                                <i
                                    class="fas fa-screwdriver-wrench text-amber-400 text-2xl group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-semibold">Lapor Rusak</span>
                            </a>
                            <a href="{{ route('reports.index') }}"
                                class="group bg-white/10 hover:bg-white/20 border border-white/10 p-4 rounded-2xl transition flex flex-col items-center justify-center gap-2 backdrop-blur-sm">
                                <i
                                    class="fas fa-print text-purple-400 text-2xl group-hover:scale-110 transition-transform"></i>
                                <span class="text-xs font-semibold">Cetak Laporan</span>
                            </a>
                        </div>
                    </div>

                    {{-- Activity Timeline --}}
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6">
                        <h3
                            class="text-lg font-bold text-gray-800 mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-500">
                            Aktivitas Log
                        </h3>
                        <div class="relative pl-4 border-l-2 border-gray-100 space-y-6">
                            @forelse($recentActivities as $log)
                                <div class="relative">
                                    <div class="absolute -left-[21px] top-1 w-3 h-3 rounded-full border-2 border-white 
                                                    {{ str_contains($log->description, 'Deleted') ? 'bg-red-500' : (str_contains($log->description, 'Created') ? 'bg-green-500' : 'bg-blue-500') }} 
                                                    shadow-sm"></div>
                                    <p class="text-xs text-gray-400 mb-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                    <p class="text-sm font-medium text-gray-800 leading-tight">
                                        {{ $log->description }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        by <span
                                            class="font-semibold text-blue-600">{{ $log->user->name ?? 'System' }}</span>
                                    </p>
                                </div>
                            @empty
                                <div class="text-sm text-gray-400 italic">Belum ada aktivitas.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Scripts for Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. PIE CHART: Status Aset
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Dipinjam', 'Maintenance', 'Hilang/Rusak'],
                    datasets: [{
                        data: [
                            {{ $pieData['available'] ?? 0 }},
                            {{ $pieData['borrowed'] ?? 0 }},
                            {{ $pieData['maintenance'] ?? 0 }},
                            {{ $pieData['lost'] ?? 0 }}
                        ],
                        backgroundColor: ['#3b82f6', '#f59e0b', '#ef4444', '#9ca3af'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10 } } }
                    },
                    cutout: '70%'
                }
            });

            // 2. LINE CHART: Tren Peminjaman
            const ctxTrend = document.getElementById('trendChart').getContext('2d');
            // Gradient Fill
            let gradient = ctxTrend.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartDates ?? []) !!},
                    datasets: [{
                        label: 'Peminjaman',
                        data: {!! json_encode($chartValues ?? []) !!},
                        borderColor: '#2563eb',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                        y: { display: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>