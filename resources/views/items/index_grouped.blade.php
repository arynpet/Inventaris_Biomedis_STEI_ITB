<x-app-layout>
    <div class="p-6">

        {{-- Header dengan Filter --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Induk Barang (Dikelompokkan)</h1>
                <p class="text-sm text-gray-500 mt-1">Barang dikelompokkan berdasarkan Nomor Asset</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('items.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded-xl shadow hover:bg-gray-700">
                    Tampilan List
                </a>
                
                <a href="{{ route('items.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700">
                    + Tambah Barang
                </a>
            </div>
        </div>

        {{-- Filter Form --}}
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <form action="{{ route('items.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                <input type="hidden" name="group_by_asset" value="1">
                
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama, Serial, Asset..."
                           class="w-full rounded-lg border-gray-300">
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="dikeluarkan" {{ request('status') === 'dikeluarkan' ? 'selected' : '' }}>Dikeluarkan</option>
                    </select>
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                    <select name="room_id" class="w-full rounded-lg border-gray-300">
                        <option value="">Semua Ruangan</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('items.index', ['group_by_asset' => 1]) }}"
                   class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            </form>
        </div>

        {{-- Grouped Items --}}
        <div class="space-y-6">
            @forelse ($groupedItems as $assetNumber => $items)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    
                    {{-- Group Header --}}
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-white">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">
                                        Asset: {{ str_starts_with($assetNumber, 'no-asset-') ? '(Tanpa Asset Number)' : $assetNumber }}
                                    </h3>
                                    <p class="text-blue-100 text-sm">{{ $items->count() }} item(s)</p>
                                </div>
                            </div>

                            <div class="text-white text-right">
                                <p class="text-sm text-blue-100">Total Quantity</p>
                                <p class="text-2xl font-bold">{{ $items->sum('quantity') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Items in Group --}}
                    <div class="divide-y divide-gray-100">
                        @foreach ($items as $item)
                            <div class="p-5 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>

                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-800 text-lg mb-2">{{ $item->name }}</h4>
                                                
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                                    <div>
                                                        <span class="text-gray-500">Serial:</span>
                                                        <span class="font-medium text-gray-700">{{ $item->serial_number }}</span>
                                                    </div>
                                                    
                                                    <div>
                                                        <span class="text-gray-500">Ruangan:</span>
                                                        <span class="font-medium text-gray-700">{{ $item->room->name ?? '-' }}</span>
                                                    </div>
                                                    
                                                    <div>
                                                        <span class="text-gray-500">Qty:</span>
                                                        <span class="font-medium text-gray-700">{{ $item->quantity }}</span>
                                                    </div>
                                                    
                                                    <div>
                                                        <x-status-badge :status="$item->status" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                        <a href="{{ route('items.show', $item) }}"
                                           class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('items.edit', $item) }}"
                                           class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Tidak ada barang ditemukan</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>