<x-app-layout>

    <x-slot name="header">
        <h2 class="text-xl font-semibold">Detail Item</h2>
    </x-slot>

    <div class="p-6 max-w-4xl mx-auto">

        <div class="bg-white shadow rounded-2xl p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- INFO --}}
            <div class="space-y-2 text-sm">
                <p><strong>Nama:</strong> {{ $item->name }}</p>
                <p><strong>Serial Number:</strong> {{ $item->serial_number }}</p>
                <p><strong>Asset Number:</strong> {{ $item->asset_number ?? '-' }}</p>
                <p><strong>Ruangan:</strong> {{ $item->room->name ?? '-' }}</p>
                <p><strong>Jumlah:</strong> {{ $item->quantity }}</p>
                <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
            </div>

            {{-- QR --}}
            <div class="flex flex-col items-center justify-center border rounded-xl p-4">
                @if ($item->qr_code)
                    <img src="{{ asset('storage/'.$item->qr_code) }}"
                         class="w-48 h-48 border rounded"
                         alt="QR {{ $item->serial_number }}">
                @else
                    <p class="text-gray-400 text-sm">QR belum tersedia</p>
                @endif
            </div>

        </div>

        {{-- ACTION --}}
        <div class="mt-6 flex gap-3">
            <a href="{{ route('items.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                Kembali
            </a>

            <a href="{{ route('items.qr.pdf', $item->id) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Download QR (PDF)
            </a>
        </div>

    </div>

</x-app-layout>
