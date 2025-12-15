<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            History Peminjaman Barang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Riwayat Pengembalian Barang</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelola dan cetak riwayat peminjaman barang</p>
                </div>

                <div class="flex gap-3">

                    {{-- Filter Tanggal --}}
                    <form action="{{ route('borrowings.history') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="date" name="from" value="{{ $from }}"
                                   class="border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>

                        <span class="text-gray-400">—</span>

                        <div class="relative">
                            <input type="date" name="to" value="{{ $to }}"
                                   class="border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" />
                        </div>

                        <button class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg font-semibold text-sm transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </button>
                    </form>

                    {{-- Tombol Print --}}
                    <button
                        onclick="document.getElementById('printModal').classList.remove('hidden')"
                        class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg shadow-md hover:shadow-lg font-semibold text-sm transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print
                    </button>

                    <a href="{{ route('borrowings.index') }}"
                        class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white rounded-lg shadow-md hover:shadow-lg font-semibold text-sm transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- WHITE WRAPPER --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">

                {{-- TABLE WRAPPER --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border-collapse">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border-b-2 border-gray-200">
                                <tr>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">#</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Barang</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Peminjam</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Tanggal Pinjam</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Tanggal Dikembalikan</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Durasi</th>
                                    <th class="px-4 py-4 text-left font-bold text-xs tracking-wider uppercase">Status</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($history as $borrow)
                                    @php
                                        $borrowDate = \Carbon\Carbon::parse($borrow->borrow_date);
                                        $returnDate = \Carbon\Carbon::parse($borrow->return_date);

                                        $diff = $borrowDate->diff($returnDate);

                                        $durasi = '';
                                        if ($diff->d > 0) $durasi .= $diff->d . ' hari ';
                                        $durasi .= $diff->h . ' jam ' . $diff->i . ' menit';
                                    @endphp

                                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 font-bold text-xs">
                                                {{ $loop->iteration }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-4 py-4">
                                            <span class="font-semibold text-gray-900">{{ $borrow->item->name }}</span>
                                        </td>
                                        
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                                                    <span class="text-purple-700 font-bold text-xs">
                                                        {{ strtoupper(substr($borrow->borrower->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <span class="text-gray-700 font-medium">{{ $borrow->borrower->name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900 font-medium">{{ $borrowDate->translatedFormat('d M Y') }}</span>
                                                <span class="text-gray-500 text-xs">{{ $borrowDate->translatedFormat('H:i') }} WIB</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900 font-medium">{{ $returnDate->translatedFormat('d M Y') }}</span>
                                                <span class="text-gray-500 text-xs">{{ $returnDate->translatedFormat('H:i') }} WIB</span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 rounded-full text-xs font-semibold border border-indigo-200 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $durasi }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gradient-to-r from-green-100 to-green-200 text-green-700 rounded-full text-xs font-bold border border-green-300 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Returned
                                            </span>
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-12">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="text-gray-500 text-sm font-medium">Belum ada history peminjaman</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PRINT --}}
    <div id="printModal" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center backdrop-blur-sm z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-200 mx-4">

            <div class="flex items-center gap-3 mb-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Print History</h3>
            </div>

            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                Pilih rentang tanggal untuk mencetak laporan riwayat peminjaman barang
            </p>

            <form action="{{ route('borrowings.historyPdf') }}" target="_blank" method="GET" class="space-y-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                    <div class="relative">
                        <input type="date" name="from"
                               class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                    <div class="relative">
                        <input type="date" name="to"
                               class="w-full border border-gray-300 px-4 py-2.5 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button"
                        onclick="document.getElementById('printModal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition-colors duration-200">
                        Batal
                    </button>

                    <button class="group inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white rounded-lg shadow-md hover:shadow-lg font-semibold text-sm transition-all duration-200">
                        <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Sekarang
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>