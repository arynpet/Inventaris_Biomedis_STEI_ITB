<x-app-layout>
<div class="p-6 max-w-xl">

    <h1 class="text-2xl font-bold mb-4">Detail Printer</h1>

    <div class="bg-white shadow rounded p-4">

        <p><strong>Nama:</strong> {{ $printer->name }}</p>
        <p><strong>Deskripsi:</strong> {{ $printer->description ?? '-' }}</p>

        <p><strong>Status:</strong>
            <span class="capitalize">
                {{ str_replace('_', ' ', $printer->status) }}
            </span>
        </p>

        <p><strong>Tersedia Pada:</strong>
            {{ $printer->available_at_formatted }}
        </p>

        <hr class="my-3">

        <h2 class="text-lg font-bold mb-2">Riwayat Print Menggunakan Printer Ini</h2>

        @if($printer->prints->count() == 0)
            <p class="text-gray-500">Belum ada riwayat pemakaian.</p>
        @else
            <ul class="list-disc ml-5">
                @foreach($printer->prints as $p)
                    <li>
                        <span class="font-semibold">{{ $p->user->name }}</span>
                        — {{ $p->date }} ({{ $p->status }})
                    </li>
                @endforeach
            </ul>
        @endif

    </div>

    <div class="mt-4">
        <a href="{{ route('printers.index') }}" class="text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

</div>
</x-app-layout>
