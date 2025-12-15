<x-app-layout>
<div class="p-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Peminjaman Print 3D</h1>

        <a href="{{ route('prints.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Tambah Peminjaman
        </a>
    </div>

    <div class="bg-white shadow rounded p-4">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">User</th>
                    <th class="p-2 text-left">Nama Mesin</th>   {{-- ⬅ Tambahan --}}
                    <th class="p-2 text-left">Material</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($prints as $print)
                    <tr class="border-b">
                        <td class="p-2">
                            {{ $print->user->name ?? '-' }}
                        </td>

                        {{-- ⬅ Nama Mesin Printer --}}
                        <td class="p-2">
                            @if ($print->printer)
                                <span class="font-semibold">
                                    {{ $print->printer->name }}
                                </span>
                                <span class="text-gray-500 text-sm">
                                    ({{ $print->printer->category }})
                                </span>
                            @else
                                <span class="text-gray-400 italic">Tidak ada mesin</span>
                            @endif
                        </td>

                        {{-- Material --}}
                        <td class="p-2">
                            {{ $print->materialType->name ?? '-' }}
                        </td>

                        {{-- Status --}}
                        <td class="p-2 capitalize">
                            {{ $print->status }}
                        </td>

                        <td class="p-2">
                            {{ $print->date }}
                        </td>

                        <td class="p-2 flex gap-2 justify-center">
                            <a href="{{ route('prints.show', $print->id) }}"
                               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                Detail
                            </a>

                            <a href="{{ route('prints.edit', $print->id) }}"
                               class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500">
                                Edit
                            </a>

                            <form action="{{ route('prints.destroy', $print->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data peminjaman ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
</x-app-layout>
