<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Detail Item
        </h2>
    </x-slot>

    <div class="p-6 max-w-5xl mx-auto">

        <div class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- INFO SECTION --}}
                <div class="space-y-4">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $item->name }}</h3>
                        <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                    </div>

                    <div class="space-y-3">
                        {{-- Serial Number --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Serial Number</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->serial_number }}</p>
                            </div>
                        </div>

                        {{-- Asset Number --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-purple-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Asset Number</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->asset_number ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Room --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-green-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Ruangan</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->room->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-orange-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Jumlah</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->quantity }}</p>
                            </div>
                        </div>
                        
                        {{-- KONDISI (NEW) --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-pink-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-pink-200 transition-colors">
                                {{-- Icon Heartbeat --}}
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Kondisi</p>
                                @php
                                    $condColors = [
                                        'good'    => 'bg-emerald-100 text-emerald-800',
                                        'damaged' => 'bg-orange-100 text-orange-800',
                                        'broken'  => 'bg-red-100 text-red-800',
                                    ];
                                    $condLabels = [
                                        'good'    => 'Baik',
                                        'damaged' => 'Rusak Ringan',
                                        'broken'  => 'Rusak Berat',
                                    ];
                                    $cond = $item->condition ?? 'good';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $condColors[$cond] ?? 'bg-gray-100' }}">
                                    {{ $condLabels[$cond] ?? ucfirst($cond) }}
                                </span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="group flex items-start p-3 rounded-xl hover:bg-indigo-50 transition-colors duration-200">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Status Ketersediaan</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($item->status == 'available') bg-green-100 text-green-800
                                    @elseif($item->status == 'borrowed') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'maintenance') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- QR SECTION --}}
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-gradient-to-br from-gray-50 to-white border-2 border-dashed border-gray-200 rounded-2xl p-8 hover:border-blue-300 transition-all duration-300 hover:shadow-lg">
                        @if ($item->qr_code)
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-300"></div>
                                <div class="relative">
                                    <img src="{{ asset('storage/'.$item->qr_code) }}"
                                         class="w-56 h-56 rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-300"
                                         alt="QR {{ $item->serial_number }}">
                                </div>
                            </div>
                            <p class="text-center text-sm text-gray-600 mt-4 font-medium">Scan QR Code</p>
                        @else
                            <div class="flex flex-col items-center justify-center w-56 h-56">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <p class="text-gray-400 text-sm font-medium">QR belum tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('items.index') }}"
               class="group flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>

            <a href="{{ route('items.qr.pdf', $item->id) }}"
               class="group flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download QR (PDF)
            </a>
        </div>

    </div>

</x-app-layout>