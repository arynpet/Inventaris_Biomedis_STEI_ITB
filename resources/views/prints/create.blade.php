<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request Print 3D') }}
        </h2>
    </x-slot>

    @php
        $minDate = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d');
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
                                    <input type="date" name="date" min="{{ $minDate }}" value="{{ old('date', $minDate) }}"
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
                            </div>
                        </div>

                        {{-- SECTION 4: FILE & NOTES --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                                File & Catatan
                            </h4>

                            {{-- File Upload --}}
                            <div class="mb-6">
                                <label class="block text-gray-700 font-bold mb-2">Upload File Pengajuan (Pdf)</label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                            <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                            <p class="text-xs text-gray-500">Max 1MB</p>
                                        </div>
                                        <input id="dropzone-file" type="file" name="file_upload" class="hidden" onchange="document.getElementById('filename-display').innerText = this.files[0].name" />
                                    </label>
                                </div>
                                <p id="filename-display" class="text-sm text-blue-600 mt-2 font-medium"></p>
                                @error('file_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

            // Event Listeners
            printerSelect.addEventListener('change', updateMaterials);
            materialSelect.addEventListener('change', updateUnit);

            // Jalankan saat load (untuk handle old input saat validasi gagal)
            if (printerSelect.value) {
                updateMaterials();
            }
        });
    </script>
</x-app-layout>