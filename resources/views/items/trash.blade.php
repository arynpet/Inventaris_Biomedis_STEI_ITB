<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tong Sampah Item') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- JUDUL HALAMAN --}}
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-red-600">Barang Dihapus (Trash)</h3>
                        <p class="text-sm text-gray-500">Kelola data yang telah dihapus sementara.</p>
                    </div>
                    <a href="{{ route('items.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-bold text-xs uppercase hover:bg-gray-300 transition">
                        &larr; Kembali ke Index
                    </a>
                </div>

                {{-- AREA FILTER & SEARCH --}}
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <form action="{{ route('items.trash') }}" method="GET">
                        {{-- Hidden Inputs untuk menjaga sorting saat filter berubah --}}
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <input type="hidden" name="direction" value="{{ request('direction') }}">
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            
                            {{-- 1. Search Box --}}
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pencarian</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Nama / No Seri / Aset..." 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 text-sm">
                            </div>

                            {{-- 2. Filter Kategori --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Filter Kategori</label>
                                <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 text-sm">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 3. Filter Ruangan --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Filter Ruangan</label>
                                <select name="room_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 text-sm">
                                    <option value="">-- Semua Ruangan --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 4. Tombol Action --}}
                            <div class="flex gap-2">
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md font-bold text-sm hover:bg-red-700 transition w-full">
                                    FILTER
                                </button>
                                @if(request()->has('search') || request()->has('category_id') || request()->has('room_id'))
                                    <a href="{{ route('items.trash') }}" class="bg-gray-500 text-white px-3 py-2 rounded-md font-bold text-sm hover:bg-gray-600 transition" title="Reset Filter">
                                        &#x2715;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                {{-- AREA TOMBOL BULK ACTION (Muncul via JS) --}}
                <div id="bulk-action-container" style="display: none;" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center animate-pulse">
                    <span class="text-sm text-green-800 font-bold">
                        <span id="count-selected">0</span> item dipilih
                    </span>
                    <button id="btn-bulk-restore" class="px-4 py-2 bg-green-600 text-white rounded-md font-bold text-xs uppercase hover:bg-green-700 transition shadow-sm">
                        Pulihkan Terpilih
                    </button>
                </div>

                {{-- ALERT MESSAGES --}}
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 text-sm font-medium">{{ session('success') }}</div>
                @endif

                {{-- TABEL DATA --}}
                <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                </th>
                                
                                {{-- HELPER SORTING --}}
                                @php
                                    $sortLink = function($col, $label) {
                                        $currSort = request('sort');
                                        $currDir = request('direction');
                                        
                                        // Tentukan arah sort berikutnya
                                        $nextDir = ($currSort == $col && $currDir == 'asc') ? 'desc' : 'asc';
                                        
                                        // Icon logic
                                        $icon = '<svg class="w-3 h-3 text-gray-400 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>'; // Icon default (atas bawah)
                                        
                                        if ($currSort == $col) {
                                            if ($currDir == 'asc') {
                                                $icon = '<svg class="w-3 h-3 text-red-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>'; // Panah Atas
                                            } else {
                                                $icon = '<svg class="w-3 h-3 text-red-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>'; // Panah Bawah
                                            }
                                        }

                                        $url = request()->fullUrlWithQuery(['sort' => $col, 'direction' => $nextDir]);
                                        return '<a href="'.$url.'" class="group inline-flex items-center text-xs font-bold text-gray-600 uppercase tracking-wider hover:text-red-600 transition">'.$label.$icon.'</a>';
                                    };
                                @endphp

                                <th class="px-4 py-3 text-left">{!! $sortLink('name', 'Nama Barang') !!}</th>
                                <th class="px-4 py-3 text-left">{!! $sortLink('serial_number', 'No. Seri') !!}</th>
                                <th class="px-4 py-3 text-left">{!! $sortLink('asset_number', 'No. Aset') !!}</th>
                                <th class="px-4 py-3 text-left hidden md:table-cell">Kategori / Ruang</th>
                                <th class="px-4 py-3 text-left">{!! $sortLink('deleted_at', 'Dihapus') !!}</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($deletedItems as $item)
                            <tr class="hover:bg-red-50 transition duration-150">
                                <td class="px-4 py-3">
                                    <input type="checkbox" class="item-checkbox rounded border-gray-300 text-red-600 focus:ring-red-500" value="{{ $item->id }}">
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->serial_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->asset_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">
                                    <div class="flex flex-col">
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full w-fit mb-1">{{ $item->category->name ?? 'No Cat' }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->room->name ?? 'No Room' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-red-500">
                                    {{ $item->deleted_at->format('d M y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium whitespace-nowrap">
                                    {{-- RESTORE (GET METHOD) --}}
                                    <a href="{{ route('items.restore', $item->id) }}" 
                                       class="text-green-600 hover:text-green-800 font-bold mr-3 hover:underline">
                                        Pulihkan
                                    </a>

                                    {{-- TERMINATE (DELETE METHOD) --}}
                                    <form action="{{ route('items.terminate', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('PERINGATAN KERAS:\nData ini akan dihapus PERMANEN dari database dan TIDAK BISA kembali.\n\nLanjutkan?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold hover:underline">
                                            HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <p class="text-lg font-medium">Tong sampah kosong.</p>
                                        @if(request('search') || request('category_id'))
                                            <p class="text-sm">Atau tidak ditemukan data sesuai filter.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $deletedItems->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT BULK ACTION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const bulkContainer = document.getElementById('bulk-action-container');
            const btnBulkRestore = document.getElementById('btn-bulk-restore');
            const countSpan = document.getElementById('count-selected');
            const bulkRestoreUrl = "{{ route('items.bulk_restore') }}";

            function updateUI() {
                let selectedIds = [];
                checkboxes.forEach(cb => {
                    if (cb.checked) selectedIds.push(cb.value);
                });

                if (selectedIds.length > 0) {
                    bulkContainer.style.display = 'flex';
                    countSpan.textContent = selectedIds.length;
                } else {
                    bulkContainer.style.display = 'none';
                }
                return selectedIds;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateUI();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateUI));

            btnBulkRestore.addEventListener('click', function() {
                let ids = updateUI(); 
                if (ids.length === 0) return;
                if (confirm('Pulihkan ' + ids.length + ' item terpilih?')) {
                    window.location.href = bulkRestoreUrl + '?ids=' + ids.join(',');
                }
            });
        });
    </script>
</x-app-layout>