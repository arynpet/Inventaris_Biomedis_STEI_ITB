<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Peminjaman
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-xl p-6">

                <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- ITEM --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Barang</label>
                        <select name="item_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                            @foreach($items as $item)
                                <option value="{{ $item->id }}"
                                    {{ $borrowing->item_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PEMINJAM --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Peminjam</label>
                        <select name="user_id" class="w-full border-gray-300 rounded-lg shadow-sm">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $borrowing->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- BORROW DATE --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Tanggal Pinjam</label>
                        <input type="date" name="borrow_date"
                            value="{{ $borrowing->borrow_date->format('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    {{-- RETURN DATE --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Tanggal Kembali</label>
                        <input type="date" name="return_date"
                            value="{{ $borrowing->return_date?->format('Y-m-d') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm">
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm">
                            <option value="borrowed"  {{ $borrowing->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="returned" {{ $borrowing->status == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="late"     {{ $borrowing->status == 'late' ? 'selected' : '' }}>Late</option>
                        </select>
                    </div>

                    {{-- NOTES --}}
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Catatan</label>
                        <textarea name="notes" 
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            rows="3">{{ $borrowing->notes }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('borrowings.index') }}"
                           class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                            Batal
                        </a>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
