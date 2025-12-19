<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Detail Ruangan
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 2500)"
             x-transition:enter="transform transition duration-300"
             x-transition:enter-start="translate-y-2 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transform transition duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-2 opacity-0"
             class="mx-4 my-4 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="py-6" x-data="roomPage()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- ROOM INFO --}}
            <div class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl p-8 mb-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">{{ $room->name }}</h3>
                                <div class="h-1 w-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mt-2"></div>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 ml-16 mb-4">{{ $room->description ?? '-' }}</p>

                        <div class="ml-16">
                            @php
                                $statusConfig = [
                                    'sedia' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'border' => 'border-green-300', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'penuh' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-300', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    'maintenance' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'border' => 'border-yellow-300', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                                ];
                                $config = $statusConfig[$room->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                            @endphp

                            <span class="inline-flex items-center px-4 py-2 {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }} rounded-xl text-sm font-semibold shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                </svg>
                                Status: {{ ucfirst($room->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITEM LIST --}}
            <div class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl p-8 border border-gray-100">

                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Barang di Ruangan Ini</h3>
                </div>

                @if ($room->items->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">Tidak ada barang di ruangan ini.</p>
                    </div>
                @else
                    <div class="grid gap-4">

                        @foreach ($room->items as $item)
                            <div class="group relative bg-white border-2 border-gray-100 rounded-2xl p-5 hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="flex items-start">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-800 text-lg mb-2">{{ $item->name }}</h4>
                                                
                                                <div class="flex flex-wrap gap-3">
                                                    <div class="flex items-center text-sm">
                                                        <div class="w-6 h-6 bg-purple-100 rounded-lg flex items-center justify-center mr-2">
                                                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-gray-600">
                                                            <span class="font-medium text-gray-700">No Asset:</span> 
                                                            {{ $item->asset_number ?? '-' }}
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex items-center text-sm">
                                                        <div class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center mr-2">
                                                            <svg class="w-3 h-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                                            </svg>
                                                        </div>
                                                        <span class="text-gray-600">
                                                            <span class="font-medium text-gray-700">Qty:</span> 
                                                            {{ $item->quantity }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button 
                                        @click="openMoveModal({{ $item->id }}, '{{ $item->name }}')"
                                        class="group/btn flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-0.5 transition-all duration-200 ml-4">
                                        <svg class="w-4 h-4 mr-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Pindahkan
                                    </button>
                                </div>

                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>

        {{-- MOVE MODAL --}}
        <div x-show="showMoveModal" x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">

            <div x-show="showMoveModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.away="showMoveModal = false"
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">

                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                    <div class="flex items-center text-white">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">Pindahkan Barang</h2>
                            <p class="text-blue-100 text-sm mt-1">Transfer item ke ruangan lain</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('rooms.moveItem') }}" method="POST" class="p-6">
                    @csrf

                    <input type="hidden" name="item_id" :value="moveItemId">

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <p class="text-gray-700">
                            Anda akan memindahkan <span class="font-bold text-blue-700" x-text="moveItemName"></span> ke ruangan lain.
                        </p>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Pilih Ruangan Tujuan
                        </label>
                        <select name="new_room_id" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 outline-none">
                            @foreach ($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="button"
                            @click="showMoveModal = false"
                            class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors duration-200">
                            Batal
                        </button>

                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
                            Pindahkan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
        function roomPage() {
            return {
                showMoveModal: false,
                moveItemId: null,
                moveItemName: '',

                openMoveModal(id, name) {
                    this.moveItemId = id;
                    this.moveItemName = name;
                    this.showMoveModal = true;
                }
            }
        }
    </script>

</x-app-layout>