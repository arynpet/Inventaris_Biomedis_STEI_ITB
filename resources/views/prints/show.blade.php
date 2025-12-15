<x-app-layout>
<div class="p-6 max-w-3xl">

    <h1 class="text-2xl font-bold mb-4">Detail Print 3D</h1>

    <div class="bg-white shadow rounded p-5">

        <p><strong>User:</strong> {{ $print->user->name }}</p>
        <p><strong>Tanggal:</strong> {{ $print->date }}</p>
        <p><strong>Waktu:</strong> {{ $print->start_time }} - {{ $print->end_time }}</p>
        <p><strong>Durasi:</strong> {{ $print->duration }} menit</p>

        <hr class="my-3">

        <p><strong>Material:</strong> {{ $print->materialType->name ?? '-' }}</p>
        <p><strong>Jumlah:</strong> {{ $print->material_amount ?? '-' }} {{ $print->material_unit }}</p>
        <p><strong>Sumber:</strong> {{ $print->material_source ?? '-' }}</p>

        <hr class="my-3">

        <p><strong>Status:</strong> <span class="capitalize">{{ $print->status }}</span></p>

        <p class="mt-3"><strong>Catatan:</strong></p>
        <p>{{ $print->notes ?? '-' }}</p>

        <hr class="my-3">

        {{-- FILE --}}
        <p><strong>File:</strong></p>
        @if($print->file_path)
            <a href="{{ asset('storage/'.$print->file_path) }}"
               target="_blank"
               class="text-blue-600 underline">
               Download / Lihat File
            </a>
        @else
            <p class="text-gray-500">Tidak ada file.</p>
        @endif

    </div>

    <div class="mt-4">
        <a href="{{ route('prints.index') }}" class="text-blue-600 hover:underline">
            ← Kembali
        </a>
    </div>

</div>
</x-app-layout>
