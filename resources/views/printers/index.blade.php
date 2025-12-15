<x-app-layout>
<div class="p-6">

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Printer 3D</h1>

        <a href="{{ route('printers.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            + Tambah Printer
        </a>
    </div>

    <div class="bg-white shadow rounded p-4">
        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="p-2 text-left">Nama</th>
                    <th class="p-2 text-left">Tipe Material</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Tersedia Pada</th>
                    <th class="p-2 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($printers as $printer)
                    <tr class="border-b">
                        <td class="p-2">{{ $printer->name }}</td>

{{-- Tipe Material --}}
<td class="p-2">
    @if($printer->materialTypes->count())
        @foreach($printer->materialTypes as $mt)
            <span class="font-semibold capitalize block">
                {{ $mt->category }} - {{ $mt->name }}
            </span>
        @endforeach
    @else
        <span class="text-gray-400 italic">Tidak ada</span>
    @endif
</td>


                        <td class="p-2 capitalize">
                            @if($printer->status == 'available')
                                <span class="text-green-600 font-semibold">Available</span>
                            @elseif($printer->status == 'in_use')
                                <span class="text-orange-600 font-semibold">In Use</span>
                            @else
                                <span class="text-red-600 font-semibold">Maintenance</span>
                            @endif
                        </td>

                        <td class="p-2">
                            {{ $printer->available_at_formatted }}
                        </td>

                        <td class="p-2 flex gap-2 justify-center">
                            <a href="{{ route('printers.show', $printer->id) }}"
                               class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                                Detail
                            </a>

                            <a href="{{ route('printers.edit', $printer->id) }}"
                               class="px-3 py-1 bg-yellow-400 rounded hover:bg-yellow-500">
                                Edit
                            </a>

                            <form action="{{ route('printers.destroy', $printer->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus printer ini?')">
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
