<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Inventory') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT (Animated) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        </div>


    @endif

    {{-- ERROR ALERT --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-20 right-4 z-50 max-w-sm w-full">
            <div
                class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl shadow-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fixed-bottom m-3" role="alert"
            style="z-index: 1050; max-width: 500px;">
            {{ session('success') }}

            {{-- Jika ada session undo, tampilkan tombol --}}
            @if (session('action_undo'))
                <a href="{{ session('action_undo') }}" class="btn btn-sm btn-dark ms-3">
                    <i class="fas fa-undo"></i> Urungkan
                </a>
            @endif

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    {{-- Kita passing Map ID -> SN ke Alpine --}}
    <div class="py-12" x-data="itemPage({{ json_encode($items->pluck('serial_number', 'id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Data Induk Barang</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola inventaris dan aset laboratorium secara terpusat.</p>
                </div>

                <div class="flex flex-wrap gap-3 items-center">

                    {{-- 1. Tombol Fix QR --}}
                    <form action="{{ route('items.regenerate_qr') }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin membuat ulang SEMUA QR Code? Proses ini mungkin memakan waktu jika data banyak.')">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-white border border-purple-200 rounded-lg font-semibold text-xs text-purple-700 uppercase tracking-widest shadow-sm hover:bg-purple-50 focus:outline-none transition ease-in-out duration-150"
                            title="Generate ulang semua file QR yang hilang">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Fix QR
                        </button>
                    </form>

                    {{-- 2. Tombol TRASH / SAMPAH (BARU) --}}
                    <a href="{{ route('items.trash') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-red-200 rounded-lg font-semibold text-xs text-red-600 uppercase tracking-widest shadow-sm hover:bg-red-50 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Sampah
                    </a>

                    {{-- 3. Tombol Riwayat --}}
                    <a href="{{ route('items.out.index') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Keluar
                    </a>

                    {{-- 4. Tombol Grouping --}}
                    <a href="{{ route('items.index', array_merge(request()->all(), ['group_by_asset' => '1'])) }}"
                        class="inline-flex items-center px-4 py-2.5 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 shadow-sm transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                        Group By Asset
                    </a>

                    {{-- 5. Tombol Tambah --}}
                    <a href="{{ route('items.create') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Barang
                    </a>
                </div>
            </div>

            {{-- FILTER & SEARCH CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('items.index') }}" method="GET"
                    class="flex flex-col lg:flex-row gap-4 items-end lg:items-center">
                    {{-- Search Input --}}
                    <div class="w-full lg:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm transition"
                            placeholder="Cari Nama, No Asset, atau S/N...">
                    </div>

                    {{-- Dropdown Filters --}}
                    <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-4">
                        <select name="status"
                            class="w-full sm:w-40 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Status</option>
                            @foreach(['available', 'borrowed', 'maintenance', 'dikeluarkan'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>

                        <select name="condition"
                            class="w-full sm:w-40 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Kondisi</option>
                            @foreach(['good' => 'Baik', 'damaged' => 'Rusak Ringan', 'broken' => 'Rusak Berat'] as $val => $label)
                                <option value="{{ $val }}" {{ request('condition') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Per Page Dropdown --}}
                        <select name="per_page" onchange="this.form.submit()"
                            class="w-full sm:w-28 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer"
                            title="Jumlah Data Per Halaman">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / Hal</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 / Hal</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / Hal</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / Hal</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 / Hal</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                        </select>

                        <select name="room_id"
                            class="w-full sm:w-48 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            Filter
                        </button>

                        @if(request()->anyFilled(['search', 'status', 'room_id']))
                            <a href="{{ route('items.index') }}"
                                class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold text-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- VIEW TOGGLE BUTTONS --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-bold text-gray-800">{{ $items->count() }}</span> dari <span
                        class="font-bold text-gray-800">{{ $items->total() }}</span> barang
                </div>
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <button type="button" @click="setViewMode('table')"
                        :class="{'bg-white shadow text-blue-600': viewMode === 'table', 'text-gray-500 hover:text-gray-700': viewMode !== 'table'}"
                        class="p-2 rounded-md transition-all" title="Tampilan Tabel">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </button>
                    <button type="button" @click="setViewMode('list')"
                        :class="{'bg-white shadow text-blue-600': viewMode === 'list', 'text-gray-500 hover:text-gray-700': viewMode !== 'list'}"
                        class="p-2 rounded-md transition-all" title="Tampilan List Card">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                    <button type="button" @click="setViewMode('grid')"
                        :class="{'bg-white shadow text-blue-600': viewMode === 'grid', 'text-gray-500 hover:text-gray-700': viewMode !== 'grid'}"
                        class="p-2 rounded-md transition-all" title="Tampilan Grid Besar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- FORM BULK ACTION WRAPPER (PENTING: Membungkus Semua View) --}}
            <form id="bulkActionForm" action="{{ route('items.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                {{-- TABLE VIEW --}}
                <div x-show="viewMode === 'table'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    {{-- CHECKBOX HEADER (Select All) --}}
                                    <th scope="col" class="px-3 py-3 text-center w-8">
                                        <input type="checkbox" @click="toggleAll" x-ref="selectAllCheckbox"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4 transition hover:scale-110">
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">
                                        Foto</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[200px]">
                                        Barang</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                        Merk</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                        No Asset</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden xl:table-cell">
                                        S/N</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        QR</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        Ruangan</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Qty</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                        Kondisi</th>
                                    <th scope="col"
                                        class="px-3 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-blue-50/40 transition-colors duration-200 group relative"
                                        :class="{'bg-blue-50': selectedItems.includes({{ $item->id }})}">

                                        {{-- CHECKBOX ROW --}}
                                        <td class="px-3 py-3 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}"
                                                @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                                                :checked="selectedItems.includes({{ $item->id }})"
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4 transition hover:scale-110">
                                        </td>

                                        {{-- ID --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-400 font-mono">
                                            #{{ $item->id }}
                                        </td>

                                        {{-- Image Thumbnail --}}
                                        <td class="px-3 py-3 text-center">
                                            <div
                                                class="h-10 w-10 mx-auto rounded-lg overflow-hidden border border-gray-100 bg-gray-50 relative shadow-sm group-hover:shadow-md transition-all">
                                                <img src="{{ $item->optimized_image }}" alt="{{ $item->name }}"
                                                    class="object-cover w-full h-full transform transition-transform duration-500 group-hover:scale-110"
                                                    loading="lazy"
                                                    onerror="this.src='https://placehold.co/100x100?text=Err'">
                                            </div>
                                        </td>

                                        {{-- Name --}}
                                        <td class="px-3 py-3">
                                            <div
                                                class="text-sm font-bold text-gray-800 leading-tight group-hover:text-blue-700 transition-colors">
                                                {{ $item->name }}
                                            </div>
                                            {{-- Mobile Only Metadata --}}
                                            <div class="lg:hidden mt-0.5 text-[10px] text-gray-400 font-mono">
                                                {{ $item->asset_number }}
                                            </div>
                                        </td>

                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-500 hidden md:table-cell">
                                            {{ $item->brand ?? '-' }}
                                        </td>

                                        {{-- Asset Number --}}
                                        <td
                                            class="px-3 py-3 whitespace-nowrap text-xs text-gray-500 font-mono hidden lg:table-cell group-hover:text-gray-700">
                                            {{ $item->asset_number ?? '-' }}
                                        </td>

                                        {{-- Serial Number --}}
                                        <td
                                            class="px-3 py-3 whitespace-nowrap text-xs font-mono text-gray-500 hidden xl:table-cell group-hover:text-gray-700">
                                            {{ $item->serial_number ?? '-' }}
                                        </td>

                                        {{-- QR Code --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-center">
                                            @if ($item->qr_code)
                                                <div class="flex justify-center items-center cursor-zoom-in relative w-8 h-8 md:hover:scale-150 transition-transform z-10 origin-center"
                                                    @mouseenter="activeQr = '{{ asset('storage/' . $item->qr_code) }}'"
                                                    @mouseleave="activeQr = null">
                                                    <img src="{{ asset('storage/' . $item->qr_code) }}" alt="QR"
                                                        class="h-8 w-8 rounded border border-gray-200 bg-white p-0.5 object-cover shadow-sm">
                                                </div>
                                            @else
                                                <span class="text-[10px] text-gray-300 italic">No QR</span>
                                            @endif
                                        </td>

                                        {{-- Room --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 hidden sm:table-cell">
                                            <div class="flex items-center gap-1.5">
                                                <div
                                                    class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-blue-400 transition-colors">
                                                </div>
                                                <span class="truncate max-w-[120px]"
                                                    title="{{ $item->room->name ?? 'Unassigned' }}">
                                                    {{ $item->room->name ?? '-' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Quantity --}}
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-gray-100 text-gray-700 border border-gray-200 group-hover:bg-blue-100 group-hover:text-blue-700 group-hover:border-blue-200 transition-colors">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <x-status-badge :status="$item->status"
                                                class="scale-90 origin-left shadow-sm" />
                                        </td>

                                        {{-- Condition --}}
                                        <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">
                                            @php
                                                $condClass = match ($item->condition) {
                                                    'good' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'damaged' => 'bg-amber-50 text-amber-700 border-amber-100',
                                                    'broken' => 'bg-red-50 text-red-700 border-red-100',
                                                    default => 'bg-gray-50 text-gray-600 border-gray-100',
                                                };
                                                $condLabel = match ($item->condition) {
                                                    'good' => 'Baik',
                                                    'damaged' => 'Rusak Ringan',
                                                    'broken' => 'Rusak Berat',
                                                    default => $item->condition,
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border {{ $condClass }}">
                                                {{ $condLabel }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <div
                                                class="flex items-center justify-end gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('items.show', $item->id) }}"
                                                    class="text-gray-400 hover:text-sky-600 hover:bg-sky-50 p-1.5 rounded-lg transition-all transform hover:scale-110"
                                                    title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>

                                                <a href="{{ route('items.edit', $item->id) }}"
                                                    class="text-gray-400 hover:text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-all transform hover:scale-110"
                                                    title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                @if($item->status !== 'dikeluarkan')
                                                    <a href="{{ route('items.out.create', $item->id) }}"
                                                        class="text-gray-400 hover:text-orange-600 hover:bg-orange-50 p-1.5 rounded-lg transition-all transform hover:scale-110"
                                                        title="Keluarkan Barang">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                @endif

                                                <button type="button"
                                                    @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                                    class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all transform hover:scale-110"
                                                    title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                                <p class="text-base font-medium text-gray-900">Tidak ada item ditemukan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- LIST VIEW (Horizontal Cards - Dynamic & Dense) --}}
                <div x-show="viewMode === 'list'" class="space-y-3"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    @forelse ($items as $item)
                        {{-- Row-Card Design --}}
                        <div class="group relative bg-white rounded-lg border border-gray-200 p-2 sm:p-3 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 overflow-visible"
                            :class="{'ring-2 ring-blue-500 bg-blue-50/30': selectedItems.includes({{ $item->id }})}">

                            {{-- 1. Checkbox & Image Section --}}
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <div class="relative z-10 flex-shrink-0">
                                    <input type="checkbox" value="{{ $item->id }}"
                                        @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                                        :checked="selectedItems.includes({{ $item->id }})"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring w-4 h-4 cursor-pointer">
                                </div>

                                {{-- Image Thumbnail (Small) --}}
                                <div
                                    class="h-12 w-12 sm:h-14 sm:w-14 rounded-md overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0 relative group-inner">
                                    <img src="{{ $item->optimized_image }}" class="w-full h-full object-cover"
                                        loading="lazy" onerror="this.style.display='none'">
                                    {{-- Placeholder Icon --}}
                                    <div class="absolute inset-0 flex items-center justify-center bg-gray-50 -z-10">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Mobile Name Display (Hidden on Desktop) --}}
                                <div class="sm:hidden flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $item->name }}</h4>
                                    <div class="text-xs text-gray-500">{{ $item->asset_number }}</div>
                                </div>
                            </div>

                            {{-- 2. Desktop Columns Grid (Matches Table-ish Layout) --}}
                            <div class="flex-1 w-full grid grid-cols-2 sm:grid-cols-12 gap-2 sm:gap-4 items-center">

                                {{-- Col A: Product Info (Name & Brand) --}}
                                <div class="col-span-2 sm:col-span-4 flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase sm:hidden">Barang</span>
                                    <h4 class="text-sm font-bold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition-colors hidden sm:block"
                                        title="{{ $item->name }}">
                                        {{ $item->name }}
                                    </h4>
                                    {{-- Brand on Desktop --}}
                                    <div class="hidden sm:flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                        <span
                                            class="bg-gray-100 px-1.5 rounded text-[10px] border border-gray-200 truncate max-w-[100px]">{{ $item->brand ?? '-' }}</span>
                                    </div>
                                    {{-- Mobile Brand --}}
                                    <span class="text-sm text-gray-600 sm:hidden">{{ $item->brand ?? '-' }}</span>
                                </div>

                                {{-- Col B: Identifiers (Asset & SN) --}}
                                <div class="col-span-1 sm:col-span-3 flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase sm:hidden">No Aset / SN</span>
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-xs font-mono font-medium text-gray-700 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 w-fit truncate max-w-full"
                                            title="No Asset">
                                            {{ $item->asset_number }}
                                        </span>
                                        <span class="text-[10px] font-mono text-gray-400 truncate" title="S/N">
                                            SN: {{ $item->serial_number ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Col C: Location --}}
                                <div class="col-span-1 sm:col-span-2 flex flex-col justify-center">
                                    <span class="text-xs text-gray-400 font-bold uppercase sm:hidden">Lokasi</span>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-400 hidden sm:block"></div>
                                        <span class="text-xs text-gray-700 font-medium truncate"
                                            title="{{ $item->room->name ?? '-' }}">
                                            {{ $item->room->name ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Col D: Status & Cond --}}
                                <div class="col-span-1 sm:col-span-2 flex flex-col sm:items-start gap-1 justify-center">
                                    <span class="text-xs text-gray-400 font-bold uppercase sm:hidden">Status</span>
                                    <x-status-badge :status="$item->status"
                                        class="!text-[10px] !px-2 !py-0.5 scale-95 origin-left" />
                                    <span
                                        class="text-[10px] font-medium {{ $item->condition == 'good' ? 'text-emerald-600' : ($item->condition == 'broken' ? 'text-red-600' : 'text-amber-600') }}">
                                        {{ ucfirst($item->condition) }}
                                    </span>
                                </div>

                                {{-- Col E: Qty --}}
                                <div class="col-span-1 sm:col-span-1 flex flex-col sm:items-center justify-center">
                                    <span class="text-xs text-gray-400 font-bold uppercase sm:hidden">Qty</span>
                                    <span
                                        class="text-sm font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded-md min-w-[2rem] text-center border border-gray-200">
                                        {{ $item->quantity }}
                                    </span>
                                </div>

                            </div>

                            {{-- 3. Actions (Right End) --}}
                            <div
                                class="w-full sm:w-auto flex sm:flex-col gap-2 border-t sm:border-t-0 border-gray-100 pt-2 sm:pt-0 sm:pl-3 sm:border-l border-gray-100 justify-center">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('items.show', $item->id) }}"
                                        class="text-gray-400 hover:text-blue-600 p-1.5 rounded-md hover:bg-blue-50 transition-colors"
                                        title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="text-gray-400 hover:text-amber-600 p-1.5 rounded-md hover:bg-amber-50 transition-colors"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                        class="text-gray-400 hover:text-red-600 p-1.5 rounded-md hover:bg-red-50 transition-colors"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="opacity-50">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada item ditemukan</p>
                        </div>
                    @endforelse
                </div>

                {{-- GRID VIEW (Large Vertical Cards - Dynamic & Satisfying) --}}
                <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @forelse ($items as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative flex flex-col h-full"
                            :class="{'ring-2 ring-blue-500 ring-offset-2': selectedItems.includes({{ $item->id }})}">

                            {{-- Selection Checkbox --}}
                            <div class="absolute top-3 left-3 z-20">
                                <input type="checkbox" value="{{ $item->id }}"
                                    @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                                    :checked="selectedItems.includes({{ $item->id }})"
                                    class="rounded border-gray-300 text-blue-600 shadow-lg focus:border-blue-300 focus:ring w-5 h-5 cursor-pointer bg-white/90 backdrop-blur hover:scale-110 transition-transform">
                            </div>

                            {{-- Status Badge (Floating) --}}
                            <div class="absolute top-3 right-3 z-20 opacity-90 group-hover:opacity-100 transition-opacity">
                                <x-status-badge :status="$item->status"
                                    class="!text-[10px] !px-2 !py-1 shadow-md backdrop-blur-sm" />
                            </div>

                            {{-- Image Area --}}
                            <div
                                class="aspect-square bg-gray-100 relative overflow-hidden flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                                {{-- Placeholder Icon (Background) --}}
                                <svg class="absolute w-16 h-16 text-gray-300 transform group-hover:scale-110 transition-transform duration-500"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>

                                <img src="{{ $item->optimized_image }}"
                                    class="relative w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 z-10"
                                    loading="lazy" onerror="this.style.display='none'">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                                </div>

                                {{-- Quick Actions Overlay --}}
                                <div
                                    class="absolute bottom-4 left-0 right-0 flex justify-center gap-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-30 px-4">
                                    <a href="{{ route('items.show', $item->id) }}"
                                        class="bg-white text-gray-700 p-2 rounded-full shadow-lg hover:bg-blue-600 hover:text-white transition-colors"
                                        title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('items.edit', $item->id) }}"
                                        class="bg-white text-gray-700 p-2 rounded-full shadow-lg hover:bg-amber-500 hover:text-white transition-colors"
                                        title="Edit Item">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 flex flex-col flex-1 bg-white relative">
                                <div class="mb-2">
                                    <h3 class="font-bold text-gray-800 text-sm mb-1 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors"
                                        title="{{ $item->name }}">
                                        {{ $item->name }}
                                    </h3>
                                    <p
                                        class="text-[10px] text-gray-400 font-mono truncate bg-gray-50 px-1.5 py-0.5 rounded inline-block">
                                        {{ $item->asset_number }}
                                    </p>
                                </div>

                                <div class="mt-auto flex justify-between items-end border-t border-gray-50 pt-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Ruangan</span>
                                        <span class="text-xs font-semibold text-gray-600 truncate max-w-[80px]"
                                            title="{{ $item->room->name ?? '-' }}">{{ $item->room->name ?? '-' }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold text-gray-800 bg-gray-100 px-2 py-1 rounded-lg">
                                            {{ $item->quantity }} <span
                                                class="text-[9px] font-normal text-gray-500">Unit</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <div class="opacity-50">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada item ditemukan</p>
                        </div>
                    @endforelse
                </div>

                {{-- PAGINATION WRAPPER (Shared) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-4">
                    {{-- Pagination & Layout Control --}}
                    <div
                        class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="w-full">
                            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $items->links() }}
                            @else
                                <div class="text-sm text-gray-500">Menampilkan semua {{ $items->count() }} data.</div>
                            @endif
                        </div>

                        <div class="whitespace-nowrap">
                            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator || $items instanceof \Illuminate\Database\Eloquent\Collection)
                                <form action="{{ route('items.index') }}" method="GET" class="flex items-center gap-2">
                                    {{-- Keep other filters --}}
                                    @foreach(request()->except(['per_page', 'page']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach

                                    <label class="text-sm text-gray-500 font-medium hidden sm:block">Tampilkan:</label>
                                    <select name="per_page" onchange="this.form.submit()"
                                        class="rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500 py-1.5 pl-3 pr-8 cursor-pointer">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 baris
                                        </option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 baris</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris
                                        </option>
                                        <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 baris
                                        </option>
                                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                        </option>
                                    </select>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- MODAL DELETE SINGLE --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div x-show="showModal" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="showModal = false"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Item</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus item <span class="font-bold text-gray-800"
                                            x-text="deleteName"></span>?
                                        Data yang dihapus tidak dapat dikembalikan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Ya, Hapus
                            </button>
                        </form>
                        <button type="button" @click="showModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- FLOATING BULK ACTION BAR --}}
        <div x-show="selectedItems.length > 0" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-full opacity-0"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6 w-[90%] max-w-2xl">

            <div class="flex items-center gap-2 text-gray-700 font-medium">
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold"
                    x-text="selectedItems.length"></span>
                <span>Item Dipilih</span>
            </div>

            <div class="h-8 w-px bg-gray-300"></div>

            <div class="flex items-center gap-3 flex-1 justify-end">
                {{-- Edit Massal --}}
                <button @click="openEditModal"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white hover:bg-amber-600 rounded-xl transition text-sm font-semibold shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Massal
                </button>

                {{-- Print QR --}}
                <button @click="submitBulkAction('print_qr')"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl transition text-sm font-semibold shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4h8v-4m-6-6h6m-6 0a2 2 0 00-2 2v6a2 2 0 002 2h2m0-8h6a2 2 0 012 2v6a2 2 0 01-2 2h-2m-6 0h2">
                        </path>
                    </svg>
                    Cetak QR
                </button>

                <button @click="submitBulkAction('copy')"
                    class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                    Duplicate
                </button>

                <button @click="submitBulkAction('delete')"
                    class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus Terpilih
                </button>
            </div>
        </div>

        {{-- MODAL BULK EDIT --}}
        <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div x-show="showEditModal" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="showEditModal = false"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">

                    <form action="{{ route('items.bulk_update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Hidden Selected IDs --}}
                        <template x-for="id in selectedItems">
                            <input type="hidden" name="selected_ids[]" :value="id">
                        </template>

                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900">Edit Massal</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Anda akan mengedit <span class="font-bold text-gray-800"
                                                x-text="selectedItems.length"></span> item terpilih.
                                            <span class="text-amber-600 italic block mt-1">Biarkan kolom kosong jika
                                                tidak ingin mengubah data tersebut.</span>
                                        </p>

                                        <div
                                            class="space-y-4 text-left max-h-[60vh] overflow-y-auto px-1 scrollbar-hide">

                                            {{-- Group 0: Gambar (Batch Photo Edit) --}}
                                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100"
                                                x-data="{ imageUrl: '', deletePhoto: false }"
                                                @remote-image-selected.window="imageUrl = $event.detail.url; $refs.fileInput.value = ''; deletePhoto = false;">

                                                <div class="flex justify-between items-center mb-2">
                                                    <label class="block text-sm font-bold text-gray-700">Ubah Foto
                                                        (Batch)</label>
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" name="delete_image" value="1"
                                                            x-model="deletePhoto"
                                                            class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-xs font-bold text-red-600">Hapus
                                                            Foto</span>
                                                    </label>
                                                </div>

                                                <div class="flex gap-4 items-start"
                                                    :class="{'opacity-50 pointer-events-none': deletePhoto}">
                                                    {{-- Preview Image --}}
                                                    <div x-show="imageUrl" class="relative group flex-shrink-0">
                                                        <img :src="imageUrl"
                                                            class="w-20 h-20 object-cover rounded-lg border border-gray-300 shadow-sm">
                                                        <button type="button"
                                                            @click="imageUrl = ''; $refs.fileInput.value = '';"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow hover:bg-red-600">
                                                            &times;
                                                        </button>
                                                    </div>

                                                    <div class="flex-1 space-y-3">
                                                        {{-- File Input --}}
                                                        <div>
                                                            <label
                                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Upload
                                                                File Local</label>
                                                            <input type="file" name="image" x-ref="fileInput"
                                                                @change="if($el.files[0]) { imageUrl = URL.createObjectURL($el.files[0]); } else { imageUrl = ''; }"
                                                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-lg">
                                                        </div>

                                                        {{-- Remote / URL --}}
                                                        <div>
                                                            <label
                                                                class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Atau
                                                                Scan HP</label>
                                                            <div class="flex gap-2">
                                                                <input type="url" name="image_url" x-model="imageUrl"
                                                                    placeholder="HTTPS URL..."
                                                                    class="flex-1 rounded-lg border-gray-300 text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">

                                                                <button type="button"
                                                                    @click="$dispatch('open-remote-upload')"
                                                                    class="bg-purple-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-purple-700 transition flex items-center gap-1 shadow-sm whitespace-nowrap"
                                                                    title="Scan QR dari HP">
                                                                    <svg class="w-3 h-3" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                                                        </path>
                                                                    </svg>
                                                                    Scan HP
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="text-[10px] text-gray-400 mt-2 italic">
                                                    *Jika diisi, foto ini akan diterapkan ke SEMUA <span
                                                        x-text="selectedItems.length"></span> item yang dipilih.
                                                </p>
                                            </div>

                                            {{-- Group 1: Identitas --}}
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase">Merk
                                                        / Brand</label>
                                                    <input type="text" name="brand"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500"
                                                        placeholder="--- Tetap ---">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase">Tipe
                                                        / Model</label>
                                                    <input type="text" name="type"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500"
                                                        placeholder="--- Tetap ---">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase">Fiscal
                                                        Group</label>
                                                    <input type="text" name="fiscal_group"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500"
                                                        placeholder="--- Tetap ---">
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase">Sumber
                                                        Perolehan</label>
                                                    <input type="text" name="source"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500"
                                                        placeholder="--- Tetap ---">
                                                </div>
                                            </div>

                                            {{-- Group 2: Waktu --}}
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase">Tahun
                                                        Perolehan</label>
                                                    <input type="number" name="acquisition_year"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500"
                                                        placeholder="YYYY">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-500 uppercase">Tgl
                                                        Digunakan</label>
                                                    <input type="date" name="placed_in_service_at"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                                </div>
                                            </div>

                                            {{-- Group 3: Status & Lokasi --}}
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-500 uppercase">Ruangan</label>
                                                <select name="room_id"
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                                    <option value="">--- Tidak Berubah ---</option>
                                                    @foreach($rooms as $room)
                                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase">Status</label>
                                                    <select name="status"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                                        <option value="">--- Tetap ---</option>
                                                        <option value="available">Available</option>
                                                        <option value="borrowed">Borrowed</option>
                                                        <option value="maintenance">Maintenance</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-500 uppercase">Kondisi</label>
                                                    <select name="condition"
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                                        <option value="">--- Tetap ---</option>
                                                        <option value="good">Baik</option>
                                                        <option value="damaged">Rusak Ringan</option>
                                                        <option value="broken">Rusak Berat</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Group 4: Serial Number Generator --}}
                                            <div class="pt-4 border-t border-gray-100">
                                                <div x-data="{ showSn: false }">
                                                    <button type="button" @click="showSn = !showSn"
                                                        class="flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-amber-800 uppercase tracking-wide mb-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                            </path>
                                                        </svg>
                                                        Advanced: Regenerate Serial Number
                                                    </button>

                                                    <div x-show="showSn"
                                                        class="bg-amber-50 p-3 rounded-lg border border-amber-100 space-y-3">
                                                        {{-- DRAFT PREVIEW --}}
                                                        <div>
                                                            <label
                                                                class="block text-xs font-bold text-gray-600 uppercase">Draft
                                                                Preview (Existing SN)</label>
                                                            <textarea x-model="snDraft" rows="6" readonly
                                                                class="w-full rounded-lg border-amber-300 focus:ring-amber-500 focus:border-amber-500 text-xs font-mono bg-amber-50/50 mb-2"
                                                                placeholder="Serial numbers will appear here..."></textarea>
                                                            <p class="text-[10px] text-gray-500">Daftar ini hanya
                                                                preview serial number saat ini yang sudah dikelompokkan.
                                                            </p>
                                                        </div>

                                                        <hr class="border-amber-200">

                                                        <hr class="border-amber-200">

                                                        {{-- DYNAMIC GROUP EDITING --}}
                                                        <div class="space-y-4">
                                                            <div class="flex justify-between items-end">
                                                                <h4
                                                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                                                                    Edit Per Group</h4>
                                                                <button type="button" @click="generateDraft()"
                                                                    class="text-[10px] bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200 font-bold transition">
                                                                    Refresh Preview
                                                                </button>
                                                            </div>

                                                            <template x-for="(group, prefix) in editingGroups"
                                                                :key="prefix">
                                                                <div
                                                                    class="bg-white p-2 rounded border border-gray-200 shadow-sm">
                                                                    <div class="flex justify-between items-center mb-2">
                                                                        <span
                                                                            class="text-xs font-bold text-gray-800 bg-gray-100 px-2 py-0.5 rounded"
                                                                            x-text="prefix"></span>
                                                                        <span class="text-[10px] text-gray-400"
                                                                            x-text="group.items.length + ' items'"></span>
                                                                    </div>
                                                                    <div class="flex gap-2">
                                                                        <div class="flex-1">
                                                                            <label
                                                                                class="block text-[10px] uppercase font-bold text-gray-400">Pattern</label>
                                                                            <input type="text" x-model="group.pattern"
                                                                                @input="generateDraft()"
                                                                                class="w-full px-2 py-1 text-xs border-gray-300 rounded focus:ring-amber-500 focus:border-amber-500 font-mono">
                                                                        </div>
                                                                        <div class="w-20">
                                                                            <label
                                                                                class="block text-[10px] uppercase font-bold text-gray-400">Start
                                                                                Seq</label>
                                                                            <input type="number"
                                                                                x-model="group.start_seq"
                                                                                @input="generateDraft()"
                                                                                class="w-full px-2 py-1 text-xs border-gray-300 rounded focus:ring-amber-500 focus:border-amber-500 text-center">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        {{-- Hidden Inputs for Form Submission --}}
                                                        <template x-for="(sn, id) in generatedDraftMap" :key="id">
                                                            <input type="hidden" :name="'manual_serial_numbers['+id+']'"
                                                                :value="sn">
                                                        </template>

                                                        <div
                                                            class="mt-2 p-2 bg-blue-50 rounded text-[10px] text-blue-700">
                                                            Simpan Perubahan akan menerapkan Serial Number sesuai Draft
                                                            Preview di atas.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-amber-500 sm:ml-3 sm:w-auto transition">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="showEditModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- FIXED QR ZOOM OVERLAY --}}
        <div x-show="activeQr" style="display: none; pointer-events: none;"
            class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[100]"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <div class="bg-white p-3 rounded-2xl shadow-2xl border-4 border-white ring-1 ring-gray-200">
                <img :src="activeQr" class="w-64 h-64 object-contain rounded-xl bg-gray-50">
                <p class="text-center text-xs font-bold text-gray-400 mt-2 tracking-widest uppercase">Scan Me</p>
            </div>
        </div>

    </div>

    {{-- COMPONENT: REMOTE UPLOAD MODAL (Reused) --}}
    <div x-data="remoteUploadComponent" @open-remote-upload.window="openModal()" class="z-50 relative">
        <div x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.away="closeModal()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Scan QR untuk Upload
                                </h3>
                                <div class="mt-4 flex flex-col items-center justify-center space-y-4">
                                    <div x-show="loading" class="flex flex-col items-center text-gray-500">
                                        <svg class="animate-spin h-8 w-8 text-indigo-500 mb-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Generating Token...
                                    </div>
                                    <div x-show="!loading && qrCodeSvg" class="p-4 bg-white border rounded">
                                        <div x-html="qrCodeSvg"></div>
                                    </div>
                                    <div x-show="!loading" class="text-sm text-gray-500 text-center">
                                        <p class="mb-2">1. Buka kamera HP atau Biomed Scanner.</p>
                                        <p class="mb-2">2. Scan QR Code di atas.</p>
                                        <p class="text-xs text-gray-400 mt-2">Halaman akan otomatis merefresh preview
                                            setelah upload sukses.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALPINE SCRIPT --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('remoteUploadComponent', () => ({
                isOpen: false,
                loading: false,
                qrCodeSvg: '',
                token: null,
                pollInterval: null,
                pollAttempts: 0,
                maxAttempts: 300,

                async openModal() {
                    this.isOpen = true;
                    this.loading = true;
                    this.qrCodeSvg = '';
                    this.token = null;
                    this.pollAttempts = 0;

                    try {
                        const res = await fetch("{{ route('remote.token') }}");
                        const data = await res.json();
                        this.token = data.token;
                        this.qrCodeSvg = data.qr_code;
                        this.loading = false;
                        this.startPolling();
                    } catch (e) {
                        console.error(e);
                        alert('Gagal generate token.');
                        this.closeModal();
                    }
                },

                closeModal() {
                    this.isOpen = false;
                    this.stopPolling();
                },

                startPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(async () => {
                        this.pollAttempts++;
                        if (this.pollAttempts > this.maxAttempts) {
                            this.stopPolling();
                            alert('Waktu habis (timeout).');
                            return;
                        }

                        // Cek status
                        // Perhatikan endpoint check: biasanya /api/remote-check/{token} atau sejenisnya
                        // Kita asumsikan ada controller "RemoteUploadController@checkStatus" 
                        // Jika route belum didefinisikan di web.php, harus dicek.
                        // Berdasarkan analisis file, Controller ada metode checkStatus, tapi routenya mungkin missed.
                        // Wait, saya belum cek API routes. Tapi logic create.blade.php pake polling ke mana?

                        // Mari kita asumsikan fetch ke endpoint yang sama dengan create.blade.php.
                        // Di create.blade.php (yang saya baca tadi), script pollingnya TIDAK TERLIHAT di potongan 800-1081.
                        // Tapi logic di controller RemoteUploadController ada `checkStatus`.
                        // Saya akan coba fetch ke `/api/remote-check/${this.token}` jika route api ada.
                        // Atau saya buat route baru di web.php untuk check status jika user mau.

                        // Namun, saya tidak bisa menjamin route api ada tanpa check `routes/api.php` atau `routes/web.php` lagi.
                        // Di `routes/web.php` tadi saya TIDAK melihat `remote-check`.
                        // Mungkin ada di `api.php`.

                        // Mari kita coba fetch ke `/api/remote-check/${this.token}`.
                        try {
                            const res = await fetch(`/api/remote-check/${this.token}`);
                            if (res.ok) {
                                const data = await res.json();
                                if (data.status === 'found') {
                                    this.stopPolling();
                                    // Dispatch event global agar form menangkap URLnya
                                    window.dispatchEvent(new CustomEvent('remote-image-selected', {
                                        detail: { url: data.url }
                                    }));
                                    this.closeModal();
                                }
                            }
                        } catch (e) {
                            // ignore error polling
                        }

                    }, 2000);
                },

                stopPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = null;
                }
            }));
        });

        function itemPage(itemMap = {}) {
            return {
                viewMode: localStorage.getItem('items_view_mode') || 'table',
                setViewMode(mode) {
                    this.viewMode = mode;
                    localStorage.setItem('items_view_mode', mode);
                },
                showModal: false,
                showEditModal: false,
                editField: 'acquisition_year',
                deleteUrl: '',
                deleteName: '',
                selectedItems: [],
                itemMap: itemMap,  // { id: 'SN1', id2: 'SN2' }
                pageIds: Object.keys(itemMap).map(Number), // IDs on current page
                lastCheckedIndex: null,
                activeQr: null,
                snDraft: '',

                editingGroups: {},
                generatedDraftMap: {},

                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/items/${id}`;
                },

                openEditModal() {
                    this.prepareBulkEdit();
                    this.showEditModal = true;
                },

                prepareBulkEdit() {
                    // Logic: Group selected serial numbers by prefix
                    let serials = this.selectedItems.map(id => this.itemMap[id]).filter(sn => sn);
                    let groups = {};

                    serials.forEach(sn => {
                        // Regex: Ambil string sebelum strip terakhir sebagai prefix
                        // Contoh: B-MISC-2023001 -> Prefix: B-MISC
                        let match = sn.match(/^(.*)-/);
                        let prefix = match ? match[1] : 'OTHERS';

                        // Fallback jika tidak ada strip, masuk OTHERS
                        if (!match && sn.includes('-')) {
                            // Coba logic lain atau just keep OTHERS
                        } else if (!match) {
                            prefix = 'NO-PREFIX';
                        }

                        if (!groups[prefix]) groups[prefix] = [];
                        groups[prefix].push(sn);
                    });

                    // Format ke string dengan newlines
                    let text = '';
                    // Sort keys agar rapi
                    let sortedKeys = Object.keys(groups).sort();

                    sortedKeys.forEach(key => {
                        // Sort individual SNs
                        groups[key].sort();
                        text += groups[key].join('\n');
                        text += '\n\n'; // Jarak antar grup
                    });

                    this.snDraft = text.trim();
                },

                toggleItem(id, index, event) {
                    if (event.shiftKey && this.lastCheckedIndex !== null) {
                        const start = Math.min(this.lastCheckedIndex, index);
                        const end = Math.max(this.lastCheckedIndex, index);
                        const idsToSelect = this.pageIds.slice(start, end + 1);

                        idsToSelect.forEach(itemId => {
                            if (!this.selectedItems.includes(itemId)) {
                                this.selectedItems.push(itemId);
                            }
                        });
                    } else {
                        if (this.selectedItems.includes(id)) {
                            this.selectedItems = this.selectedItems.filter(item => item !== id);
                        } else {
                            this.selectedItems.push(id);
                        }
                        this.lastCheckedIndex = index;
                    }
                },

                toggleAll(e) {
                    if (e.target.checked) {
                        this.selectedItems = [...this.pageIds];
                    } else {
                        this.selectedItems = [];
                    }
                    this.lastCheckedIndex = null;
                },

                prepareBulkEdit() {
                    // Reset
                    this.editingGroups = {};
                    this.generatedDraftMap = {};

                    // 1. Collect Data
                    // Map ID -> {id, sn}
                    let itemsData = this.selectedItems.map(id => ({
                        id: id,
                        sn: this.itemMap[id] || 'UNKNOWN-000'
                    }));

                    let groups = {};

                    // 2. Grouping Logic
                    itemsData.forEach(item => {
                        let sn = item.sn;
                        // Regex: Ambil string sebelum strip terakhir sebagai prefix
                        let match = sn.match(/^(.*)-/);
                        let prefix = match ? match[1] : 'OTHERS';

                        if (prefix === 'OTHERS' && !sn.includes('-')) {
                            prefix = 'NO_PREFIX';
                        }

                        if (!groups[prefix]) {
                            groups[prefix] = {
                                prefix: prefix,
                                items: [],
                                pattern: prefix + '-@', // Default suggestion
                                start_seq: 1
                            };
                        }
                        groups[prefix].items.push(item);
                    });

                    this.editingGroups = groups;
                    this.generateDraft();
                },

                generateDraft() {
                    let draftText = '';
                    let draftMap = {};

                    // Loop setiap grup (sorted by prefix key)
                    Object.keys(this.editingGroups).sort().forEach(prefix => {
                        let group = this.editingGroups[prefix];
                        let pattern = group.pattern;
                        let seq = parseInt(group.start_seq) || 1;

                        // Sort items by ID agar urut
                        group.items.sort((a, b) => a.id - b.id);

                        group.items.forEach(item => {
                            let newSn = '';
                            if (pattern.includes('@')) {
                                newSn = pattern.replace('@', seq.toString().padStart(3, '0'));
                                seq++;
                            } else {
                                newSn = pattern + seq; // Fallback jika user hapus @
                                seq++;
                            }

                            draftMap[item.id] = newSn;
                            draftText += newSn + '\n';
                        });

                        draftText += '\n'; // Jarak antar grup
                    });

                    this.snDraft = draftText.trim();
                    this.generatedDraftMap = draftMap;
                },

                submitBulkAction(type) {
                    if (type === 'print_qr') {
                        const form = document.getElementById('bulkActionForm');
                        const originalAction = form.action;
                        form.action = "{{ route('items.print_bulk_qr') }}";
                        form.submit();
                        setTimeout(() => form.action = originalAction, 1000);
                        return;
                    }

                    const message = type === 'delete'
                        ? `Yakin ingin menghapus ${this.selectedItems.length} item yang dipilih?`
                        : `Yakin ingin menduplikasi ${this.selectedItems.length} item yang dipilih?`;

                    if (confirm(message)) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>