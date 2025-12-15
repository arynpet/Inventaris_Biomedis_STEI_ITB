<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Ruangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-xl p-6">

                <form action="{{ route('rooms.store') }}" method="POST">
                    @csrf

                    {{-- CODE --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Kode Ruangan</label>
                        <input type="text" name="code"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            required>
                    </div>

                    {{-- NAME --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Nama Ruangan</label>
                        <input type="text" name="name"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            required>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="description"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            rows="3"></textarea>
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Status</label>
                        <select name="status"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                            <option value="sedia">Sedia</option>
                            <option value="dipinjam">Dipinjam</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('rooms.index') }}"
                            class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                            Batal
                        </a>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
