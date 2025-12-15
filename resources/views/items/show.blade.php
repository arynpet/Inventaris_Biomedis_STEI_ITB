{{-- resources/views/items/show.blade.php --}}
<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Detail Item</h2>
    </x-slot>

    <div class="p-6">

        <div class="bg-white shadow rounded-lg p-6 space-y-3">

            <p><strong>Ruang:</strong> {{ $item->room->nama_ruang ?? '-' }}</p>
            <p><strong>Jumlah Unit:</strong> {{ $item->jumlah_unit }}</p>
            <p><strong>Deskripsi:</strong> {{ $item->deskripsi }}</p>
            <p><strong>Sumber:</strong> {{ $item->sumber ?? '-' }}</p>
            <p><strong>Tahun Perolehan:</strong> {{ $item->tahun_perolehan ?? '-' }}</p>
            <p><strong>Date Place in Service:</strong> {{ $item->date_place_in_service ?? '-' }}</p>
            <p><strong>Kelompok Fiskal:</strong> {{ $item->kelompok_fiskal ?? '-' }}</p>
            <p><strong>Asset Category:</strong> {{ $item->asset_category ?? '-' }}</p>

        </div>

        <a href="{{ route('items.index') }}"
            class="mt-4 inline-block bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Kembali
        </a>

    </div>

</x-app-layout>
