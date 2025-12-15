<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Peminjam</h1>

        <form action="{{ route('peminjam-users.store') }}" method="POST"
              class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Nama</label>
                <input type="text" name="name" required
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 px-3 py-2">
            </div>

            {{-- NIM --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">NIM</label>
                <input type="text" name="nim"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 px-3 py-2">
            </div>

            {{-- Email --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email</label>
                <input type="email" name="email"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 px-3 py-2">
            </div>

            {{-- Phone --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Phone</label>
                <input type="text" name="phone"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 px-3 py-2">
            </div>

            {{-- Role --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Role</label>
                <select name="role"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 px-3 py-2">
                    <option value="">- Pilih Role -</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="asisten">Asisten</option>
                    <option value="dosen">Dosen</option>
                </select>
            </div>


            <label class="flex items-center gap-2">
    <input type="checkbox" name="is_trained"
           value="1"
           class="rounded border-gray-300"
           {{ old('is_trained', $user->is_trained ?? 0) ? 'checked' : '' }}>
    Sudah Mengikuti Pelatihan
</label>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('peminjam-users.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">
                    Cancel
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
                    Save
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
