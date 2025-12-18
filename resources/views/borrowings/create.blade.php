<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">New Borrowing</h1>

        <form action="{{ route('borrowings.store') }}" method="POST"
              class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5">
            @csrf

            {{-- Borrower --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Borrower</label>
                <select name="user_id"
                        class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">-- Select Borrower --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Item --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Item</label>
                <select name="item_id"
                        class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">-- Select Item --</option>
                    @foreach($items as $i)
                        <option value="{{ $i->id }}" {{ old('item_id') == $i->id ? 'selected' : '' }}>
                            {{ $i->name }}
                        </option>
                    @endforeach
                </select>
                @error('item_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Borrow Date --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Borrow Date & Time</label>
                
                <input type="datetime-local" 
                    name="borrow_date"
                    class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                    value="{{ old('borrow_date', now()->format('Y-m-d\TH:i')) }}" 
                    required>
                    
                @error('borrow_date') 
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Return Date --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Return Date (optional)</label>
                <input type="datetime-local" name="return_date"
                       class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('return_date') }}">
                @error('return_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Notes</label>
                <textarea name="notes"
                          class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500 h-24"
                >{{ old('notes') }}</textarea>
                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('borrowings.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                    Cancel
                </a>

                <button class="px-4 py-2 bg-blue-600 rounded-xl text-white hover:bg-blue-700 transition">
                    Save
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
