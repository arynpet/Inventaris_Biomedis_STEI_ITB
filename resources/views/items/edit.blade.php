<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Item</h1>

        <form action="{{ route('items.update', $item->id) }}" method="POST"
            class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Name</label>
                <input type="text" name="name"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('name', $item->name) }}" required>
            </div>

            {{-- Brand --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Merk / Brand (Optional)</label>
                <input type="text" name="brand"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('brand', $item->brand) }}" placeholder="Contoh: Dell, Epson">
            </div>

            {{-- Type --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Tipe / Model (Optional)</label>
                <input type="text" name="type"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('type', $item->type) }}" placeholder="Contoh: L3110, Latitude 5420">
            </div>

            {{-- Asset Number --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Asset Number</label>
                <input type="text" name="asset_number"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('asset_number', $item->asset_number) }}">
            </div>

            {{-- Serial Number --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">
                    Serial Number
                </label>

                <input type="text" name="serial_number"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('serial_number', $item->serial_number) }}" required>

                <p class="text-xs text-gray-500 mt-1">
                    Serial number akan digunakan sebagai isi QR Code
                </p>
            </div>


            {{-- Room --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Room</label>
                <select name="room_id"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    required>
                    <option value="">-- Select Room --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id', $item->room_id) == $room->id ? 'selected' : '' }}>
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
                    @php
                        // Ambil ID kategori yang sudah ada di item untuk selected state
                        $selectedCategories = old('categories', $item->categories->pluck('id')->toArray());
                    @endphp

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <p class="text-xs text-gray-500 mt-1">
                    Hold CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu.
                </p>
            </div>

            {{-- Quantity --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Quantity</label>
                <input type="number" name="quantity"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('quantity', $item->quantity) }}" required>
            </div>

            {{-- Source --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Source</label>
                <input type="text" name="source"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('source', $item->source) }}">
            </div>

            {{-- Acquisition Year --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Acquisition Year</label>
                <input type="number" name="acquisition_year"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('acquisition_year', $item->acquisition_year) }}">
            </div>

            {{-- Placed in Service --}}
            @php
                // Format tanggal agar bisa masuk ke input type="date"
                $placed = old(
                    'placed_in_service_at',
                    $item->placed_in_service_at
                    ? $item->placed_in_service_at->format('Y-m-d')
                    : null
                );
            @endphp

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Date Placed in Service</label>
                <input type="date" name="placed_in_service_at"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ $placed }}">
            </div>

            {{-- Fiscal Group --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Fiscal Group</label>
                <input type="text" name="fiscal_group"
                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="{{ old('fiscal_group', $item->fiscal_group) }}">
            </div>

            {{-- Grid Condition & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Condition (BARU) --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Condition</label>
                    <select name="condition"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="good" {{ old('condition', $item->condition) == 'good' ? 'selected' : '' }}>Baik
                            (Good)</option>
                        <option value="damaged" {{ old('condition', $item->condition) == 'damaged' ? 'selected' : '' }}>
                            Rusak Ringan (Damaged)</option>
                        <option value="broken" {{ old('condition', $item->condition) == 'broken' ? 'selected' : '' }}>
                            Rusak Berat (Broken)</option>
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Status</label>
                    <select name="status"
                        class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="available" {{ old('status', $item->status) === 'available' ? 'selected' : '' }}>
                            Available</option>
                        <option value="borrowed" {{ old('status', $item->status) === 'borrowed' ? 'selected' : '' }}>
                            Borrowed</option>
                        <option value="maintenance" {{ old('status', $item->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                    Update
                </button>
            </div>

        </form>
    </div>
</x-app-layout>