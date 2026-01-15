<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Inventory') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT (Animated) --}}
    @if (session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div class="p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        </div>

        
    @endif

    {{-- ERROR ALERT --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="fixed top-20 right-4 z-50 max-w-sm w-full">
            <div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl shadow-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show fixed-bottom m-3" role="alert" style="z-index: 1050; max-width: 500px;">
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
    {{-- Kita passing ID item ke Alpine untuk fitur Shift-Click --}}
    <div class="py-12" x-data="itemPage({{ json_encode($items->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

{{-- HEADER SECTION --}}
            <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Data Induk Barang</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola inventaris dan aset laboratorium secara terpusat.</p>
                </div>

                <div class="flex flex-wrap gap-3 items-center">
                    
                    {{-- 1. Tombol Fix QR --}}
                    <form action="{{ route('items.regenerate_qr') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat ulang SEMUA QR Code? Proses ini mungkin memakan waktu jika data banyak.')">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2.5 bg-white border border-purple-200 rounded-lg font-semibold text-xs text-purple-700 uppercase tracking-widest shadow-sm hover:bg-purple-50 focus:outline-none transition ease-in-out duration-150"
                                title="Generate ulang semua file QR yang hilang">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Fix QR
                        </button>
                    </form>

                    {{-- 2. Tombol TRASH / SAMPAH (BARU) --}}
                    <a href="{{ route('items.trash') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-red-200 rounded-lg font-semibold text-xs text-red-600 uppercase tracking-widest shadow-sm hover:bg-red-50 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Sampah
                    </a>

                    {{-- 3. Tombol Riwayat --}}
                    <a href="{{ route('items.out.index') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Keluar
                    </a>

                    {{-- 4. Tombol Grouping --}}
                    <a href="{{ route('items.index', array_merge(request()->all(), ['group_by_asset' => '1'])) }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 shadow-sm transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                        Group By Asset
                    </a>

                    {{-- 5. Tombol Tambah --}}
                    <a href="{{ route('items.create') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Barang
                    </a>
                </div>
            </div>

            {{-- FILTER & SEARCH CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end lg:items-center">
                    {{-- Search Input --}}
                    <div class="w-full lg:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm transition"
                               placeholder="Cari Nama, No Asset, atau S/N...">
                    </div>

                    {{-- Dropdown Filters --}}
                    <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-4">
                        <select name="status" class="w-full sm:w-40 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Status</option>
                            @foreach(['available', 'borrowed', 'maintenance', 'dikeluarkan'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                    {{ ucfirst($st) }}
                                </option>
                            @endforeach
                        </select>

                        <select name="room_id" class="w-full sm:w-48 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm cursor-pointer">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm">
                            Filter
                        </button>

                        @if(request()->anyFilled(['search', 'status', 'room_id']))
                            <a href="{{ route('items.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold text-center transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- FORM BULK ACTION WRAPPER (PENTING: Membungkus Tabel) --}}
            <form id="bulkActionForm" action="{{ route('items.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    {{-- CHECKBOX HEADER (Select All) --}}
                                    <th scope="col" class="px-3 py-3 text-center w-8">
                                        <input type="checkbox" @click="toggleAll" x-ref="selectAllCheckbox"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4">
                                    </th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Foto</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider min-w-[200px]">Barang</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Merk</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">No Asset</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap hidden xl:table-cell">S/N</th>
                                    <th scope="col" class="px-3 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">QR</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Ruangan</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">Kondisi</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden xl:table-cell">Kategori</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-150" 
                                        :class="{'bg-blue-50': selectedItems.includes({{ $item->id }})}">
                                        
                                        {{-- CHECKBOX ROW --}}
                                        <td class="px-3 py-3 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" 
                                                   @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                                                   :checked="selectedItems.includes({{ $item->id }})"
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4">
                                        </td>

                                        {{-- ID --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-500">
                                            #{{ $item->id }}
                                        </td>

                                        {{-- Image Thumbnail --}}
                                        <td class="px-3 py-3 text-center">
                                            <div class="h-10 w-10 mx-auto rounded-lg overflow-hidden border border-gray-200 bg-gray-50 relative group shadow-sm">
                                                 <img src="{{ $item->optimized_image }}" 
                                                      alt="{{ $item->name }}" 
                                                      class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110"
                                                      loading="lazy"
                                                      onerror="this.src='https://placehold.co/100x100?text=Err'">
                                            </div>

                                        </td>

                                        {{-- Name --}}
                                        <td class="px-3 py-3">
                                            <div class="text-sm font-semibold text-gray-900 leading-tight">
                                                {{ $item->name }}
                                            </div>
                                            {{-- Mobile Only Metadata --}}
                                            <div class="lg:hidden mt-1 text-xs text-gray-500">
                                                {{ $item->asset_number }}
                                            </div>
                                        </td>

                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 hidden md:table-cell">
                                            {{ $item->brand ?? '-' }}
                                        </td>

                                        {{-- Asset Number --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 font-mono hidden lg:table-cell">
                                            {{ $item->asset_number ?? '-' }}
                                        </td>

                                        {{-- Serial Number --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs font-mono text-gray-800 hidden xl:table-cell">
                                            {{ $item->serial_number ?? '-' }}
                                        </td>

                                        {{-- QR Code --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-center">
                                            @if ($item->qr_code)
                                                <div class="flex justify-center items-center cursor-zoom-in relative w-8 h-8"
                                                     @mouseenter="activeQr = '{{ asset('storage/'.$item->qr_code) }}'"
                                                     @mouseleave="activeQr = null">
                                                    <img src="{{ asset('storage/'.$item->qr_code) }}" 
                                                         alt="QR" 
                                                         class="h-8 w-8 rounded border border-gray-200 bg-white p-0.5 object-cover shadow-sm transition-transform">
                                                </div>
                                            @else
                                                <span class="text-[10px] text-gray-400 italic">No QR</span>
                                            @endif
                                        </td>

                                        {{-- Room --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 hidden sm:table-cell">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                <span class="truncate max-w-[120px]" title="{{ $item->room->name ?? 'Unassigned' }}">
                                                    {{ $item->room->name ?? '-' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Quantity --}}
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <x-status-badge :status="$item->status" class="scale-90 origin-left" />
                                        </td>

                                        {{-- Condition --}}
                                        <td class="px-3 py-3 whitespace-nowrap hidden md:table-cell">
                                            @php
                                                $condClass = match($item->condition) {
                                                    'good'    => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'damaged' => 'bg-orange-50 text-orange-700 border-orange-100',
                                                    'broken'  => 'bg-red-50 text-red-700 border-red-100',
                                                    default   => 'bg-gray-50 text-gray-600 border-gray-100',
                                                };
                                                $condLabel = match($item->condition) {
                                                    'good'    => 'Baik',
                                                    'damaged' => 'Rusak Ringan',
                                                    'broken'  => 'Rusak Berat',
                                                    default   => $item->condition,
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium border {{ $condClass }}">
                                                {{ $condLabel }}
                                            </span>
                                        </td>

                                        {{-- Categories --}}
                                        <td class="px-3 py-3 hidden xl:table-cell">
                                            <div class="flex flex-wrap gap-1 max-w-[120px]">
                                                @forelse ($item->categories->take(2) as $cat)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-blue-50 text-blue-700 border border-blue-100 truncate max-w-[80px]">
                                                        {{ $cat->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-[10px] text-gray-300">-</span>
                                                @endforelse
                                                @if($item->categories->count() > 2)
                                                    <span class="text-[9px] text-gray-400">+{{ $item->categories->count() - 2 }}</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-3 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('items.show', $item->id) }}" 
                                                   class="text-sky-600 hover:text-sky-900 bg-sky-50 hover:bg-sky-100 p-1.5 rounded transition-colors" 
                                                   title="Detail">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>
                                                
                                                <a href="{{ route('items.edit', $item->id) }}" 
                                                   class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-1.5 rounded transition-colors" 
                                                   title="Edit">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                
                                                @if($item->status !== 'dikeluarkan')
                                                    <a href="{{ route('items.out.create', $item->id) }}" 
                                                       class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded transition-colors" 
                                                       title="Keluarkan Barang">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                    </a>
                                                @endif
                                                
                                                <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')" 
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors" 
                                                        title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="12" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-base font-medium text-gray-900">Tidak ada item ditemukan</p>
                                        </div>
                                    </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination & Layout Control --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="w-full">
                            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $items->links() }}
                            @else
                                <div class="text-sm text-gray-500">Menampilkan semua {{ $items->count() }} data.</div>
                            @endif
                        </div>
                        
                        <div class="whitespace-nowrap">
                            @if(request('show_all'))
                                <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Batasi Per Halaman
                                </a>
                            @else
                                <a href="{{ request()->fullUrlWithQuery(['show_all' => 1]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    Tampilkan Semua
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- MODAL DELETE SINGLE --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     @click.away="showModal = false"
                     class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Item</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus item <span class="font-bold text-gray-800" x-text="deleteName"></span>? 
                                        Data yang dihapus tidak dapat dikembalikan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Ya, Hapus
                            </button>
                        </form>
                        <button type="button" @click="showModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- FLOATING BULK ACTION BAR --}}
        <div x-show="selectedItems.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6 w-[90%] max-w-2xl">
            
            <div class="flex items-center gap-2 text-gray-700 font-medium">
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold" x-text="selectedItems.length"></span>
                <span>Item Dipilih</span>
            </div>

            <div class="h-8 w-px bg-gray-300"></div>

            <div class="flex items-center gap-3 flex-1 justify-end">
                <button @click="submitBulkAction('copy')" class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Duplicate
                </button>

                <button @click="submitBulkAction('delete')" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Terpilih
                </button>
            </div>
        </div>

    {{-- FIXED QR ZOOM OVERLAY --}}
        <div x-show="activeQr" 
             style="display: none; pointer-events: none;"
             class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[100]"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            <div class="bg-white p-3 rounded-2xl shadow-2xl border-4 border-white ring-1 ring-gray-200">
                <img :src="activeQr" class="w-64 h-64 object-contain rounded-xl bg-gray-50">
                <p class="text-center text-xs font-bold text-gray-400 mt-2 tracking-widest uppercase">Scan Me</p>
            </div>
        </div>

    </div>

    {{-- ALPINE SCRIPT --}}
    <script>
        function itemPage(pageIds = []) {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',
                selectedItems: [],
                pageIds: pageIds,
                lastCheckedIndex: null,
                activeQr: null, // New state for QR Zoom

                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/items/${id}`;
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

                submitBulkAction(type) {
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