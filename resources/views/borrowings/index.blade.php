<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman Aktif') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="fixed top-4 right-4 z-50 p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="py-12" x-data="borrowingPage({{ json_encode($borrowings->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Peminjaman Berjalan</h3>
                    <p class="text-sm text-gray-500 mt-1">Pantau barang yang sedang dipinjam dan jatuh tempo.</p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('borrowings.follow_ups') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-red-300 rounded-lg font-semibold text-xs text-red-700 uppercase hover:bg-red-50 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Tindak Lanjut
                    </a>
                    <a href="{{ route('borrowings.history') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>
                    <a href="{{ route('admin.loans.pending') }}" class="inline-flex items-center px-4 py-2.5 bg-yellow-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Permintaan Baru
                    </a>
                    <a href="{{ route('borrowings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Pinjam Baru
                    </a>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('borrowings.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    
                    {{-- Search Input --}}
                    <div class="w-full md:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm" 
                               placeholder="Cari Peminjam atau Nama Barang...">
                    </div>

                    {{-- Filter Overdue --}}
                    <div class="w-full md:w-auto">
                        <label class="flex items-center justify-center space-x-2 cursor-pointer bg-red-50 px-4 py-2.5 rounded-lg border border-red-100 hover:bg-red-100 transition h-[42px]">
                            <input type="checkbox" name="status_filter" value="late" {{ request('status_filter') == 'late' ? 'checked' : '' }} 
                                   class="rounded text-red-600 focus:ring-red-500 border-gray-300 w-4 h-4 cursor-pointer">
                            <span class="text-sm font-bold text-red-700">Hanya Terlambat</span>
                        </label>
                    </div>

                    {{-- Sorting --}}
                    <div class="w-full md:w-48">
                        <select name="direction" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer h-[42px]">
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Urutkan: Terlama</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition h-[42px]">
                        Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'status_filter']))
                        <a href="{{ route('borrowings.index') }}" class="w-full md:w-auto px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-semibold transition text-center h-[42px] flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- TABLE WRAPPER --}}
            <form id="bulkActionForm" action="{{ route('borrowings.bulk_return') }}" method="POST">
                @csrf
                <input type="hidden" name="condition" id="bulkReturnCondition">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 w-10 text-center">
                                        <input type="checkbox" @click="toggleAll" x-ref="selectAllCheckbox"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penanggung Jawab</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Ruang</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tenggat</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($borrowings as $b)
                                    @php
                                        // Cek Keterlambatan
                                        $isLate = $b->return_date && \Carbon\Carbon::now()->gt($b->return_date);
                                        // Highlight row merah jika telat
                                        $rowClass = $isLate ? 'bg-red-50 hover:bg-red-100/80' : 'hover:bg-blue-50/50';
                                    @endphp
                                    <tr class="transition duration-150 {{ $rowClass }}" :class="{'bg-blue-50': selectedItems.includes({{ $b->id }})}">
                                        
                                        {{-- CHECKBOX --}}
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $b->id }}" 
                                                   @click="toggleItem({{ $b->id }}, {{ $loop->index }}, $event)"
                                                   :checked="selectedItems.includes({{ $b->id }})"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        </td>

                                        {{-- PEMINJAM --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                                    {{ substr($b->borrower->name ?? '?', 0, 1) }}
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $b->borrower->name ?? 'User Dihapus' }}</div>
                                            </div>
                                        </td>

                                        {{-- PENANGGUNG JAWAB --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600 font-medium">{{ $b->penanggung_jawab ?? '-' }}</div>
                                        </td>

                                        {{-- BARANG --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $b->item->name ?? 'Item Dihapus' }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $b->item->serial_number ?? '-' }}</div>
                                        </td>

                                        {{-- RUANG --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">{{ $b->ruang_pakai ?? '-' }}</div>
                                        </td>

                                        {{-- TGL PINJAM --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($b->borrow_date)->format('d M Y') }}
                                        </td>

                                        {{-- TENGGAT --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($b->return_date)
                                                <span class="{{ $isLate ? 'text-red-600 font-bold flex items-center gap-1' : 'text-gray-600' }}">
                                                    {{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}
                                                    @if($isLate)
                                                        <span class="bg-red-100 text-red-600 text-[10px] px-1.5 py-0.5 rounded uppercase tracking-wide">Telat</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Tidak ada tenggat</span>
                                            @endif
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                
                                                <a href="{{ route('borrowings.show', $b->id) }}" class="text-gray-400 hover:text-blue-600 p-1.5 hover:bg-white rounded-lg transition" title="Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>

                                                <button type="button" 
                                                        @click="openReturnModal({{ $b->id }}, '{{ $b->item->name ?? 'Item' }}', '{{ $b->borrower->name ?? 'User' }}')" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-emerald-100 transition shadow-sm">
                                                    Kembalikan
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center bg-gray-50">
                                            <div class="flex flex-col items-center justify-center text-gray-500">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                <p class="font-medium">Tidak ada peminjaman aktif.</p>
                                                <p class="text-xs mt-1">Gunakan tombol "Pinjam Baru" untuk membuat transaksi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="w-full">
                            @if($borrowings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $borrowings->links() }}
                            @else
                                <div class="text-sm text-gray-500">Menampilkan semua {{ $borrowings->count() }} data.</div>
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
                <span>Peminjaman Dipilih</span>
            </div>

            <div class="h-8 w-px bg-gray-300"></div>

            <div class="flex-1 flex justify-end">
                <button @click="showBulkModal = true" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl transition text-sm font-bold shadow-lg shadow-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 
                    Kembalikan Terpilih
                </button>
            </div>
        </div>

        {{-- MODAL 1: SINGLE RETURN --}}
        <div x-show="showReturnModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
            <div @click.away="showReturnModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-emerald-900">Konfirmasi Pengembalian</h3>
                    <button @click="showReturnModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <div class="p-6">
                    <div class="mb-5">
                        <p class="text-sm text-gray-500 mb-1">Barang</p>
                        <p class="text-gray-900 font-bold text-lg" x-text="returnItemName"></p>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-1">Peminjam</p>
                        <p class="text-gray-900 font-medium" x-text="returnBorrower"></p>
                    </div>

                    <form :action="'/borrowings/' + returnId + '/return'" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi Barang</label>
                            <select name="condition" x-model="selectedCondition" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                <option value="good">✨ Baik (Layak Pakai)</option>
                                <option value="damaged">⚠️ Rusak Ringan</option>
                                <option value="broken">❌ Rusak Berat</option>
                            </select>
                        </div>
                        
                        {{-- BUKTI FOTO (QR UPLOAD - POPUP STYLE) --}}
                        <div class="mb-6" @remote-image-selected.window="evidenceUrl = $event.detail.url">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Bukti Foto (Opsional)</label>
                            <input type="hidden" name="evidence_photo" x-model="evidenceUrl">
                            
                            {{-- 1. Trigger Button --}}
                            <div x-show="!evidenceUrl">
                                <button type="button" @click="$dispatch('open-remote-upload')"
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-xl bg-blue-50 hover:bg-blue-100 transition text-blue-700 gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <span class="font-bold">Scan QR Upload</span>
                                    <span class="text-xs opacity-70">Klik untuk membuka scanner</span>
                                </button>
                            </div>

                            {{-- 2. Success Preview State --}}
                            <div x-show="evidenceUrl" class="relative group rounded-xl overflow-hidden border border-emerald-200 bg-emerald-50 p-2 flex items-center gap-3">
                                <img :src="evidenceUrl" class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-emerald-800">Foto Terupload!</p>
                                    <p class="text-xs text-emerald-600">Klik 'Proses' untuk menyimpan.</p>
                                </div>
                                <button type="button" @click="evidenceUrl = null" class="absolute top-2 right-2 bg-white text-red-500 rounded-full p-1 shadow hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-6" x-show="selectedCondition !== 'good'" x-transition>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tindak Lanjut <span class="text-red-500">*</span></label>
                            <input type="text" name="follow_up" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm" placeholder="Contoh: Perlu dibawa ke servis luar, atau bisa diperbaiki sendiri">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showReturnModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow">Proses</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL 2: BULK RETURN --}}
        <div x-show="showBulkModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
            <div @click.away="showBulkModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 mb-4">
                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Pengembalian Masal</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Anda akan mengembalikan <span class="font-bold text-gray-900" x-text="selectedItems.length"></span> barang sekaligus.
                    </p>

                    <div class="mt-6 text-left">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kondisi (Untuk Semua)</label>
                        <select id="bulkConditionSelect" class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                            <option value="good">Semua Baik</option>
                            <option value="damaged">Semua Rusak Ringan</option>
                            <option value="broken">Semua Rusak Berat</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-2 italic">*Jika kondisi barang berbeda-beda, harap proses satu per satu.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" @click="showBulkModal = false" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">Batal</button>
                    <button type="button" @click="submitBulkReturn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 shadow">Konfirmasi</button>
                </div>
            </div>
        </div>

    </div>

    {{-- ALPINE SCRIPT --}}
    <script>
        function borrowingPage(pageIds = []) {
            return {
                selectedItems: [],
                pageIds: pageIds,
                lastCheckedIndex: null,
                
                // Single Return State
                showReturnModal: false,
                returnId: null,
                returnItemName: '',
                returnBorrower: '',
                selectedCondition: 'good',

                // Bulk Return State
                showBulkModal: false,

                // Logic Open Single Modal
                openReturnModal(id, itemName, borrower) {
                    this.returnId = id;
                    this.returnItemName = itemName;
                    this.returnBorrower = borrower;
                    this.selectedCondition = 'good';
                    // Reset QR
                    this.evidenceUrl = null;
                    this.qrCodeSvg = null;
                    if(this.pollInterval) clearInterval(this.pollInterval);
                    
                    this.showReturnModal = true;
                },

                // QR Logic (Moved to remoteUploadComponent)
                evidenceUrl: null,

                submitBulkReturn() {
                    const condition = document.getElementById('bulkConditionSelect').value;
                    document.getElementById('bulkReturnCondition').value = condition;
                    document.getElementById('bulkActionForm').submit();
                },


                // Checkbox Logic (Shift-Click Support)
                toggleItem(id, index, event) {
                    if (event.shiftKey && this.lastCheckedIndex !== null) {
                        const start = Math.min(this.lastCheckedIndex, index);
                        const end = Math.max(this.lastCheckedIndex, index);
                        this.pageIds.slice(start, end + 1).forEach(i => {
                            if (!this.selectedItems.includes(i)) this.selectedItems.push(i);
                        });
                    } else {
                        if (this.selectedItems.includes(id)) this.selectedItems = this.selectedItems.filter(i => i !== id);
                        else this.selectedItems.push(id);
                        this.lastCheckedIndex = index;
                    }
                },
                toggleAll(e) {
                    this.selectedItems = e.target.checked ? [...this.pageIds] : [];
                }
            }
        }
    </script>

    {{-- REMOTE UPLOAD COMPONENT (SAME AS ROOM BORROWING) --}}
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

                <div @click.away="closeModal()" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 text-gray-900 font-bold">Scan QR untuk Upload</h3>
                                <div class="mt-4 flex flex-col items-center justify-center space-y-4">
                                    {{-- Loading --}}
                                    <div x-show="loading" class="flex flex-col items-center text-gray-500">
                                        <svg class="animate-spin h-8 w-8 text-indigo-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Generating Token & QR...
                                    </div>
                                    {{-- QR --}}
                                    <div x-show="!loading && qrCodeSvg" class="p-4 bg-white border rounded">
                                        <div x-html="qrCodeSvg"></div>
                                    </div>
                                    {{-- Instructions --}}
                                    <div x-show="!loading" class="text-sm text-gray-500 text-center">
                                        <p class="mb-2">1. Buka kamera HP Anda / Aplikasi Scanner.</p>
                                        <p class="mb-2">2. Scan QR Code di atas.</p>
                                        <p class="mb-2">3. Upload foto melalui halaman di HP.</p>
                                        <p class="text-xs text-gray-400 mt-2">(Halaman akan otomatis refresh saat foto diterima)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        // Use remote.token to match items/create & room_borrowings logic
                        const response = await fetch("{{ route('remote.token') }}");
                        if (!response.ok) throw new Error('Network response was not ok');
                        const data = await response.json();

                        if (data.token) {
                            this.token = data.token;
                            if (data.qr_code) this.qrCodeSvg = data.qr_code;
                            this.loading = false;
                            this.startPolling();
                        } else { throw new Error("Token not found"); }
                    } catch (error) {
                        console.error(error);
                        alert('Gagal membuat sesi upload.');
                        this.closeModal();
                    }
                },

                startPolling() {
                    if (!this.token) return;
                    if (this.pollInterval) clearInterval(this.pollInterval);

                    this.pollInterval = setInterval(async () => {
                        if (this.pollAttempts >= this.maxAttempts) {
                            alert('Sesi upload habis. Silakan scan ulang.');
                            this.closeModal();
                            return;
                        }
                        this.pollAttempts++;

                        try {
                            const res = await fetch(`{{ url('/api/remote-check') }}/${this.token}`);
                            if (!res.ok) return;
                            const statusData = await res.json();

                            if (statusData.status === 'found' && statusData.url) {
                                this.closeModal();
                                window.dispatchEvent(new CustomEvent('remote-image-selected', {
                                    detail: { url: statusData.url }
                                }));
                            }
                        } catch (e) { console.error(e); }
                    }, 2000);
                },

                closeModal() {
                    this.isOpen = false;
                    this.token = null;
                    if (this.pollInterval) clearInterval(this.pollInterval);
                }
            }));
        });
    </script>
</x-app-layout>