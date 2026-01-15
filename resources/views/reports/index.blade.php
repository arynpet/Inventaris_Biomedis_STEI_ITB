<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📊 Laporan & Export Data</h1>
                <p class="text-sm text-gray-500">Download laporan inventaris dan peminjaman</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Excel Export Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Export Data Inventaris</h3>
                        <p class="text-xs text-gray-500">Format: Microsoft Excel (.xlsx)</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Download seluruh data barang inventaris dalam format Excel untuk keperluan analisis atau backup
                    data.
                </p>

                <form action="{{ route('reports.items.excel') }}" method="GET">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-3 px-4 rounded-xl hover:from-green-700 hover:to-green-800 transition shadow-lg shadow-green-500/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download Excel
                    </button>
                </form>
            </div>

            {{-- PDF Monthly Report Card --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">Laporan Peminjaman Bulanan</h3>
                        <p class="text-xs text-gray-500">Format: PDF</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mb-4">
                    Generate laporan peminjaman per bulan untuk kebutuhan dokumentasi dan audit.
                </p>

                <form action="{{ route('reports.monthly.pdf') }}" method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Bulan</label>
                            <select name="month" required
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                            <select name="year" required
                                class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-bold py-3 px-4 rounded-xl hover:from-red-700 hover:to-red-800 transition shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Generate PDF
                    </button>
                </form>
            </div>

        </div>

        {{-- Info Box --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-800">
                <strong>Tips:</strong> File Excel cocok untuk analisis data di software seperti Microsoft Excel atau
                Google Sheets.
                File PDF cocok untuk dokumentasi resmi atau dicetak.
            </div>
        </div>

    </div>
</x-app-layout>