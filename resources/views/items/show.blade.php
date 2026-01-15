<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Detail Item
        </h2>
    </x-slot>

    <div class="p-6 max-w-5xl mx-auto">

        <div
            class="bg-gradient-to-br from-white to-gray-50 shadow-xl rounded-3xl p-8 border border-gray-100 hover:shadow-2xl transition-all duration-300">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- INFO SECTION --}}
                <div class="space-y-4">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $item->name }}</h3>
                        <div class="h-1 w-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                    </div>

                    <div class="space-y-3">
                        {{-- Serial Number --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-blue-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Serial Number</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->serial_number }}</p>
                            </div>
                        </div>

                        {{-- Asset Number --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-purple-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Asset Number</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->asset_number ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Room --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-green-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200 transition-colors">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Ruangan</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->room->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Merk & Tipe (NEW) --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-teal-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-teal-200 transition-colors">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Merk / Tipe</p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $item->brand ?? '-' }}
                                    <span class="text-gray-400 mx-1">/</span>
                                    {{ $item->type ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-orange-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Jumlah</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->quantity }}</p>
                            </div>
                        </div>

                        {{-- KONDISI --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-pink-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-pink-200 transition-colors">
                                <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Kondisi</p>
                                @php
                                    $condColors = ['good' => 'bg-emerald-100 text-emerald-800', 'damaged' => 'bg-orange-100 text-orange-800', 'broken' => 'bg-red-100 text-red-800'];
                                    $condLabels = ['good' => 'Baik', 'damaged' => 'Rusak Ringan', 'broken' => 'Rusak Berat'];
                                    $cond = $item->condition ?? 'good';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $condColors[$cond] ?? 'bg-gray-100' }}">
                                    {{ $condLabels[$cond] ?? ucfirst($cond) }}
                                </span>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-indigo-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Status Ketersediaan</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($item->status == 'available') bg-green-100 text-green-800
                                    @elseif($item->status == 'borrowed') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'maintenance') bg-red-100 text-red-800
                                    @elseif($item->status == 'dikeluarkan') bg-gray-800 text-white
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- ADDITIONAL INFO: SOURCE & FISCAL --}}
                    <div class="pt-4 mt-4 border-t border-gray-100 space-y-3">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Informasi Aset</h4>

                        {{-- Tahun Perolehan & Sumber --}}
                        <div class="grid grid-cols-2 gap-3">
                            {{-- Tahun --}}
                            <div
                                class="group flex flex-col p-3 rounded-xl bg-gray-50 hover:bg-teal-50 transition-colors duration-200">
                                <p class="text-xs text-gray-500 font-medium mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Tahun Perolehan
                                </p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->acquisition_year ?? '-' }}</p>
                            </div>

                            {{-- Sumber --}}
                            <div
                                class="group flex flex-col p-3 rounded-xl bg-gray-50 hover:bg-teal-50 transition-colors duration-200">
                                <p class="text-xs text-gray-500 font-medium mb-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    Sumber
                                </p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->source ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Fiskal --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-rose-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-rose-200 transition-colors">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Kelompok Fiskal</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->fiscal_group ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Tanggal Mulai Pakai --}}
                        <div
                            class="group flex items-start p-3 rounded-xl hover:bg-cyan-50 transition-colors duration-200">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-cyan-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-cyan-200 transition-colors">
                                <svg class="w-4 h-4 text-cyan-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Mulai Digunakan</p>
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $item->placed_in_service_at ? $item->placed_in_service_at->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- RIGHT COLUMN: IMAGE & QR --}}
                <div class="flex flex-col gap-6">

                    {{-- ITEM IMAGE --}}
                    <div class="bg-white p-2 rounded-2xl shadow-md border border-gray-100">
                        <img src="{{ $item->optimized_image }}" alt="{{ $item->name }}"
                            class="w-full h-64 object-cover rounded-xl bg-gray-50"
                            onerror="this.src='https://placehold.co/600x400?text=No+Image'">
                        <p class="text-xs text-center text-gray-400 mt-2 italic">Foto Barang</p>
                    </div>

                    {{-- QR SECTION --}}
                    <div class="flex flex-col items-center justify-center">
                        <div
                            class="bg-gradient-to-br from-gray-50 to-white border-2 border-dashed border-gray-200 rounded-2xl p-8 hover:border-blue-300 transition-all duration-300 hover:shadow-lg">
                            @if ($item->qr_code)
                                <div class="relative group">
                                    <div
                                        class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-300">
                                    </div>
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $item->qr_code) }}"
                                            class="w-56 h-56 rounded-xl shadow-lg transform group-hover:scale-105 transition-transform duration-300"
                                            alt="QR {{ $item->serial_number }}">
                                    </div>
                                </div>
                                <p class="text-center text-sm text-gray-600 mt-4 font-medium">Scan QR Code</p>
                            @else
                                <div class="flex flex-col items-center justify-center w-56 h-56">
                                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-400 text-sm font-medium">QR belum tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{--
                ================================================
                SECTION BARU: DETAIL PENGELUARAN (Jika Ada)
                ================================================
                --}}
                @if($item->status == 'dikeluarkan' && $item->latestLog)
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Informasi Pengeluaran Barang</h3>
                        </div>

                        <div
                            class="bg-orange-50/50 rounded-2xl p-6 border border-orange-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Detail Text --}}
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs uppercase text-gray-500 font-bold tracking-wider">Penerima</p>
                                    <p class="text-gray-900 font-semibold text-lg">{{ $item->latestLog->recipient_name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500 font-bold tracking-wider">Tanggal Keluar</p>
                                    <p class="text-gray-900 font-medium">{{ $item->latestLog->out_date->format('d F Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500 font-bold tracking-wider">Alasan / Keterangan
                                    </p>
                                    <p class="text-gray-700 italic">"{{ $item->latestLog->reason ?? '-' }}"</p>
                                </div>
                            </div>

                            {{-- Action Files --}}
                            <div class="flex flex-col justify-center gap-3 border-l border-orange-200/50 pl-0 md:pl-6">

                                {{-- Button Download File Upload --}}
                                @if($item->latestLog->reference_file)
                                    <a href="{{ asset('storage/' . $item->latestLog->reference_file) }}" target="_blank"
                                        class="flex items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-md transition group">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-100 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">File Bukti / Surat</p>
                                                <p class="text-xs text-gray-500">Klik untuk melihat file</p>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>
                                @endif

                                {{-- Button Cetak PDF Sistem --}}
                                <a href="{{ route('items.out.pdf', $item->id) }}" target="_blank"
                                    class="flex items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-xl hover:border-red-400 hover:shadow-md transition group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="p-2 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-100 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Cetak Surat Jalan</p>
                                            <p class="text-xs text-gray-500">Download PDF Resmi Sistem</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500 transition" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>

                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('items.index') }}"
                    class="group flex items-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                <a href="{{ route('items.qr.pdf', $item->id) }}"
                    class="group flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Download QR (PDF)
                </a>
            </div>

        </div>

</x-app-layout>