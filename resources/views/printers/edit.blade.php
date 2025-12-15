<x-app-layout>
<div class="p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-4">Edit Printer</h1>

    <form action="{{ route('printers.update', $printer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Nama Printer</label>
            <input type="text" name="name" class="w-full border rounded p-2"
                   value="{{ $printer->name }}">
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
            <textarea name="description" class="w-full border rounded p-2">{{ $printer->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="available"   {{ $printer->status == 'available' ? 'selected' : '' }}>Available</option>
                <option value="in_use"      {{ $printer->status == 'in_use' ? 'selected' : '' }}>In Use</option>
                <option value="maintenance" {{ $printer->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Tersedia Pada</label>
            <input type="datetime-local"
                   name="available_at"
                   class="w-full border rounded p-2"
                   value="{{ $printer->available_at ? $printer->available_at->format('Y-m-d\TH:i') : '' }}">
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Update
        </button>
    </form>

</div>
</x-app-layout>
