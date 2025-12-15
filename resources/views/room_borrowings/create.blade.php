<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Peminjaman Ruangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg p-6">

                <form action="{{ route('room_borrowings.store') }}" method="POST">
                    @csrf

                    {{-- ROOM --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Ruangan</label>
                        <select name="room_id"
                            class="w-full border-gray-300 rounded-lg">
                            @foreach ($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- USER --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Peminjam</label>
                        <select name="user_id"
                            class="w-full border-gray-300 rounded-lg">
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TIME --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Mulai</label>
                            <input type="datetime-local" name="start_time"
                                class="w-full border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Selesai</label>
                            <input type="datetime-local" name="end_time"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                    </div>

                    {{-- PURPOSE --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Keperluan</label>
                        <input type="text" name="purpose"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    {{-- NOTES --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-lg"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
