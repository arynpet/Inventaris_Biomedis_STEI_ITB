<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Items Inventory') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    {{-- MAIN WRAPPER (Start x-data scope) --}}
    <div class="py-12" x-data="itemPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER SECTION --}}
            <x-page-header 
                title="Data Induk Barang" 
                description="Kelola inventaris dan aset laboratorium secara terpusat.">
                
                <x-slot:actions>
                    <a href="{{ route('items.out.index') }}" 
                        class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Keluar
                    </a>
                    
                    <x-button 
                        variant="purple" 
                        size="sm"
                        :href="route('items.index', array_merge(request()->all(), ['group_by_asset' => '1']))"
                        icon="M4 6h16M4 12h16m-7 6h7"
                        class="uppercase tracking-widest text-xs">
                        Group By Asset
                    </x-button>
                    
                    <x-button 
                        variant="primary" 
                        size="sm"
                        :href="route('items.create')"
                        icon="M12 4v16m8-8H4"
                        class="uppercase tracking-widest text-xs">
                        Tambah Barang
                    </x-button>
                </x-slot:actions>
            </x-page-header>

            {{-- FILTER & SEARCH --}}
            <x-filter-bar action="{{ route('items.index') }}">
                <div class="w-full lg:flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm transition"
                           placeholder="Cari Nama, No Asset, atau S/N...">
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
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

                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition-colors shadow-sm inline-flex items-center justify-center">
                        Filter
                    </button>

                    @if(request()->anyFilled(['search', 'status', 'room_id']))
                        <a href="{{ route('items.index') }}" class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold text-center transition-colors inline-flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </x-filter-bar>

            {{-- DATA TABLE --}}
            <x-data-table 
                :headers="['ID', 'Nama Barang', 'No Asset', 'Serial Number', 'QR', 'Ruangan', 'Qty', 'Status', 'Kondisi', 'Kategori', 'Aksi']"
                :pagination="$items->links()">
                
                @forelse ($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $item->id }}
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900 line-clamp-2 max-w-xs" title="{{ $item->name }}">
                                {{ $item->name }}
                            </div>
                        </td>

                        {{-- Asset Number --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                            {{ $item->asset_number ?? '-' }}
                        </td>

                        {{-- Serial Number --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-gray-800">
                            {{ $item->serial_number ?? '-' }}
                        </td>

                        {{-- QR Code --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($item->qr_code)
                                <div class="group relative inline-block">
                                    <img src="{{ asset('storage/'.$item->qr_code) }}" 
                                         alt="QR" 
                                         class="h-10 w-10 rounded border border-gray-200 bg-white p-0.5 transition-transform group-hover:scale-[3] group-hover:absolute group-hover:z-10 group-hover:shadow-xl cursor-zoom-in origin-left">
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">No QR</span>
                            @endif
                        </td>

                        {{-- Room --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="truncate max-w-[120px]" title="{{ $item->room->name ?? 'Unassigned' }}">
                                    {{ $item->room->name ?? 'Unassigned' }}
                                </span>
                            </div>
                        </td>

                        {{-- Quantity --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ $item->quantity }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-status-badge :status="$item->status" />
                        </td>

                        {{-- Condition --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $condClass = match($item->condition) {
                                    'good'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'damaged' => 'bg-orange-100 text-orange-700 border-orange-200',
                                    'broken'  => 'bg-red-100 text-red-700 border-red-200',
                                    default   => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                                $condLabel = match($item->condition) {
                                    'good'    => 'Baik',
                                    'damaged' => 'Rusak Ringan',
                                    'broken'  => 'Rusak Berat',
                                    default   => $item->condition,
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $condClass }}">
                                {{ $condLabel }}
                            </span>
                        </td>

                        {{-- Categories --}}
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                @forelse ($item->categories as $cat)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $cat->name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400">-</span>
                                @endforelse
                            </div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons 
                                :showRoute="route('items.show', $item->id)"
                                :editRoute="route('items.edit', $item->id)"
                                :deleteId="$item->id"
                                :deleteName="$item->name">
                                
                                @if($item->status !== 'dikeluarkan')
                                    <x-slot:additionalButtons>
                                        <a href="{{ route('items.out.create', $item->id) }}" 
                                           class="p-2 text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors" 
                                           title="Keluarkan Barang">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                        </a>
                                    </x-slot:additionalButtons>
                                @endif
                            </x-action-buttons>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-12">
                            <x-empty-state 
                                message="Tidak ada item ditemukan"
                                description="Coba ubah filter pencarian atau tambahkan item baru."
                                actionText="Tambah Item Baru"
                                :actionRoute="route('items.create')" />
                        </td>
                    </tr>
                @endforelse
            </x-data-table>
        </div> {{-- END max-w-7xl --}}

        {{-- DELETE CONFIRMATION MODAL --}}
        {{-- Modal ini sekarang ADA DI DALAM div yang memiliki x-data="itemPage()" --}}
        <div x-show="showModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            
            {{-- Backdrop --}}
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

            {{-- Modal Panel --}}
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
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Hapus Item</h3>
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
                            @csrf
                            @method('DELETE')
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

    </div> {{-- END py-12 / x-data scope --}}

    {{-- SCRIPT --}}
    <script>
        function itemPage() {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',
                confirmDelete(id, name) {
                    this.deleteName = name;
                    this.deleteUrl = `/items/${id}`;
                    this.showModal = true;
                }
            }
        }
    </script>
</x-app-layout>