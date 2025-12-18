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
                <tr class="border-b bg-gray-100">
                    <th class="p-2 text-left">User</th>
                    <th class="p-2 text-left">Mesin</th>
                    <th class="p-2 text-left">Material</th>
                    <th class="p-2 text-right">Jumlah</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Tanggal</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($prints as $print)
                    <tr class="border-b">
                        <td class="p-2">{{ $print->user->name ?? '-' }}</td>

                        <td class="p-2">
                            {{ $print->printer->name ?? '-' }}
                        </td>

                        <td class="p-2">
                            {{ $print->materialType->name ?? '-' }}
                        </td>

                        <td class="p-2 text-right">
                            {{ $print->material_amount ?? '-' }}
                            {{ $print->material_unit }}
                        </td>

                        <td class="p-2 capitalize">
                            <span class="px-2 py-1 rounded text-sm
                                {{ $print->status === 'done' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $print->status === 'canceled' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $print->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $print->status === 'printing' ? 'bg-blue-100 text-blue-700' : '' }}">
                                {{ $print->status }}
                            </span>
                        </td>

                        <td class="p-2">{{ $print->date }}</td>

                        <td class="p-2 flex flex-wrap gap-2 justify-center">

                            <a href="{{ route('prints.show', $print->id) }}"
                               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                Detail
                            </a>

                            @if ($print->status === 'pending' || $print->status === 'printing')
                                {{-- SELESAI --}}
                                <form action="{{ route('prints.update', $print->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="done">
                                    <button class="px-3 py-1 bg-green-600 text-white rounded">
                                        Selesai
                                    </button>
                                </form>

                                @if ($print->status === 'pending')
<form action="{{ route('prints.update', $print->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="printing">
    <button class="px-3 py-1 bg-blue-600 text-white rounded">
        Mulai Print
    </button>
</form>
@endif


                                {{-- BATAL --}}
                                <form action="{{ route('prints.update', $print->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="canceled">
                                    <button class="px-3 py-1 bg-red-500 text-white rounded">
                                        Batalkan
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('prints.destroy', $print->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-700 text-white rounded">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</x-app-layout>
