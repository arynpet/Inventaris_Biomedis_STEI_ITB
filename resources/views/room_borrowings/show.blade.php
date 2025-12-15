<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Peminjaman Ruangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Peminjaman</h3>

                <div class="space-y-2 text-gray-700">
                    <p><b>Ruangan:</b> {{ $roomBorrowing->room->name }}</p>
                    <p><b>Peminjam:</b> {{ $roomBorrowing->user->name }}</p>
                    <p><b>Mulai:</b> {{ $roomBorrowing->start_time }}</p>
                    <p><b>Selesai:</b> {{ $roomBorrowing->end_time }}</p>
                    <p><b>Keperluan:</b> {{ $roomBorrowing->purpose }}</p>
                    <p><b>Status:</b> {{ ucfirst($roomBorrowing->status) }}</p>
                    <p><b>Catatan:</b> {{ $roomBorrowing->notes ?? '-' }}</p>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('room_borrowings.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Kembali
                    </a>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
