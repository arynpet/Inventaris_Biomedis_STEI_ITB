<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Paket Praktikum') }}
        </h2>
    </x-slot>

    {{--
    DATA PREPARATION FOR ALPINE
    We convert the PHP collection to a JSON array for client-side searching.
    --}}
    @php
        $itemsJson = $availableItems->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'sn' => $item->serial_number ?? '-',
            'asset' => $item->asset_number ?? '-',
            'search_str' => strtolower($item->name . ' ' . ($item->serial_number ?? '') . ' ' . ($item->asset_number ?? ''))
        ])->values()->toJson();
    @endphp

    <div class="py-12" x-data="itemPackageForm({
        items: {{ $itemsJson }},
        initialRows: 2
    })">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <form action="{{ route('item-packages.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- LEFT COLUMN: Basic Info --}}
                    <div class="lg:col-span-1 space-y-6">
                        <section
                            class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-blue-50 rounded-full blur-xl opacity-60">
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                Informasi Paket
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Paket
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 transition"
                                        placeholder="Contoh: Modul Fisika Dasar A" required>
                                </div>

                                <div>
                                    <label for="description"
                                        class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition resize-none"
                                        placeholder="Jelaskan isi atau peruntukan paket ini..."></textarea>
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-100">
                                <button type="submit"
                                    class="w-full flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-xl text-sm font-bold text-white shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Paket
                                </button>
                                <a href="{{ route('item-packages.index') }}"
                                    class="block text-center mt-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                                    Batal & Kembali
                                </a>
                            </div>
                        </section>
                    </div>

                    {{-- RIGHT COLUMN: Dynamic Item Selection --}}
                    <div class="lg:col-span-2">
                        <section
                            class="bg-white shadow-lg shadow-blue-50/50 sm:rounded-xl border border-gray-100 p-6 h-full flex flex-col">

                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    <span
                                        class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                            </path>
                                        </svg>
                                    </span>
                                    Isi Paket
                                </h3>
                                <span
                                    class="text-xs font-semibold bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100">
                                    <span x-text="rows.length"></span> Item Ditambahkan
                                </span>
                            </div>

                            <div class="flex-1 space-y-4 mb-6">
                                <template x-for="(row, index) in rows" :key="row.id">
                                    <div class="group relative flex items-start gap-3 p-4 rounded-xl border-2 transition-all duration-200"
                                        :class="row.selectedItem ? 'border-green-100 bg-green-50/30' : 'border-dashed border-gray-200 hover:border-blue-300 hover:bg-blue-50/20'">

                                        {{-- Number Badge --}}
                                        <div class="flex-shrink-0 mt-2">
                                            <span
                                                class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold"
                                                :class="row.selectedItem ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                                x-text="index + 1"></span>
                                        </div>

                                        {{-- Custom Searchable Select (Autocomplete) --}}
                                        <div class="flex-1 w-full" x-data="searchableSelect(row, allItems)">
                                            <div class="relative">
                                                <!-- Hidden Input for Form Submission -->
                                                <input type="hidden" name="item_ids[]" x-model="row.selectedId"
                                                    :required="true">

                                                <!-- Search Input -->
                                                <input type="text" x-model="search" @focus="open = true"
                                                    @click.away="open = false" @keydown.escape="open = false"
                                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 pl-3 pr-10 transition placeholder-gray-400 font-medium"
                                                    :class="{'border-green-500 focus:border-green-500 focus:ring-green-500': row.selectedItem}"
                                                    placeholder="Ketik nama item, serial number, atau no asset..."
                                                    autocomplete="off">

                                                <!-- Icon Indicator -->
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <template x-if="!row.selectedItem">
                                                        <svg class="h-5 w-5 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="row.selectedItem">
                                                        <svg class="h-5 w-5 text-green-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </template>
                                                </div>

                                                <!-- Dropdown List -->
                                                <div x-show="open && filteredItems.length > 0"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute z-10 mt-1 w-full bg-white shadow-xl max-h-60 rounded-lg py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm custom-scrollbar">

                                                    <ul tabindex="-1" role="listbox">
                                                        <template x-for="item in filteredItems" :key="item.id">
                                                            <li @click="selectItem(item); open = false"
                                                                class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition text-gray-900 group-li">
                                                                <div class="flex flex-col">
                                                                    <span class="font-semibold block truncate"
                                                                        x-text="item.name"></span>
                                                                    <span class="text-xs text-gray-500 flex gap-2">
                                                                        <span x-text="'SN: ' + item.sn"></span>
                                                                        <span class="text-gray-300">|</span>
                                                                        <span x-text="'Asset: ' + item.asset"></span>
                                                                    </span>
                                                                </div>
                                                            </li>
                                                        </template>
                                                    </ul>
                                                </div>

                                                <!-- Empty State for Search -->
                                                <div x-show="open && filteredItems.length === 0"
                                                    class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-lg py-4 text-center ring-1 ring-black ring-opacity-5">
                                                    <p class="text-sm text-gray-500">Tidak ada item ditemukan.</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Delete Row Button --}}
                                        <button type="button" @click="removeRow(index)"
                                            class="mt-1.5 p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Hapus baris ini">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- Add Row Button --}}
                                <button type="button" @click="addRow()"
                                    class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 font-semibold hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Barang ke Paket
                                </button>
                            </div>

                        </section>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.data('itemPackageForm', ({ items, initialRows = 2 }) => ({
                allItems: items,
                rows: [],

                init() {
                    // Populate initial rows
                    for (let i = 0; i < initialRows; i++) {
                        this.addRow();
                    }
                },

                addRow() {
                    this.rows.push({
                        id: Date.now() + Math.random(), // Check valid ID for loop key
                        selectedId: '',
                        selectedItem: null // Object
                    });
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    } else {
                        // Optional: Clear the last row instead of deleting if you want to enforce at least 1
                        alert("Minimal harus ada 1 item dalam paket.");
                    }
                }
            }));

            Alpine.data('searchableSelect', (row, allItems) => ({
                search: '',
                open: false,

                get filteredItems() {
                    if (this.search === '') {
                        return allItems.slice(0, 10); // Show top 10 if empty
                    }
                    const term = this.search.toLowerCase();
                    return allItems.filter(item =>
                        item.search_str.includes(term)
                    ).slice(0, 20); // Limit results for performance
                },

                selectItem(item) {
                    this.search = item.name;
                    row.selectedId = item.id;
                    row.selectedItem = item;
                },

                // Watch for external clearing? (Not strictly needed right now)
                init() {
                    this.$watch('row.selectedId', (value) => {
                        if (!value) {
                            this.search = '';
                            row.selectedItem = null;
                        }
                    })
                }
            }));
        });
    </script>
</x-app-layout>