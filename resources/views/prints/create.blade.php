<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Print 3D') }}
        </h2>
    </x-slot>

    @php
        $minDate = \Carbon\Carbon::today()->format('Y-m-d');
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                
                {{-- CARD HEADER --}}
                <div class="bg-blue-600 px-8 py-5 flex justify-between items-center">
                    <div>
                        <h3 class="text-white font-bold text-xl">Formulir Cetak 3D</h3>
                        <p class="text-blue-100 text-sm mt-1">Pastikan file STL/OBJ sudah siap dan material mencukupi.</p>
                    </div>
                    <svg class="w-10 h-10 text-blue-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>

                <div class="p-8">
                    {{-- ERROR SUMMARY (Optional/Backup) --}}
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                            <p class="font-bold">Mohon perbaiki kesalahan berikut:</p>
                            <ul class="list-disc ml-5 mt-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('prints.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- SECTION 1: INFO UMUM --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                Informasi Project
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- User --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Nama Peminjam</label>
                                    <select name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-gray-50">
                                        <option value="">-- Pilih User (Trained Only) --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Project Name --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Nama File / Judul Project</label>
                                    <input type="text" name="project_name" value="{{ old('project_name') }}" 
                                           class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                           placeholder="Contoh: Prototype Roda V1">
                                    @error('project_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Printer Selection --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Pilih Mesin Printer</label>
                                    <select name="printer_id" id="printer_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                        <option value="" data-category="">-- Pilih Mesin --</option>
                                        @foreach ($printers as $printer)
                                            <option value="{{ $printer->id }}" 
                                                    data-category="{{ $printer->category }}" 
                                                    {{ old('printer_id') == $printer->id ? 'selected' : '' }}>
                                                {{ $printer->name }} (Tipe: {{ strtoupper($printer->category) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">*Material akan disesuaikan dengan tipe mesin (FDM = Filament, SLA = Resin).</p>
                                    @error('printer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: WAKTU --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Jadwal Pengerjaan
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Tanggal --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                                    <input type="date" name="date" value="{{ old('date', $minDate) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Start Time --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time') }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- End Time --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Jam Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time') }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3: MATERIAL --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                Kebutuhan Material
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Material Type --}}
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Jenis Material</label>
                                    <select name="material_type_id" id="material_type_id" disabled
                                            class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm disabled:bg-gray-100 disabled:text-gray-400">
                                        <option value="" data-category="" data-unit="">-- Pilih Mesin Terlebih Dahulu --</option>
                                        @foreach($materials as $m)
                                            <option value="{{ $m->id }}" 
                                                    data-category="{{ $m->category }}" 
                                                    data-unit="{{ $m->unit }}"
                                                    {{ old('material_type_id') == $m->id ? 'selected' : '' }}>
                                                {{ $m->name }} (Sisa: {{ $m->stock_balance }} {{ $m->unit }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('material_type_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Amount & Unit --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Estimasi Jumlah</label>
                                        <input type="number" step="0.1" name="material_amount" value="{{ old('material_amount') }}"
                                               class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="0.0">
                                        @error('material_amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Satuan</label>
                                        <input type="text" id="material_unit_display" name="material_unit" readonly 
                                               class="w-full border-gray-300 bg-gray-100 rounded-xl text-gray-600 shadow-sm" 
                                               value="{{ old('material_unit', '-') }}">
                                    </div>
                                </div>

                                {{-- Source --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Sumber Material</label>
                                    <select name="material_source" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                        <option value="lab" {{ old('material_source') == 'lab' ? 'selected' : '' }}>Lab (Potong Stok)</option>
                                        <option value="pribadi" {{ old('material_source') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                        <option value="penelitian" {{ old('material_source') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                                        <option value="dosen" {{ old('material_source') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    </select>
                                    @error('material_source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Lecturer Name (Conditional) --}}
                                <div id="lecturer_field" class="hidden">
                                    <label class="block text-gray-700 font-bold mb-2">Nama Dosen</label>
                                    <input type="text" name="lecturer_name" value="{{ old('lecturer_name') }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                           placeholder="Masukkan Nama Dosen">
                                    @error('lecturer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4: FILE & NOTES --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                                File & Catatan
                            </h4>

                            {{-- File Upload PDF / IMAGE (Hybrid) --}}
                            <div class="mb-6" x-data="{ fileUrl: '' }" @remote-image-selected.window="fileUrl = $event.detail.url">
                                <label class="block text-gray-700 font-bold mb-2">Upload File Pengajuan (Pdf / Foto)</label>
                                
                                {{-- HIDDEN URL INPUT --}}
                                <input type="hidden" name="file_upload_url" x-model="fileUrl">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Local Upload --}}
                                    <div>
                                        <div class="flex items-center justify-center w-full">
                                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                                                   :class="fileUrl ? 'opacity-50 pointer-events-none' : ''">
                                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                    <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                    <p class="text-xs text-gray-500 text-center"><span class="font-semibold">Klik Upload PDF/Foto</span></p>
                                                    <p class="text-[10px] text-gray-400 mt-1">Max 10MB</p>
                                                </div>
                                                <input type="file" name="file_upload" class="hidden" accept=".pdf,.jpg,.jpeg,.png" 
                                                       @change="if($el.files[0]) { document.getElementById('filename-display').innerText = $el.files[0].name; fileUrl = ''; }" />
                                            </label>
                                        </div>
                                    </div>

                                    {{-- Remote / QR Upload --}}
                                    <div>
                                        <div class="h-32 border-2 border-blue-200 border-dashed rounded-xl bg-blue-50 flex flex-col items-center justify-center text-blue-700 cursor-pointer hover:bg-blue-100 transition relative"
                                             @click="$dispatch('open-remote-upload')">
                                            
                                            {{-- If URL exists, show preview/success state --}}
                                            <div x-show="fileUrl" class="absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center z-10 p-2">
                                                <span class="text-green-600 font-bold text-sm mb-1">✅ File Terupload!</span>
                                                <p class="text-[10px] text-gray-400 break-all px-2 text-center" x-text="fileUrl.split('/').pop()"></p>
                                                <button type="button" @click.stop="fileUrl = ''" class="mt-2 text-xs text-red-500 hover:underline">Hapus / Ganti</button>
                                            </div>

                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                            <span class="font-bold text-sm">Scan QR dari HP</span>
                                            <span class="text-[10px] opacity-70">Ambil foto dokumen langsung</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <p id="filename-display" class="text-sm text-blue-600 mt-2 font-medium"></p>
                                @error('file_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- File Upload STL --}}
                            {{-- File Upload STL --}}
                            <div class="mb-6">
                                <label class="block text-gray-700 font-bold mb-2">Upload File 3D (STL/OBJ/ZIP)</label>
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg mb-3">
                                    <p class="text-xs text-yellow-800 font-bold mb-1">⚠️ Aturan Penamaan File:</p>
                                    <p class="text-xs text-yellow-700">Format: <code class="bg-yellow-100 px-1 rounded">TIPE-NAMAPEMILIK-NAMAFILE.(stl/zip)</code></p>
                                    <p class="text-xs text-yellow-700 mt-1">
                                        Contoh: <br>
                                        • Filament: <span class="font-mono font-semibold">FLN-SATYA-TENGKORAK.stl</span><br>
                                        • Resin: <span class="font-mono font-semibold">RSN-SATYA-GIGI.zip</span>
                                    </p>
                                </div>
                                <input type="file" name="stl_file" accept=".stl,.obj,.zip" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100 placeholder-gray-400 border border-gray-300 rounded-lg">
                                @error('stl_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Catatan Tambahan</label>
                                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Instruksi khusus...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('prints.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg hover:shadow-blue-500/30">
                                Simpan Request
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT UNTUK FILTER MATERIAL & UNIT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printerSelect = document.getElementById('printer_id');
            const materialSelect = document.getElementById('material_type_id');
            const unitInput = document.getElementById('material_unit_display');
            
            // Simpan semua opsi material di awal (sebelum difilter)
            const allMaterialOptions = Array.from(materialSelect.options);

            // Nilai lama (untuk handle validation error)
            const oldMaterialId = "{{ old('material_type_id') }}";

            function updateMaterials() {
                const selectedPrinter = printerSelect.options[printerSelect.selectedIndex];
                const category = selectedPrinter.getAttribute('data-category');

                // Kosongkan select
                materialSelect.innerHTML = '';
                
                // Filter opsi yang sesuai kategori mesin
                const filteredOptions = allMaterialOptions.filter(option => {
                    const materialCat = option.getAttribute('data-category');
                    return materialCat === category || option.value === '';
                });

                // Masukkan opsi yang sudah difilter
                filteredOptions.forEach(option => materialSelect.appendChild(option));
                
                // Enable/Disable select
                if (category) {
                    materialSelect.disabled = false;
                    materialSelect.classList.remove('bg-gray-100');
                } else {
                    materialSelect.disabled = true;
                    materialSelect.classList.add('bg-gray-100');
                }

                // Coba pilih kembali nilai lama (jika ada dan valid)
                if (oldMaterialId) {
                    // Cek apakah oldId ada di opsi yang baru difilter
                    const exists = filteredOptions.some(opt => opt.value === oldMaterialId);
                    if (exists) {
                        materialSelect.value = oldMaterialId;
                    }
                }

                updateUnit();
            }

            function updateUnit() {
                if (materialSelect.selectedIndex >= 0) {
                    const selectedMaterial = materialSelect.options[materialSelect.selectedIndex];
                    const unit = selectedMaterial.getAttribute('data-unit');
                    unitInput.value = unit ? unit : '-';
                } else {
                    unitInput.value = '-';
                }
            }

            // Logic for Lecturer Field
            const sourceSelect = document.querySelector('select[name="material_source"]');
            const lecturerField = document.getElementById('lecturer_field');

            function toggleLecturerField() {
                if (sourceSelect.value === 'dosen') {
                    lecturerField.classList.remove('hidden');
                } else {
                    lecturerField.classList.add('hidden');
                }
            }

            // Event Listeners
            printerSelect.addEventListener('change', updateMaterials);
            materialSelect.addEventListener('change', updateUnit);
            sourceSelect.addEventListener('change', toggleLecturerField);

            // Initial Check
            if (sourceSelect.value) {
                toggleLecturerField();
            }

            // Jalankan saat load (untuk handle old input saat validasi gagal)
            if (printerSelect.value) {
                updateMaterials();
            }
        });
    </script>
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
                                        <svg class="animate-spin h-8 w-8 text-indigo-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
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
                                        <p class="text-xs text-gray-400 mt-2">(Halaman akan otomatis refresh saat foto diterima)</p>
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

    {{-- Script Alpine untuk Remote Upload (Reused) --}}
    <script>
        document.addEventListener('alpine:init', () => {
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
                        const response = await fetch("{{ route('remote.token') }}");
                        if (!response.ok) throw new Error('Network response was not ok');
                        const data = await response.json();

                        if (data.token) {
                            this.token = data.token;
                            if (data.qr_code) this.qrCodeSvg = data.qr_code;
                            this.loading = false;
                            this.startPolling();
                        } else {
                            throw new Error("Token not found");
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Gagal membuat sesi upload.');
                        this.closeModal();
                    }
                },

                startPolling() {
                    if (!this.token) return;
                    if (this.pollInterval) clearInterval(this.pollInterval);

                    this.pollInterval = setInterval(async () => {
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
                                this.closeModal();
                                window.dispatchEvent(new CustomEvent('remote-image-selected', {
                                    detail: { url: statusData.url }
                                }));
                            }
                        } catch (e) { console.error(e); }
                    }, 2000);
                },

                closeModal() {
                    this.isOpen = false;
                    this.token = null;
                    if (this.pollInterval) clearInterval(this.pollInterval);
                }
            }));
        });
    </script>
</x-app-layout>