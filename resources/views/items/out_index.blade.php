<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-orange-600">
            Riwayat Barang Keluar
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- TOMBOL KEMBALI --}}
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('items.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-gray-800 transition font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Inventaris
                </a>
            </div>

            {{-- TABEL WRAPPER --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        {{-- THEAD --}}
                        <thead class="bg-orange-50 text-orange-800 border-b border-orange-100">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Item Info</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Penerima</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Tanggal Keluar</th>
                                <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>

                        {{-- TBODY --}}
                        <tbody class="divide-y divide-gray-100">
                            @forelse($items as $item)
                                @php $log = $item->latestLog; @endphp
                                
                                <tr class="hover:bg-orange-50/40 transition duration-150">
                                    {{-- Kolom 1: Item --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $item->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono mt-1">SN: {{ $item->serial_number }}</div>
                                    </td>

                                    {{-- Kolom 2: Penerima --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-800 font-medium">{{ $log->recipient_name ?? '-' }}</div>
                                    </td>

                                    {{-- Kolom 3: Tanggal --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log && $log->out_date)
                                            <div class="flex items-center gap-1.5 text-gray-700">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $log->out_date->format('d M Y') }}
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Kolom 4: Aksi (Ke Detail) --}}
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('items.show', $item->id) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-600 rounded-xl font-bold text-xs hover:bg-blue-50 hover:border-blue-300 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center text-gray-500 italic">Belum ada riwayat barang keluar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 px-2">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>