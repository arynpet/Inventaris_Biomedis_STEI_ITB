<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permintaan Peminjaman (Pending)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($loans->isEmpty())
                        <p class="text-gray-500 text-center py-4">Tidak ada permintaan peminjaman yang pending.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Mahasiswa</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Barang</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tujuan</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Penanggung Jawab</th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($loans as $loan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $loan->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                {{ $loan->user->name }}
                                                <div class="text-xs text-gray-500">{{ $loan->user->nim }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                {{ $loan->item->name }}
                                                <div class="text-xs text-gray-500">Stok: {{ $loan->item->quantity }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                                {{ $loan->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <span class="font-semibold text-blue-600 block mb-1">Ruang:
                                                    {{ $loan->ruang_pakai ?? '-' }}</span>
                                                {{ Str::limit($loan->purpose, 30) }}
                                                <div class="text-xs text-gray-400 mt-1">Full: {{ $loan->purpose }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                                {{ $loan->penanggung_jawab ?? '-' }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">

                                                {{-- APPROVE FORM --}}
                                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST"
                                                    onsubmit="return confirm('Stok akan berkurang. Lanjutkan?');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200 transition">
                                                        Terima
                                                    </button>
                                                </form>

                                                {{-- REJECT FORM --}}
                                                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST"
                                                    onsubmit="return confirm('Tolak permintaan ini?');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition">
                                                        Tolak
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>