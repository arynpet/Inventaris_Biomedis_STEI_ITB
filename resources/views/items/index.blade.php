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
             class="mx-4 my-4 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg text-sm flex items-center gap-3 transition-all duration-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="py-6" x-data="itemPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Daftar Item</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola dan pantau seluruh aset inventaris Anda</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    {{-- Tombol Riwayat Barang Keluar (BARU) --}}
                    <a href="{{ route('items.out.index') }}"
                       class="px-5 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 shadow-sm transition-all duration-200 flex items-center gap-2 font-medium text-sm">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Keluar
                    </a>

                    {{-- Tombol Tambah Item --}}
                    <a href="{{ route('items.create') }}"
                       class="group relative px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Item
                    </a>
                </div>
            </div>

            {{-- SEARCH & FILTER SECTION (BARU) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                <form action="{{ route('items.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                    {{-- Search Input --}}
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
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

            {{-- WHITE WRAPPER --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">

                {{-- TABLE WRAPPER --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-collapse">

                            {{-- TABLE HEADER --}}
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border-b-2 border-gray-200">
                                <tr>
                                    @foreach (['ID','Nama','No Asset','Serial','QR','Ruangan','Qty','Status','Kondisi','Kategori','Aksi'] as $head)
                                        <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase whitespace-nowrap">
                                            {{ $head }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>

                            {{-- TABLE BODY --}}
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($items as $item)
                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">

                                        {{-- ID --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 font-bold text-xs">
                                                {{ $item->id }}
                                            </span>
                                        </td>

                                        {{-- NAME --}}
                                        <td class="px-4 py-4 whitespace-normal break-words max-w-xs">
                                            <span class="font-semibold text-gray-900">{{ $item->name }}</span>
                                        </td>

                                        {{-- ASSET NO --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="text-gray-600 font-mono text-xs">{{ $item->asset_number ?? '-' }}</span>
                                        </td>

                                        {{-- SERIAL --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="text-gray-800 font-mono text-xs font-bold">
                                                {{ $item->serial_number ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- QR --}}
                                        <td class="px-4 py-4">
                                            @if ($item->qr_code)
                                                <img src="{{ asset('storage/'.$item->qr_code) }}"
                                                     alt="QR {{ $item->serial_number }}"
                                                     class="w-10 h-10 rounded border hover:scale-150 transition-transform bg-white">
                                            @else
                                                <span class="text-xs text-gray-400 italic">Belum ada</span>
                                            @endif
                                        </td>

                                        {{-- ROOM --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 text-gray-700">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $item->room->name }}
                                            </span>
                                        </td>

                                        {{-- QTY --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 bg-gray-100 rounded-full text-xs font-bold text-gray-700 border border-gray-200">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <x-status-badge :status="$item->status" />
                                        </td>

                                        {{-- CONDITION --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $condColors = [
                                                    'good'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                    'damaged' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                    'broken'  => 'bg-red-100 text-red-700 border-red-200',
                                                ];
                                                $condLabels = [
                                                    'good'    => 'Baik',
                                                    'damaged' => 'Rusak Ringan',
                                                    'broken'  => 'Rusak Berat',
                                                ];
                                                $currCond = $item->condition ?? 'good';
                                            @endphp
                                            
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $condColors[$currCond] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $condLabels[$currCond] ?? ucfirst($currCond) }}
                                            </span>
                                        </td>

                                        {{-- CATEGORIES --}}
                                        <td class="px-4 py-4 whitespace-normal max-w-xs">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach ($item->categories as $cat)
                                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] bg-blue-50 text-blue-600 rounded border border-blue-100">
                                                        {{ $cat->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- ACTIONS (LENGKAP) --}}
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                {{-- DETAIL --}}
                                                <a href="{{ route('items.show', $item->id) }}"
                                                   class="p-1.5 bg-sky-100 text-sky-600 rounded-lg hover:bg-sky-200 transition" title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </a>

                                                {{-- EDIT --}}
                                                <a href="{{ route('items.edit', $item->id) }}"
                                                   class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>

                                                {{-- KELUARKAN (BARU - Conditional) --}}
                                                @if($item->status !== 'dikeluarkan')
                                                <a href="{{ route('items.out.create', $item->id) }}"
                                                   class="p-1.5 bg-orange-100 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition" title="Keluarkan Barang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                </a>
                                                @endif

                                                {{-- DELETE --}}
                                                <button @click="confirmDelete({{ $item->id }}, '{{ $item->name }}')"
                                                        class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-12">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-gray-500 text-sm font-medium">Belum ada data item</p>
                                                <a href="{{ route('items.create') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">
                                                    Tambahkan item pertama Anda
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-6 px-2">
                    {{ $items->links() }}
                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div x-show="showModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 flex items-center justify-center backdrop-blur-sm z-50">

            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200 mx-4"
                 @click.away="showModal = false">

                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Konfirmasi Hapus</h2>
                </div>

                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    Apakah Anda yakin ingin menghapus item <span class="font-bold text-gray-900" x-text="deleteName"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition-colors duration-200"
                        @click="showModal = false">
                        Batal
                    </button>

                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg transition-all duration-200">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- ALPINE SCRIPT --}}
    <script>
        function itemPage() {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',

                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/items/${id}`;
                }
            }
        }
    </script>
</x-app-layout>