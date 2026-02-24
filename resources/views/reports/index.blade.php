<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan & Analisis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Hero Section --}}
            <div
                class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl overflow-hidden mb-10 text-white">
                <div class="absolute inset-0 bg-white/5 opacity-30 pattern-dots"></div> {{-- Placeholder for pattern if
                needed --}}
                <div class="relative p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="space-y-4 max-w-2xl">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 text-xs font-semibold backdrop-blur-sm border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Data Intelligence Center</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Pusat Laporan & Analisis Data</h1>
                        <p class="text-blue-100 text-lg leading-relaxed">
                            Unduh laporan resmi, ekspor data mentah untuk analisis mendalam, dan pantau kondisi aset
                            laboratorium secara real-time.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <svg class="w-40 h-40 text-white/10" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- Card 1: Excel Export --}}
                <div
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="bg-emerald-100/50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide">DATA
                                MENTAH</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-emerald-700 transition-colors">
                            Export Inventaris</h3>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed flex-grow">
                            Download seluruh database barang dalam format <strong class="text-emerald-600">.xlsx
                                (Excel)</strong>. Cocok untuk backup data, analisis pivot table, atau audit aset
                            tahunan.
                        </p>

                        <form action="{{ route('reports.items.excel') }}" method="GET" class="mt-auto">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-white border-2 border-emerald-100 text-emerald-700 font-bold py-3 px-4 rounded-xl hover:bg-emerald-50 hover:border-emerald-200 transition-all duration-200 shadow-sm group-hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download Excel
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Card 2: Laporan Bulanan --}}
                <div
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-red-500 to-pink-600"></div>
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="bg-red-100/50 text-red-700 text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide">DOKUMEN
                                RESMI</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-red-700 transition-colors">
                            Laporan Peminjaman</h3>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                            Cetak laporan peminjaman bulanan resmi. Dokumen ini berisi rekap transaksi peminjaman
                            mahasiswa dan dosen untuk arsip administrasi.
                        </p>

                        <form action="{{ route('reports.monthly.pdf') }}" method="POST" class="space-y-4 mt-auto">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Bulan</label>
                                    <div class="relative">
                                        <select name="month" required
                                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-red-500 focus:border-red-500 py-2.5 pl-3 pr-8 shadow-sm cursor-pointer hover:bg-white transition-colors">
                                            @for($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label
                                        class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tahun</label>
                                    <div class="relative">
                                        <select name="year" required
                                            class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-red-500 focus:border-red-500 py-2.5 pl-3 pr-8 shadow-sm cursor-pointer hover:bg-white transition-colors">
                                            @for($y = date('Y'); $y >= 2020; $y--)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold py-3 px-4 rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-lg shadow-red-500/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                                Generate PDF
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Card 3: Kondisi Barang (NEW) --}}
                <div
                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                    <div class="p-6 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="bg-amber-100/50 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full tracking-wide">KONDISI
                                ASET</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-amber-700 transition-colors">
                            Laporan Kondisi</h3>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed flex-grow">
                            Analisis menyeluruh tentang kesehatan aset. Menampilkan ringkasan barang Baik, Rusak Ringan,
                            dan Rusak Berat untuk perencanaan maintenance.
                        </p>

                        <a href="{{ route('reports.condition.pdf') }}"
                            class="w-full mt-auto flex items-center justify-center gap-2 bg-white border-2 border-amber-100 text-amber-700 font-bold py-3 px-4 rounded-xl hover:bg-amber-50 hover:border-amber-200 transition-all duration-200 shadow-sm group-hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Cetak Laporan
                        </a>
                    </div>
                </div>

            </div>

            {{-- Info Footer --}}
            <div
                class="mt-10 bg-blue-50/50 border border-blue-100 rounded-xl p-6 flex flex-col md:flex-row items-start md:items-center gap-4 text-blue-800">
                <div class="p-2 bg-blue-100 rounded-lg shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-sm">
                    <h4 class="font-bold mb-1">Butuh data kustom?</h4>
                    <p class="opacity-80">
                        Untuk data yang lebih spesifik, gunakan fitur <strong>Filter & Search</strong> di halaman <a
                            href="{{ route('items.index') }}"
                            class="underline decoration-blue-400 hover:text-blue-600">Inventaris</a> lalu unduh hasil
                        pencarian, atau hubungi Super Admin untuk akses query langsung.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>