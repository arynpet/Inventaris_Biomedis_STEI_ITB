<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Add Item</h1>

        <form action="{{ route('items.store') }}" method="POST"
              class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Name</label>
                <input type="text" name="name"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('name') }}" required>
            </div>

            {{-- Asset Number --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Asset Number</label>
                <input type="text" name="asset_number"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('asset_number') }}">
            </div>

            {{-- SERIAL NUMBER --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Serial Number</label>
                <input type="text" name="serial_number"
                       class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       placeholder="SN-XXXX"
                       value="{{ old('serial_number') }}"
                       required>
            </div>


            {{-- Room --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Room</label>
                <select name="room_id"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                        required>
                    <option value="">-- Select Room --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Categories --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Categories</label>

                <select name="categories[]" multiple
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2 h-32">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (collect(old('categories'))->contains($category->id)) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <p class="text-xs text-gray-500 mt-1">Hold CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu.</p>
            </div>

            {{-- Quantity --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Quantity</label>
                <input type="number" name="quantity"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('quantity', 1) }}" required>
            </div>

            {{-- Source --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Source</label>
                <input type="text" name="source"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('source') }}">
            </div>

            {{-- Acquisition Year --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Acquisition Year</label>
                <input type="number" name="acquisition_year"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('acquisition_year') }}">
            </div>

            {{-- Placed in Service --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Date Placed in Service</label>
                <input type="date" name="placed_in_service_at"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('placed_in_service_at') }}">
            </div>

            {{-- Fiscal Group --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Fiscal Group</label>
                <input type="text" name="fiscal_group"
                       class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                       value="{{ old('fiscal_group') }}">
            </div>

            {{-- Grid untuk Status & Kondisi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Condition (BARU) --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Condition</label>
                    <select name="condition"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik (Good)</option>
                        <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak Ringan (Damaged)</option>
                        <option value="broken" {{ old('condition') == 'broken' ? 'selected' : '' }}>Rusak Berat (Broken)</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Status</label>
                    <select name="status"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ old('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('items.index') }}"
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