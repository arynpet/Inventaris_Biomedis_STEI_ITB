<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Material Management') }}
        </h2>
    </x-slot>

    {{-- ALERTS --}}
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

    <div class="py-12" x-data="materialPage({{ json_encode($materials->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Bahan Baku (Materials)</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola stok filament, resin, dan material lainnya.</p>
                </div>
                <a href="{{ route('materials.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Material
                </a>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('materials.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400" placeholder="Cari Nama Material...">
                    </div>
                    <div class="w-full md:w-auto flex gap-4">
                        <select name="category" class="w-full md:w-40 rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            <option value="filament" {{ request('category') == 'filament' ? 'selected' : '' }}>Filament</option>
                            <option value="resin" {{ request('category') == 'resin' ? 'selected' : '' }}>Resin</option>
                        </select>
                        <button type="submit" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition">Filter</button>
                    </div>
                </form>
            </div>

            {{-- TABLE --}}
            <form id="bulkActionForm" action="{{ route('materials.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 w-10 text-center"><input type="checkbox" @click="toggleAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer"></th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Material</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stok Saat Ini</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($materials as $item)
                                    <tr class="hover:bg-blue-50/50 transition" :class="{'bg-blue-50': selectedItems.includes({{ $item->id }})}">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" 
                                                   @click="toggleItem({{ $item->id }}, {{ $loop->index }}, $event)"
                                                   :checked="selectedItems.includes({{ $item->id }})"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize
                                                {{ $item->category == 'filament' ? 'bg-orange-100 text-orange-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $item->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->name }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-bold text-gray-800">{{ number_format($item->stock_balance, 2) }}</span>
                                                <span class="text-xs text-gray-500 uppercase font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $item->unit }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- TOMBOL ADD STOCK (NEW) --}}
                                                <button type="button" @click="openStockModal({{ $item->id }}, '{{ $item->name }}', '{{ $item->unit }}')" 
                                                        class="flex items-center gap-1 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition border border-emerald-200" title="Tambah Stok">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    <span class="text-xs font-bold">Stok</span>
                                                </button>

                                                <a href="{{ route('materials.edit', $item->id) }}" class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                                
                                                <button type="button" @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500">Data tidak ditemukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">{{ $materials->links() }}</div>
                </div>
            </form>
        </div>

        {{-- FLOATING BULK ACTION --}}
        <div x-show="selectedItems.length > 0" x-transition.duration.300ms class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-700 font-medium"><span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold" x-text="selectedItems.length"></span><span>Dipilih</span></div>
            <button @click="submitBulkAction('delete')" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus Terpilih
            </button>
        </div>

        {{-- MODAL 1: ADD STOCK --}}
        <div x-show="showStockModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showStockModal" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showStockModal" @click.away="showStockModal = false" x-transition.scale class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md">
                    <div class="bg-emerald-50 px-4 py-4 sm:px-6 flex items-center gap-3 border-b border-emerald-100">
                        <div class="bg-emerald-100 p-2 rounded-full"><svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                        <h3 class="text-lg font-bold text-emerald-900">Tambah Stok</h3>
                    </div>
                    <form :action="'/materials/' + stockId + '/add-stock'" method="POST" class="p-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Material</label>
                            <input type="text" :value="stockName" disabled class="w-full bg-gray-100 border-gray-300 rounded-lg text-gray-500">
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tambahan (<span x-text="stockUnit" class="uppercase"></span>)</label>
                            <input type="number" name="amount" step="0.01" min="0.01" required class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" placeholder="Contoh: 500">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showStockModal = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold">Simpan Stok</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL 2: DELETE CONFIRMATION --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
            <div @click.away="showDeleteModal = false" class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Material?</h3>
                <p class="text-gray-500 mb-6">Anda yakin ingin menghapus <span class="font-bold text-gray-800" x-text="deleteName"></span>? Data tidak bisa dikembalikan.</p>
                <div class="flex justify-end gap-3">
                    <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                    <form :action="'/materials/' + deleteId" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function materialPage(pageIds = []) {
            return {
                // Bulk Action State
                selectedItems: [],
                pageIds: pageIds,
                lastCheckedIndex: null,
                
                // Add Stock Modal State
                showStockModal: false,
                stockId: null,
                stockName: '',
                stockUnit: '',

                // Delete Modal State
                showDeleteModal: false,
                deleteId: null,
                deleteName: '',

                // Logic Add Stock
                openStockModal(id, name, unit) {
                    this.stockId = id;
                    this.stockName = name;
                    this.stockUnit = unit;
                    this.showStockModal = true;
                },

                // Logic Delete
                confirmDelete(id, name) {
                    this.deleteId = id;
                    this.deleteName = name;
                    this.showDeleteModal = true;
                },

                // Logic Bulk Checkbox
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
                },
                submitBulkAction(type) {
                    if(confirm('Yakin ingin memproses ' + this.selectedItems.length + ' item?')) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>