<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Material Types
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-bold">Daftar Material Types</h1>

                <a href="{{ route('materials.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Material Type
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Name</th>

                            <th class="px-4 py-2 text-right">Stock</th>
<th class="px-4 py-2 text-center">Unit</th>
                            <th class="px-4 py-2 text-center">Aksi</th>

                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($materials as $type)
                            <tr class="border-t">
                                <td class="px-4 py-2 capitalize">{{ $type->category }}</td>
                                <td class="px-4 py-2">{{ $type->name }}</td>
                                <td class="px-4 py-2 text-right">
    {{ number_format($type->stock_balance, 2) }}
</td>
<td class="px-4 py-2 text-center uppercase">
    {{ $type->unit }}
</td>


                                <td class="px-4 py-2 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('materials.edit', $type->id) }}"
                                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Edit
                                        </a>

                                        <form action="{{ route('materials.destroy', $type->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
