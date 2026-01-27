<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tindak Lanjut Barang Kembali') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Daftar Tindak Lanjut</h3>
                    <p class="text-sm text-gray-500 mt-1">Barang yang dikembalikan dengan catatan kerusakan atau kondisi
                        khusus.</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('borrowings.index') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-gray-100 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase hover:bg-gray-200 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            {{-- SEARCH FILTER --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('borrowings.follow_ups') }}" method="GET" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm"
                        placeholder="Cari Peminjam atau Nama Barang...">
                    @if(request('search'))
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <a href="{{ route('borrowings.follow_ups') }}" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Barang</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Peminjam</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Tgl Kembali</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Kondisi</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Tindak Lanjut</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($borrowings as $b)
                                <tr class="hover:bg-blue-50/50 transition duration-150">
                                    {{-- BARANG --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $b->item->name ?? 'Item Dihapus' }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $b->item->serial_number ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- PEMINJAM --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs mr-3">
                                                {{ substr($b->borrower->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $b->borrower->name ?? 'User Dihapus' }}</div>
                                        </div>
                                    </td>

                                    {{-- TGL KEMBALI --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($b->return_date)->format('d M Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($b->return_date)->format('H:i') }} WIB</div>
                                    </td>

                                    {{-- KONDISI --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badges = [
                                                'good' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                'damaged' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                'broken' => 'bg-red-100 text-red-700 border-red-200',
                                            ];
                                            $labels = [
                                                'good' => 'Baik',
                                                'damaged' => 'Rusak Ringan',
                                                'broken' => 'Rusak Berat',
                                            ];
                                            $class = $badges[$b->return_condition] ?? 'bg-gray-100 text-gray-700';
                                            $label = $labels[$b->return_condition] ?? $b->return_condition;
                                        @endphp
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold border {{ $class }}">
                                            {{ $label }}
                                        </span>
                                    </td>

                                    {{-- TINDAK LANJUT --}}
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm text-gray-800 bg-yellow-50 p-2 rounded-lg border border-yellow-100">
                                            {{ $b->follow_up }}
                                        </div>
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('borrowings.show', $b->id) }}"
                                            class="text-blue-600 hover:text-blue-900 font-bold hover:underline">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center bg-gray-50">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="font-medium">Tidak ada data tindak lanjut.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $borrowings->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>