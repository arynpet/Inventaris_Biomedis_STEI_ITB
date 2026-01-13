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
        <div x-data="{ 
            mode: 'single', 
            autoGen: false, 
            itemName: '',
            catCode: 'E',
            abbr: 'XXXX',
            year: '{{ date('y') }}',
            startSeq: '001',
            qty: 1,
            generatedSerials: [], 
            
            generateAbbr() {
                if(!this.itemName) return;
                let clean = this.itemName.toUpperCase().replace(/[^A-Z]/g, '');
                // Ambil huruf pertama tiap kata jika ada spasi, atau ambil konsonan
                // Fallback sederhana: 4 Karakter pertama non-symbol
                this.abbr = clean.substring(0, 4).padEnd(4, 'X');
                this.fetchSequence();
            },

            updateCatCode(e) {
                let text = e.target.options[e.target.selectedIndex].text;
                this.catCode = text.charAt(0).toUpperCase(); // Ambil huruf depan (E, M, F)
                this.fetchSequence();
            },

            fetchSequence() {
                if(!this.autoGen) return;
                fetch(`{{ route('items.next_sequence') }}?prefix=${this.catCode}&abbr=${this.abbr}&year=${this.year}`)
                    .then(res => res.json())
                    .then(data => {
                        this.startSeq = data.sequence;
                        this.previewSerials();
                    });
            },

            previewSerials() {
                if(!this.autoGen) return;
                let list = [];
                let current = parseInt(this.startSeq);
                for(let i=0; i < this.qty; i++) {
                    let seqStr = (current + i).toString().padStart(3, '0');
                    list.push(`${this.catCode}-${this.abbr}-${this.year}${seqStr}`);
                }
                this.generatedSerials = list;
            }
        }" class="bg-white shadow-xl border border-gray-100 rounded-3xl overflow-hidden">

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
            <form action="{{ route('items.store') }}" method="POST" class="p-8 space-y-6">
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
                            <input type="text" x-model="catCode" @input="fetchSequence()"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center uppercase">
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
                            <input type="text" x-model="startSeq" @input="previewSerials()"
                                class="w-full rounded-lg border-gray-300 text-sm font-bold text-center">
                            <button type="button" @click="fetchSequence()"
                                class="text-[10px] text-blue-500 underline text-center w-full mt-1">Check DB</button>
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
                            <input type="text" name="serial_number" :value="generatedSerials[0] || ''"
                                @input="generatedSerials[0] = $el.value"
                                class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 px-4 py-3 font-mono font-bold"
                                :class="autoGen ? 'bg-blue-50 text-blue-900 border-blue-300' : ''"
                                placeholder="Masukkan Serial Number Unik" :required="mode === 'single'"
                                :disabled="mode !== 'single'">
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
</x-app-layout>