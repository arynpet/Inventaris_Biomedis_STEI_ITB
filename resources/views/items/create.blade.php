<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">

        {{-- Header & Title --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Add New Item</h1>
                <p class="text-sm text-gray-500">Create new inventory items easily.</p>
            </div>

            <a href="{{ route('items.index') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium text-sm transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
        </div>

        {{-- FORM CONTAINER (Alpine Data Scope) --}}
        <div x-data="itemForm" class="bg-white shadow-xl border border-gray-100 rounded-3xl overflow-hidden">

            {{-- DEV MODE BUTTON --}}
            @if(auth()->user()->is_dev_mode ?? false)
                <div class="bg-yellow-100 p-2 text-center border-b border-yellow-200">
                    <button type="button" @click="fillDummyData()"
                        class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold py-1 px-4 rounded shadow text-sm flex items-center justify-center gap-2 mx-auto w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        ✨ Developer Mode: Auto Fill Random Data
                    </button>
                </div>
            @endif

            {{-- TOGGLE SWITCH HEADER --}}
            <div class="bg-gray-50 border-b border-gray-200 p-2 flex justify-center">
                <div class="bg-gray-200 p-1 rounded-xl flex shadow-inner relative">
                    {{-- Tombol Single --}}
                    <button @click="mode = 'single'"
                        :class="mode === 'single' ? 'bg-white text-blue-600 shadow' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all duration-300 w-32 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Single
                    </button>

                    {{-- Tombol Batch --}}
                    <button @click="mode = 'batch'"
                        :class="mode === 'batch' ? 'bg-white text-blue-600 shadow' : 'text-gray-500 hover:text-gray-700'"
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all duration-300 w-32 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Batch Input
                    </button>
                </div>
            </div>

            {{-- FORM CONTENT --}}
            <form action="{{ route('items.store') }}" method="POST" class="p-8 space-y-6" enctype="multipart/form-data">
                @csrf

                {{-- Input Mode (Hidden) --}}
                <input type="hidden" name="input_mode" x-model="mode">

                {{-- INFO ALERT UNTUK BATCH MODE --}}
                <div x-show="mode === 'batch'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3 text-blue-800 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <strong>Mode Batch Aktif!</strong><br>
                        Anda dapat memasukkan banyak barang sekaligus. Cukup isi data umum sekali, lalu paste daftar
                        Serial Number.
                    </div>
                </div>

                {{-- 1. IDENTITAS UMUM (General Identity) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Name --}}
                    <div class="col-span-1 md:col-span-2">
                        <label class="block mb-2 font-bold text-gray-700">Nama Barang</label>
                        <input type="text" name="name" x-model="itemName" @input="generateAbbr()"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="Contoh: Laptop Dell Latitude 5420" value="{{ old('name') }}" required>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Merk / Brand <span
                                class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                        <input type="text" name="brand"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="Contoh: Dell, Epson, Logitech" value="{{ old('brand') }}">
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Tipe / Model <span
                                class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                        <input type="text" name="type"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="Contoh: Latitude 5420, L3110" value="{{ old('type') }}">
                    </div>

                    {{-- Asset Number --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Asset Number <span
                                class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                        <input type="text" name="asset_number"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="AST-001" value="{{ old('asset_number') }}">
                        <p x-show="mode === 'batch'" class="text-xs text-orange-500 mt-1">*Diabaikan saat mode Batch
                            untuk mencegah duplikat.</p>
                    </div>

                    {{-- Fiscal Group --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Fiscal Group</label>
                        <input type="text" name="fiscal_group"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            value="{{ old('fiscal_group') }}">
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- 2. IMAGE UPLOAD (HYBRID) - FULLY REACTIVE --}}
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200"
                    x-data="{ imageUrl: '{{ old('image_url') }}' }"
                    @remote-image-selected.window="console.log('✅ Event diterima:', $event.detail); imageUrl = $event.detail.url">

                    <label class="block mb-4 font-bold text-gray-700 text-lg">Foto Barang (Opsional)</label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Opsi A: Upload File --}}
                        <div>
                            <label class="block mb-2 font-bold text-gray-600 text-sm">Upload File (Local)</label>
                            <div class="relative">
                                <input type="file" name="image"
                                    @change="if($el.value) { imageUrl = ''; $refs.urlInput.disabled = true; } else { $refs.urlInput.disabled = false; }"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG. Max: 2MB. Akan di-resize
                                    otomatis.</p>
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

                                {{-- ✅ REACTIVE INPUT dengan x-model --}}
                                <input type="url" name="image_url" x-ref="urlInput" x-model="imageUrl"
                                    class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 text-sm disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed"
                                    placeholder="https://example.com/image.jpg">

                                {{-- SEARCH BUTTON (Pixabay) --}}
                                <button type="button" @click="$dispatch('open-image-modal', { query: itemName })"
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
                            <p class="text-xs text-gray-400 mt-1">Cari gambar via Pixabay atau Scan QR untuk upload dari
                                HP Anda.</p>
                            @error('image_url')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- ✅ IMAGE PREVIEW (Reactive) --}}
                    <div x-show="imageUrl" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="mt-6 relative inline-block">
                        <div class="relative group">
                            <img :src="imageUrl" alt="Preview"
                                class="w-64 h-64 object-cover rounded-xl border-2 border-gray-300 shadow-lg"
                                x-on:error="console.error('Failed to load image:', imageUrl)">

                            {{-- Clear Button --}}
                            <button type="button" @click="imageUrl = ''"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition shadow-lg opacity-0 group-hover:opacity-100"
                                title="Hapus gambar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">Preview Gambar</p>
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- 2. SERIAL NUMBER SECTION (DINAMIS) --}}
                {{-- 2. SMART SERIAL GENERATOR SECTION --}}
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200 transition-all duration-300"
                    :class="autoGen ? 'ring-2 ring-blue-100' : 'opacity-100'">

                    <div class="flex justify-between items-center mb-4">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" class="sr-only" x-model="autoGen">
                                <div class="block bg-gray-300 w-10 h-6 rounded-full"
                                    :class="autoGen ? 'bg-blue-500' : 'bg-gray-300'"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition"
                                    :class="autoGen ? 'transform translate-x-4' : ''"></div>
                            </div>
                            <span class="font-bold text-gray-700">Smart Serial Generator</span>
                        </label>

                        <div x-show="autoGen"
                            class="text-xs font-mono bg-white px-2 py-1 rounded border border-gray-200">
                            Format: <span class="text-blue-600 font-bold" x-text="catCode"></span>-<span
                                class="text-purple-600 font-bold" x-text="abbr"></span>-<span
                                class="text-green-600 font-bold" x-text="year"></span><span
                                class="text-gray-400">00X</span>
                        </div>
                    </div>

                    <div x-show="autoGen" x-transition class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        {{-- Params Generator --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Prefix</label>
                            <select x-model="catCode" @change="fetchSequence()"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center uppercase">
                                <option value="B">B (Biomedis)</option>
                                <option value="I">I (Instrumentasi)</option>
                                <option value="K">K (Komponen)</option>
                                <option value="P">P (Pc)</option>
                                <option value="EK">EK (Elektronika Kantor)</option>
                                <option value="M">M (Mekanikal)</option>
                                <option value="C">C (Cabel)</option>
                                <option value="A">A (Atk)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Abbr</label>
                            <input type="text" x-model="abbr" @input="fetchSequence()"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center uppercase"
                                maxlength="4">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Year</label>
                            <input type="text" x-model="year" @input="fetchSequence()"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Start Seq</label>
                            <input type="text" x-model="startSeq"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center">
                            <div class="flex gap-1 mt-1">
                                <button type="button" @click="fetchSequence()"
                                    class="text-[10px] text-blue-500 underline flex-1 text-center"
                                    title="Cek seq terakhir di database">Check DB</button>
                                <button type="button" @click="previewSerials()"
                                    class="text-[10px] bg-blue-600 text-white px-2 py-0.5 rounded flex-1 text-center hover:bg-blue-700">Apply</button>
                            </div>
                        </div>
                    </div>

                    {{-- Quantity Input controlling Batch --}}
                    <div class="mb-4 pt-4 border-t border-gray-200">
                        <label class="block mb-2 font-bold text-gray-700">Jumlah Input Barang (Quantity)</label>
                        <div class="flex items-center gap-4">
                            <input type="number" x-model="qty"
                                @input="previewSerials(); if(qty > 1) mode = 'batch'; else mode = 'single';" min="1"
                                class="w-32 rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 font-bold text-lg">
                            <input type="hidden" name="quantity" value="1">
                            <span class="text-sm text-gray-500">
                                <span x-show="qty > 1">item akan dibuat dengan serial number berurutan.</span>
                                <span x-show="qty == 1">item (Single Mode).</span>
                            </span>
                        </div>
                    </div>

                    {{-- OUTPUT: Serial Number Fields --}}
                    <div>
                        {{-- Single View --}}
                        <div x-show="mode === 'single'">
                            <label class="block mb-2 font-bold text-gray-700">Serial Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" x-model="generatedSerials[0]"
                                class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 font-mono font-bold"
                                :class="autoGen ? 'bg-blue-50 text-blue-900 border-blue-300' : ''"
                                placeholder="Masukkan Serial Number Unik" :required="mode === 'single'"
                                :disabled="mode !== 'single'">
                            @error('serial_number')
                                <p class="text-red-500 text-sm mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Batch View --}}
                        <div x-show="mode === 'batch'">
                            <label class="block mb-2 font-bold text-gray-700">Result: Data Preview</label>
                            <textarea name="serial_numbers_batch" rows="6"
                                class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 font-mono text-sm"
                                :class="autoGen ? 'bg-blue-50 text-blue-900 border-blue-300' : ''"
                                :value="generatedSerials.join('\n')" @input="generatedSerials = $el.value.split('\n')"
                                placeholder="SN-001&#10;SN-002&#10;SN-003&#10;..." :required="mode === 'batch'"
                                :disabled="mode !== 'batch'"></textarea>
                            <p class="text-xs text-gray-500 mt-2">Daftar ini dibuat otomatis. Jika ingin edit manual,
                                matikan "Smart Serial Generator" atau edit langsung di box.</p>
                            @error('serial_numbers_batch')
                                <p class="text-red-500 text-sm mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                <hr class="border-gray-100">

                {{-- 3. DETAIL LOKASI & STATUS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Room --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Ruangan <span
                                class="text-red-500">*</span></label>
                        <select name="room_id" required
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Categories --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Kategori</label>
                        <select name="categories[]" multiple @change="updateCatCode($event)"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 h-[120px] text-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (collect(old('categories'))->contains($category->id)) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Tahan CTRL/CMD untuk pilih banyak.</p>
                    </div>

                    {{-- Source --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Sumber Perolehan</label>
                        <input type="text" name="source"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="Misal: Hibah Dikti / Pembelian" value="{{ old('source') }}">
                    </div>

                    {{-- Dates --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Tahun Perolehan</label>
                        <input type="number" name="acquisition_year"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            placeholder="2026" value="{{ old('acquisition_year', '2026') }}">
                    </div>

                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Tanggal Digunakan</label>
                        <input type="date" name="placed_in_service_at"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3"
                            value="{{ old('placed_in_service_at', date('Y-m-d')) }}">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Status Awal</label>
                        <select name="status"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available
                            </option>
                            <option value="borrowed" {{ old('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance
                            </option>
                        </select>
                    </div>

                    {{-- Condition --}}
                    <div>
                        <label class="block mb-2 font-bold text-gray-700">Kondisi Fisik</label>
                        <select name="condition"
                            class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3">
                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik (Good)</option>
                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak Ringan
                            </option>
                            <option value="broken" {{ old('condition') == 'broken' ? 'selected' : '' }}>Rusak Berat
                            </option>
                        </select>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                    <a href="{{ route('items.index') }}"
                        class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition transform hover:-translate-y-0.5">
                        <span x-text="mode === 'batch' ? 'Simpan Semua Data' : 'Simpan Data'"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('itemForm', () => ({
                mode: 'single',
                autoGen: false,
                itemName: '',
                catCode: 'B',
                abbr: 'XXXX',
                year: '{{ date('y') }}',
                startSeq: '001',
                qty: 1,
                generatedSerials: [''],

                init() {
                    this.$watch('autoGen', value => {
                        if (value) {
                            this.previewSerials();
                            if (this.itemName) this.generateAbbr();
                            else this.fetchSequence();
                        }
                    });
                },

                generateAbbr() {
                    if (!this.itemName) return;
                    let clean = this.itemName.toUpperCase().replace(/[^A-Z]/g, '');
                    this.abbr = clean.substring(0, 4).padEnd(4, 'X');
                    this.fetchSequence();
                },

                updateCatCode(e) {
                    // Get latest selected option
                    let options = e.target.options;
                    let selectedOption = null;

                    // Iterate to find the last selected one or just use selectedIndex
                    if (e.target.selectedIndex !== -1) {
                        let text = options[e.target.selectedIndex].text.toUpperCase();

                        // Smart Mapping
                        if (text.includes('ELEKTRONIKA')) {
                            this.catCode = 'EK';
                        } else {
                            this.catCode = text.charAt(0);
                        }
                        this.fetchSequence();
                    }
                },

                fetchSequence() {
                    if (!this.autoGen) return;
                    fetch(`{{ route('items.next_sequence') }}?prefix=${this.catCode}&abbr=${this.abbr}&year=${this.year}`)
                        .then(res => res.json())
                        .then(data => {
                            this.startSeq = data.sequence;
                            this.previewSerials();
                        });
                },

                previewSerials() {
                    if (!this.autoGen) return;
                    let list = [];
                    let current = parseInt(this.startSeq);
                    for (let i = 0; i < this.qty; i++) {
                        let seqStr = (current + i).toString().padStart(3, '0');
                        list.push(`${this.catCode}-${this.abbr}-${this.year}${seqStr}`);
                    }
                    this.generatedSerials = list;
                },

                fillDummyData() {
                    const brands = ['Olympus', 'Nikon', 'Canon', 'Thermo Scientific', 'Leica', 'Fluke', 'Tektronix'];
                    const types = ['X-500', 'Turbo 3000', 'Vision Pro', 'LabMaster V2', 'Spectra', 'Quantus'];
                    const names = ['Mikroskop', 'Centrifuge', 'Oscilloscope', 'Multimeter', 'Spectrophotometer', 'Incubator', 'Pipette', 'Autoclave'];
                    const conditions = ['good', 'good', 'good', 'damaged']; // Lebih banyak good
                    const sources = ['Pembelian 2024', 'Hibah Dikti', 'Donasi Alumni', 'Pengadaan Mandiri'];

                    // Random Name Generation
                    const randomName = names[Math.floor(Math.random() * names.length)];
                    const randomType = types[Math.floor(Math.random() * types.length)];

                    this.itemName = randomName + ' ' + randomType;
                    this.mode = 'single'; // Always single for now
                    this.qty = 1; // Always 1
                    this.autoGen = true;

                    this.generateAbbr();

                    document.querySelector('[name="brand"]').value = brands[Math.floor(Math.random() * brands.length)];
                    document.querySelector('[name="type"]').value = randomType;
                    document.querySelector('[name="asset_number"]').value = 'DEV-' + Math.floor(Math.random() * 10000);
                    document.querySelector('[name="fiscal_group"]').value = 'Elektronik Lab';
                    document.querySelector('[name="source"]').value = sources[Math.floor(Math.random() * sources.length)];
                    document.querySelector('[name="acquisition_year"]').value = Math.floor(Math.random() * (2025 - 2020 + 1)) + 2020;
                    document.querySelector('[name="condition"]').value = conditions[Math.floor(Math.random() * conditions.length)];

                    const roomSelect = document.querySelector('[name="room_id"]');
                    if (roomSelect.options.length > 1) {
                        roomSelect.selectedIndex = Math.floor(Math.random() * (roomSelect.options.length - 1)) + 1;
                    }
                }
            }));

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
                    // Update field input di x-data itemForm scope atau via DOM
                    // Karena input url ada di luar scope ini, kita pakai DOM reference
                    const urlInput = document.querySelector('input[name="image_url"]');
                    if (urlInput) {
                        urlInput.value = url;
                        // Reset file input agar tidak konflik
                        const fileInput = document.querySelector('input[name="image"]');
                        if (fileInput) fileInput.value = '';
                    }
                    this.modalOpen = false;
                }
            }));
        });
    </script>

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
    {{-- COMPONENT: REMOTE UPLOAD MODAL (Inlined for Debugging) --}}
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('remoteUploadComponent', () => ({
                isOpen: false,
                loading: false,
                qrCodeSvg: '',
                token: null,
                pollInterval: null,
                pollAttempts: 0,
                maxAttempts: 300, // 300 * 2s = 600s (10 minutes)

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

                                // ⚠️ PENTING: Dispatch ke WINDOW, bukan Alpine $dispatch
                                // Karena listener di input field pakai @remote-image-selected.window
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