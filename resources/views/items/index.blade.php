<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Inventory') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="mx-4 my-4 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg text-sm flex items-center gap-3 transition-all duration-500 fixed top-16 right-4 z-50 max-w-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mx-4 my-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl shadow-lg text-sm fixed top-16 right-4 z-50">
            {{ session('error') }}
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    {{-- Ambil semua ID di page ini untuk referensi urutan javascript --}}
        <div class="py-6" x-data="itemPage({{ json_encode($items->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Daftar Item</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh aset inventaris Anda</p>
                </div>


                    {{-- Tombol Regenerate QR (BARU) --}}
    <form action="{{ route('items.regenerate_qr') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuat ulang SEMUA QR Code? Proses ini mungkin memakan waktu jika data banyak.')">
        @csrf
        <button type="submit" 
                class="px-5 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm transition-all duration-200 flex items-center gap-2 font-medium text-sm"
                title="Generate ulang semua file QR yang hilang">
            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Fix QR
        </button>
    </form>


                <div class="flex flex-wrap gap-3">
                    {{-- Tombol Riwayat Barang Keluar --}}
                    <a href="{{ route('items.out.index') }}"
                       class="px-5 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat Keluar
                    </a>

                    {{-- Tombol Tambah Item --}}
                    <a href="{{ route('items.create') }}"
                       class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Item
                    </a>
                </div>
            </div>

            {{-- SEARCH & FILTER SECTION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                    {{-- Search Input --}}
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"
                               placeholder="Cari Nama, No Asset, atau Serial Number...">
                    </div>

                    {{-- Filters --}}
                    <div class="flex flex-wrap md:flex-nowrap gap-3">
                        <select name="status" class="w-full md:w-40 rounded-xl border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            @foreach(['available', 'borrowed', 'maintenance', 'dikeluarkan'] as $st)
                                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>

                        <select name="room_id" class="w-full md:w-48 rounded-xl border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Ruangan</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition-colors font-semibold text-sm">
                            Filter
                        </button>

                        @if(request()->anyFilled(['search', 'status', 'room_id']))
                            <a href="{{ route('items.index') }}" class="w-full md:w-auto px-6 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-colors font-semibold text-sm text-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- FORM BULK ACTION WRAPPER --}}
            {{-- Form ini membungkus tabel agar input checkbox bisa disubmit --}}
            <form id="bulkActionForm" action="{{ route('items.bulk_action') }}" method="POST">
                @csrf
                {{-- Input tersembunyi untuk menentukan apakah user memilih 'delete' atau 'copy' --}}
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 overflow-hidden">
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full text-sm border-collapse">
                            
                            {{-- TABLE HEADER --}}
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border-b-2 border-gray-200">
                                <tr>
                                    {{-- CHECKBOX HEADER (SELECT ALL) --}}
                                    <th class="px-4 py-4 text-center w-10">
                                        <input type="checkbox" @click="toggleAll" x-ref="selectAllCheckbox"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4">
                                    </th>
                                    @foreach (['ID','Nama','No Asset','Serial','QR','Ruangan','Qty','Status','Kondisi','Kategori','Aksi'] as $head)
                                        <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase whitespace-nowrap">{{ $head }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            {{-- TABLE BODY --}}
<tbody class="divide-y divide-gray-100 bg-white">
    @forelse ($items as $item)
        <tr class="hover:bg-blue-50/50 transition-colors duration-200" 
            :class="{'bg-blue-50': selectedItems.includes({{ $item->id }})}">
            
            {{-- CHECKBOX ROW (UPDATED) --}}
            <td class="px-4 py-4 text-center">
                {{-- Perhatikan parameter toggleItem di bawah ini --}}
                <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" 
                       @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                       :checked="selectedItems.includes({{ $item->id }})"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer w-4 h-4">
            </td>

                                        {{-- ID --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-xs">{{ $item->id }}</span>
                                        </td>

                                        {{-- NAME --}}
                                        <td class="px-4 py-4 whitespace-normal break-words max-w-xs">
                                            <span class="font-semibold text-gray-900">{{ $item->name }}</span>
                                        </td>

                                        {{-- ASSET NO --}}
                                        <td class="px-4 py-4 whitespace-nowrap"><span class="text-gray-600 font-mono text-xs">{{ $item->asset_number ?? '-' }}</span></td>

                                        {{-- SERIAL --}}
                                        <td class="px-4 py-4 whitespace-nowrap"><span class="text-gray-800 font-mono text-xs font-bold">{{ $item->serial_number }}</span></td>

                                        {{-- QR --}}
                                        <td class="px-4 py-4">
                                            @if ($item->qr_code)
                                                <img src="{{ asset('storage/'.$item->qr_code) }}" class="w-10 h-10 rounded border hover:scale-150 transition-transform bg-white">
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </td>

                                        {{-- ROOM --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 text-gray-700">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $item->room->name }}
                                            </span>
                                        </td>

                                        {{-- QTY --}}
                                        <td class="px-4 py-4 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 rounded-full text-xs font-bold text-gray-700 border border-gray-200">{{ $item->quantity }}</span></td>

                                        {{-- STATUS --}}
                                        <td class="px-4 py-4 whitespace-nowrap"><x-status-badge :status="$item->status" /></td>

                                        {{-- CONDITION --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $condColors = ['good' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'damaged' => 'bg-orange-100 text-orange-700 border-orange-200', 'broken' => 'bg-red-100 text-red-700 border-red-200'];
                                                $condLabels = ['good' => 'Baik', 'damaged' => 'Rusak Ringan', 'broken' => 'Rusak Berat'];
                                                $currCond = $item->condition ?? 'good';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $condColors[$currCond] ?? 'bg-gray-100' }}">{{ $condLabels[$currCond] ?? ucfirst($currCond) }}</span>
                                        </td>

                                        {{-- CATEGORIES --}}
                                        <td class="px-4 py-4 whitespace-normal max-w-xs">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($item->categories as $cat)
                                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] bg-blue-50 text-blue-600 rounded border border-blue-100">{{ $cat->name }}</span>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- ACTIONS (SINGLE) --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                {{-- Detail --}}
                                                <a href="{{ route('items.show', $item->id) }}" class="p-1.5 bg-sky-100 text-sky-600 rounded-lg hover:bg-sky-200 transition" title="Detail"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                                
                                                {{-- Edit --}}
                                                <a href="{{ route('items.edit', $item->id) }}" class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition" title="Edit"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                                
                                                {{-- Keluarkan --}}
                                                @if($item->status !== 'dikeluarkan')
                                                <a href="{{ route('items.out.create', $item->id) }}" class="p-1.5 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition" title="Keluarkan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg></a>
                                                @endif
                                                
                                                {{-- Hapus (Single) --}}
                                                {{-- type="button" agar tidak mensubmit form bulk action di luarnya --}}
                                                <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="12" class="text-center py-12 text-gray-500">Data tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            {{-- PAGINATION --}}
            <div class="mt-6 px-2">{{ $items->links() }}</div>
        </div>

        {{-- MODAL DELETE SINGLE --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center backdrop-blur-sm z-50">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200 mx-4" @click.away="showModal = false">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center"><svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                    <h2 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h2>
                </div>
                <p class="text-gray-600 text-sm mb-6">Hapus item <span class="font-bold" x-text="deleteName"></span>?</p>
                <div class="flex justify-end gap-3">
                    <button @click="showModal = false" class="px-5 py-2.5 bg-gray-100 rounded-lg text-sm">Batal</button>
                    {{-- Form khusus delete single agar tidak bentrok dengan form bulk --}}
                    <form :action="deleteUrl" method="POST">
                        @csrf 
                        @method('DELETE')
                        <button class="px-5 py-2.5 bg-red-600 text-white rounded-lg text-sm font-bold shadow hover:bg-red-700">Ya, Hapus</button>
                    </form>
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
                {{-- Tombol Copy --}}
                <button @click="submitBulkAction('copy')" 
                        class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Duplicate
                </button>

                {{-- Tombol Delete Massal --}}
                <button @click="submitBulkAction('delete')" 
                        class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Hapus Terpilih
                </button>
            </div>
        </div>

    </div>

<script>
    function itemPage(pageIds = []) { // Menerima array ID dari PHP
        return {
            showModal: false,
            deleteUrl: '',
            deleteName: '',
            selectedItems: [],
            
            // Logic Shift Selection
            pageIds: pageIds,       // Daftar ID urut di halaman ini
            lastCheckedIndex: null, // Index terakhir yang diklik user

            // Konfirmasi Hapus Single
            confirmDelete(id, name) {
                this.showModal = true;
                this.deleteName = name;
                this.deleteUrl = `/items/${id}`;
            },

            // Toggle Checkbox per Item (Support Shift Key)
            toggleItem(id, index, event) {
                // Jika tombol SHIFT ditekan DAN kita punya history klik sebelumnya
                if (event.shiftKey && this.lastCheckedIndex !== null) {
                    // Tentukan range (awal dan akhir)
                    const start = Math.min(this.lastCheckedIndex, index);
                    const end = Math.max(this.lastCheckedIndex, index);

                    // Ambil ID dari range tersebut
                    const idsToSelect = this.pageIds.slice(start, end + 1);

                    // Masukkan ke array selectedItems (jika belum ada)
                    idsToSelect.forEach(itemId => {
                        if (!this.selectedItems.includes(itemId)) {
                            this.selectedItems.push(itemId);
                        }
                    });
                } else {
                    // --- KLIK BIASA (Tanpa Shift) ---
                    if (this.selectedItems.includes(id)) {
                        // Kalau sudah ada, hapus (uncheck)
                        this.selectedItems = this.selectedItems.filter(item => item !== id);
                    } else {
                        // Kalau belum ada, tambah (check)
                        this.selectedItems.push(id);
                    }
                    
                    // Simpan index ini sebagai "terakhir diklik"
                    this.lastCheckedIndex = index;
                }
            },

            // Toggle Select All
            toggleAll(e) {
                if (e.target.checked) {
                    // Ambil semua ID di halaman ini
                    this.selectedItems = [...this.pageIds];
                } else {
                    this.selectedItems = [];
                }
                this.lastCheckedIndex = null; // Reset shift history
            },

            // Submit Form Bulk Action
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