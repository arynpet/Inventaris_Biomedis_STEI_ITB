<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peminjaman Barang
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 2500)"
             class="mx-4 my-4 p-4 bg-green-500 text-white rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
<div class="flex justify-between mb-4">
    <h3 class="text-lg font-semibold text-gray-800">Daftar Peminjaman</h3>

    <div class="space-x-2">
        <a href="{{ route('borrowings.history') }}"
            class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 shadow">
            History Peminjaman
        </a>

        <a href="{{ route('borrowings.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
            + Tambah Peminjaman
        </a>
    </div>
</div>


            {{-- TABLE WRAPPER --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">#</th>
                                <th class="px-4 py-3 text-left">Barang</th>
                                <th class="px-4 py-3 text-left">Peminjam</th>
                                <th class="px-4 py-3 text-left">Tanggal Pinjam</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($borrowings as $borrow)
                                @php
                                    $color = [
                                        'borrowed' => 'yellow',
                                        'returned' => 'green',
                                        'late' => 'red'
                                    ];
                                @endphp
    
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3">{{ $borrow->item->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $borrow->borrower->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $borrow->borrow_date }}</td>

                                    {{-- STATUS BADGE --}}
                                    <td class="px-4 py-3">
                                        <span class="
                                            px-2 py-1 rounded text-xs
                                            bg-{{ $color[$borrow->status] }}-100
                                            text-{{ $color[$borrow->status] }}-700
                                            border border-{{ $color[$borrow->status] }}-300
                                        ">
                                            {{ ucfirst($borrow->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 space-x-2">
                                        <a href="{{ route('borrowings.show', $borrow->id) }}"
                                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                            Detail
                                        </a>

                                        <a href="{{ route('borrowings.edit', $borrow->id) }}"
                                           class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Edit
                                        </a>

<form action="{{ route('borrowings.return', $borrow->id) }}"
      method="POST" class="inline"
      onsubmit="return confirm('Tandai sebagai dikembalikan?');">
    @csrf
    <button class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
        Dikembalikan
    </button>
</form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
