<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Print 3D') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Arsip Cetak</h3>
                    <p class="text-sm text-gray-500 mt-1">Riwayat pencetakan yang telah selesai atau dibatalkan.</p>
                </div>
                <a href="{{ route('prints.index') }}" class="inline-flex items-center px-4 py-2.5 bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 shadow-sm transition">
                    &larr; Kembali ke Antrian
                </a>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('prints.history') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:flex-1 relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-4 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400" placeholder="Cari User atau File...">
                    </div>
                    <div class="w-full md:w-auto">
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer h-[42px]">
                            <option value="">Semua Status</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition h-[42px]">Filter</button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">User</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">File</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Material</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Waktu</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Status Akhir</th>
                                <th class="px-6 py-4 text-right font-bold uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($histories as $print)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $print->user->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $print->file_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $print->materialType->name ?? '-' }} ({{ $print->material_amount }} {{ $print->material_unit }})
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                        {{ \Carbon\Carbon::parse($print->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($print->status == 'done')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200 uppercase">
                                                Selesai
                                            </span>
                                        @elseif($print->status == 'canceled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200 uppercase">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('prints.show', $print->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold text-xs hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        <p class="font-medium">Belum ada riwayat.</p>
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