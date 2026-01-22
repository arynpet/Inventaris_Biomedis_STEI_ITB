<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Item</h1>

        <form action="{{ route('items.update', $item->id) }}" method="POST"
            class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5" enctype="multipart/form-data">
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

            {{-- Image Edit Section (Hybrid) --}}
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200"
                x-data="{ imageUrl: '{{ old('image_url', filter_var($item->image_path, FILTER_VALIDATE_URL) ? $item->image_path : '') }}' }"
                @remote-image-selected.window="console.log('✅ Event:', $event.detail); imageUrl = $event.detail.url">

                <label class="block mb-4 font-bold text-gray-700 text-lg">Foto Barang</label>

                {{-- Show Current Image if no new URL is entered --}}
                @if($item->image_path)
                    <div class="mb-6 flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-200 shadow-sm"
                        x-show="!imageUrl">
                        <img src="{{ $item->optimized_image }}" alt="Current Image"
                            class="w-20 h-20 object-cover rounded-lg bg-gray-100">
                        <div>
                            <p class="font-bold text-gray-700">Foto Saat Ini</p>
                            <p class="text-xs text-gray-500">Gambar ini akan diganti hanya jika Anda mengupload file baru
                                atau memasukkan URL.</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Opsi A: Upload File --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-600 text-sm">Ganti File (Local)</label>
                        <div class="relative">
                            <input type="file" name="image"
                                @change="if($el.value) { imageUrl = ''; $refs.urlInput.disabled = true; } else { $refs.urlInput.disabled = false; }"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Max: 2MB.</p>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Opsi B: External URL or Remote Upload --}}
                    <div class="relative">
                        <label class="block mb-2 font-bold text-gray-600 text-sm">Atau Paste Link / Scan HP</label>
                        <div class="flex items-center gap-2">
                            <div class="bg-gray-100 p-2 rounded-lg text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                    </path>
                                </svg>
                            </div>

                            {{-- REACTIVE INPUT dengan x-model --}}
                            <input type="url" name="image_url" x-ref="urlInput" x-model="imageUrl"
                                class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 text-sm disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                                placeholder="https://example.com/image.jpg">

                            {{-- SEARCH BUTTON (Pixabay) --}}
                            <button type="button"
                                @click="$dispatch('open-image-modal', { query: '{{ addslashes($item->name) }}' })"
                                class="bg-blue-600 text-white px-3 py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition flex items-center gap-1 whitespace-nowrap shadow-md shadow-blue-500/30"
                                title="Cari di Pixabay">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Cari
                            </button>

                            {{-- SCAN HP BUTTON --}}
                            <button type="button" @click="$dispatch('open-remote-upload')"
                                class="bg-purple-600 text-white px-3 py-2 rounded-xl text-sm font-bold hover:bg-purple-700 transition flex items-center gap-1 whitespace-nowrap shadow-md shadow-purple-500/30"
                                title="Scan QR untuk upload dari HP">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                    </path>
                                </svg>
                                HP
                            </button>
                        </div>
                        @error('image_url')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- IMAGE PREVIEW (Reactive) --}}
                <div x-show="imageUrl" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="mt-6 relative inline-block">
                    <div class="relative group">
                        <img :src="imageUrl" alt="New Image Preview"
                            class="w-64 h-64 object-cover rounded-xl border-2 border-gray-300 shadow-lg"
                            x-on:error="console.error('Failed to load image:', imageUrl)">

                        {{-- Clear Button --}}
                        <button type="button" @click="imageUrl = ''"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100"
                            title="Batal Pakai Gambar Ini">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 text-center">Preview Berubah</p>
                </div>
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
    {{-- PIXABAY SEARCH MODAL --}}
    <div x-data="imageSearch" x-show="modalOpen" x-cloak style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
        @keydown.escape.window="modalOpen = false">

        <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden"
            @click.away="modalOpen = false">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <img src="https://pixabay.com/static/img/logo_square.png" class="w-6 h-6 rounded" alt="Pixabay">
                    Cari Gambar (Pixabay)
                </h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            {{-- Controls --}}
            <div class="px-6 py-4 border-b border-gray-100 space-y-4">
                <div class="flex gap-2">
                    <input type="text" x-model="query" @keydown.enter="search()"
                        class="flex-1 rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Kata kunci (English recommended)...">
                    <button @click="search()"
                        class="px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">
                        Cari
                    </button>
                </div>

                {{-- Filter Type --}}
                <div class="flex items-center gap-4 text-sm">
                    <span class="font-semibold text-gray-600">Tipe Gambar:</span>
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="img_type" value="all" x-model="imageType" @change="search()"
                            class="text-blue-600 focus:ring-blue-500">
                        <span>Semua</span>
                    </label>
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="img_type" value="photo" x-model="imageType" @change="search()"
                            class="text-blue-600 focus:ring-blue-500">
                        <span>Foto</span>
                    </label>
                    <label class="flex items-center gap-1 cursor-pointer">
                        <input type="radio" name="img_type" value="vector" x-model="imageType" @change="search()"
                            class="text-blue-600 focus:ring-blue-500">
                        <span>Vektor/Ilustrasi</span>
                    </label>
                </div>
            </div>

            {{-- Results Grid --}}
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50 custom-scrollbar">

                {{-- Loading --}}
                <div x-show="loading" class="flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-10 w-10 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="text-gray-500 font-medium">Mencari gambar...</p>
                </div>

                {{-- Error/Empty --}}
                <div x-show="!loading && (error || (photos.length === 0 && query))" class="text-center py-12">
                    <p class="text-gray-500" x-text="error || 'Tidak ada hasil.'"></p>
                </div>

                {{-- Empty Start --}}
                <div x-show="!loading && !query" class="text-center py-12">
                    <p class="text-gray-400 italic">Ketik kata kunci untuk mencari gambar.</p>
                </div>

                {{-- Grid --}}
                <div x-show="!loading && photos.length > 0"
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <template x-for="photo in photos" :key="photo.id">
                        <div class="group relative rounded-xl overflow-hidden bg-gray-200 cursor-pointer shadow-sm hover:shadow-lg transition-all aspect-[4/3]"
                            @click="selectImage(photo.largeImageURL)">
                            <img :src="photo.webformatURL"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                            {{-- Overlay info --}}
                            <div
                                class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/80 to-transparent p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <p class="text-white text-xs font-bold truncate" x-text="photo.tags"></p>
                                <p class="text-gray-300 text-[10px]" x-text="'by ' + photo.user"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-between items-center text-xs text-gray-500">
                <span>Powered by <a href="https://pixabay.com" target="_blank"
                        class="text-blue-500 hover:underline">Pixabay</a></span>
                <button @click="modalOpen = false"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 font-bold text-gray-700">
                    Batal
                </button>
            </div>
        </div>
    </div>

    {{-- COMPONENT: REMOTE UPLOAD MODAL --}}
    <div x-data="remoteUploadComponent" @open-remote-upload.window="openModal()" class="z-50 relative">

        <!-- Modal -->
        <div x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Backdrop -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal Content -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    @click.away="closeModal()">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Scan QR untuk Upload
                                </h3>
                                <div class="mt-4 flex flex-col items-center justify-center space-y-4">

                                    <!-- Loading State -->
                                    <div x-show="loading" class="flex flex-col items-center text-gray-500">
                                        <svg class="animate-spin h-8 w-8 text-indigo-500 mb-2"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Generating Token & QR...
                                    </div>

                                    <!-- QR Display -->
                                    <div x-show="!loading && qrCodeSvg" class="p-4 bg-white border rounded">
                                        <div x-html="qrCodeSvg"></div>
                                    </div>

                                    <div x-show="!loading" class="text-sm text-gray-500 text-center">
                                        <p class="mb-2">1. Buka kamera HP Anda / Aplikasi Scanner.</p>
                                        <p class="mb-2">2. Scan QR Code di atas.</p>
                                        <p class="mb-2">3. Upload foto melalui halaman di HP.</p>
                                        <p class="text-xs text-gray-400 mt-2">(Halaman akan otomatis refresh saat foto
                                            diterima)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="closeModal()">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS FOR ALPINE COMPONENTS --}}
    <script>
        document.addEventListener('alpine:init', () => {

            // PIXABAY IMAGE SEARCH COMPONENT
            Alpine.data('imageSearch', () => ({
                modalOpen: false,
                query: '',
                apiKey: '54205676-9a99102b1018f739098165548', // API Key Pixabay
                photos: [],
                loading: false,
                error: null,
                imageType: 'all', // all, photo, vector

                init() {
                    window.addEventListener('open-image-modal', (e) => {
                        this.query = e.detail.query || '';
                        this.modalOpen = true;
                        if (this.query) this.search();
                    });
                },

                async search() {
                    if (!this.query) return;
                    this.loading = true;
                    this.error = null;
                    this.photos = [];

                    try {
                        const safeQuery = encodeURIComponent(this.query);
                        // Docs: https://pixabay.com/api/docs/
                        const url = `https://pixabay.com/api/?key=${this.apiKey}&q=${safeQuery}&image_type=${this.imageType}&per_page=12&safesearch=true`;

                        const req = await fetch(url);
                        const data = await req.json();

                        if (parseInt(data.totalHits) > 0) {
                            this.photos = data.hits;
                        } else {
                            this.error = 'Tidak ada gambar ditemukan.';
                        }
                    } catch (e) {
                        console.error(e);
                        this.error = 'Gagal mengambil data dari Pixabay. Cek koneksi atau API Key.';
                    } finally {
                        this.loading = false;
                    }
                },

                selectImage(url) {
                    // Update field input via DOM reference since it is outside scope
                    const urlInput = document.querySelector('input[name="image_url"]');
                    if (urlInput) {
                        urlInput.value = url;
                        urlInput.dispatchEvent(new Event('input', { bubbles: true })); // Trigger x-model update

                        // Reset file input agar tidak konflik
                        const fileInput = document.querySelector('input[name="image"]');
                        if (fileInput) fileInput.value = '';
                    }
                    this.modalOpen = false;
                }
            }));

            // REMOTE UPLOAD COMPONENT
            Alpine.data('remoteUploadComponent', () => ({
                isOpen: false,
                loading: false,
                qrCodeSvg: '',
                token: null,
                pollInterval: null,
                pollAttempts: 0,
                maxAttempts: 300,

                async openModal() {
                    this.isOpen = true;
                    this.loading = true;
                    this.qrCodeSvg = '';
                    this.token = null;
                    this.pollAttempts = 0;

                    try {
                        // 1. Get Token & QR from Server
                        const response = await fetch("{{ route('remote.token') }}");

                        if (!response.ok) throw new Error('Network response was not ok');

                        const data = await response.json();

                        if (data.token) {
                            this.token = data.token;
                            if (data.qr_code) {
                                this.qrCodeSvg = data.qr_code;
                            }
                            this.loading = false;
                            this.startPolling();
                        } else {
                            throw new Error("Token not found in response");
                        }

                    } catch (error) {
                        console.error('Error initiating upload:', error);
                        alert('Gagal membuat sesi upload. Silakan coba lagi.');
                        this.closeModal();
                    }
                },

                startPolling() {
                    if (!this.token) return;

                    // Clear existing if any
                    if (this.pollInterval) clearInterval(this.pollInterval);

                    this.pollInterval = setInterval(async () => {
                        // Stop if too many attempts
                        if (this.pollAttempts >= this.maxAttempts) {
                            alert('Sesi upload habis. Silakan scan ulang.');
                            this.closeModal();
                            return;
                        }
                        this.pollAttempts++;

                        try {
                            const res = await fetch(`{{ url('/api/remote-check') }}/${this.token}`);
                            if (!res.ok) return;

                            const statusData = await res.json();

                            if (statusData.status === 'found' && statusData.url) {
                                console.log('✅ Upload selesai! URL:', statusData.url);

                                // Success! Close modal
                                this.closeModal();

                                // Dispatch to WINDOW
                                window.dispatchEvent(new CustomEvent('remote-image-selected', {
                                    detail: { url: statusData.url }
                                }));
                            }
                        } catch (e) {
                            console.error("Polling error (ignoring):", e);
                        }
                    }, 2000);
                },

                closeModal() {
                    this.isOpen = false;
                    this.token = null;
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                        this.pollInterval = null;
                    }
                }
            }));
        });
    </script>
</x-app-layout>