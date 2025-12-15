<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Peminjaman Ruangan
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-lg p-6">

                <form action="{{ route('room_borrowings.update', $roomBorrowing->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ROOM --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Ruangan</label>
                        <select name="room_id"
                            class="w-full border-gray-300 rounded-lg">
                            @foreach ($rooms as $r)
                                <option value="{{ $r->id }}"
                                    {{ $r->id == $roomBorrowing->room_id ? 'selected' : '' }}>
                                    {{ $r->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- USER --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Peminjam</label>
                        <select name="user_id"
                            class="w-full border-gray-300 rounded-lg">
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ $u->id == $roomBorrowing->user_id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TIME --}}
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Mulai</label>
                            <input type="datetime-local" name="start_time"
                                value="{{ $roomBorrowing->start_time }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Selesai</label>
                            <input type="datetime-local" name="end_time"
                                value="{{ $roomBorrowing->end_time }}"
                                class="w-full border-gray-300 rounded-lg">
                        </div>
                    </div>

                    {{-- PURPOSE --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Keperluan</label>
                        <input type="text" name="purpose"
                            value="{{ $roomBorrowing->purpose }}"
                            class="w-full border-gray-300 rounded-lg">
                    </div>

                    {{-- NOTES --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-lg">{{ $roomBorrowing->notes }}</textarea>
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg">
                            <option value="pending" {{ $roomBorrowing->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $roomBorrowing->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $roomBorrowing->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="finished" {{ $roomBorrowing->status == 'finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>
