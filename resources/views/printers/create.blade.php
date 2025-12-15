<x-app-layout>
<div class="p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-4">Tambah Printer</h1>

    <form action="{{ route('printers.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Nama Printer</label>
            <input type="text" name="name" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
    <label class="font-semibold">Tipe Material</label>
<select name="category" class="w-full border rounded p-2">
    <option value="filament" {{ old('category', $printer->category ?? '') == 'filament' ? 'selected' : '' }}>
        Filament
    </option>
    <option value="resin" {{ old('category', $printer->category ?? '') == 'resin' ? 'selected' : '' }}>
        Resin
    </option>
</select>

</div>


        <div class="mb-4">
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="available">Available</option>
                <option value="in_use">In Use</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Tersedia Pada (opsional)</label>
            <input type="datetime-local" name="available_at" class="w-full border rounded p-2">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Simpan
        </button>
    </form>

</div>
</x-app-layout>
