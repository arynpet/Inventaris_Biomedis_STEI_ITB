<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Arsip Riwayat</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar peminjaman ruangan yang telah selesai atau ditolak.</p>
                </div>
                
                <a href="{{ route('room_borrowings.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 shadow-sm transition">
                    &larr; Kembali ke Aktif
                </a>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Ruangan</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Peminjam</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Waktu Penggunaan</th>
                                <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-xs">Surat</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Status Akhir</th>
                                <th class="px-6 py-4 text-right font-bold uppercase tracking-wider text-xs">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($histories as $h)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- RUANGAN --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-800">{{ $h->room->name }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $h->room->code }}</div>
                                    </td>

                                    {{-- PEMINJAM --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-gray-700">{{ $h->user->name }}</span>
                                    </td>

                                    {{-- WAKTU --}}
                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ \Carbon\Carbon::parse($h->start_time)->format('d M Y') }}</span>
                                            <span>{{ \Carbon\Carbon::parse($h->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($h->end_time)->format('H:i') }}</span>
                                        </div>
                                    </td>

                                    {{-- SURAT --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($h->surat_peminjaman)
                                            <a href="{{ Storage::url($h->surat_peminjaman) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-semibold">
                                                Download PDF
                                            </a>
                                        @else
                                            <span class="text-gray-300 text-xs italic">-</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @if($h->status == 'finished')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 uppercase">
                                                Selesai
                                            </span>
                                        @elseif($h->status == 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 uppercase">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>

                                    {{-- AKSI (Hanya Detail) --}}
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('room_borrowings.show', $h->id) }}" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                            <p class="font-medium">Belum ada riwayat peminjaman.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $histories->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>