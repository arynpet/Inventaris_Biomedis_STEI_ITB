<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Request Print 3D') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                
                {{-- CARD HEADER --}}
                <div class="bg-indigo-600 px-8 py-5 flex justify-between items-center">
                    <div>
                        <h3 class="text-white font-bold text-xl">Edit Data Print</h3>
                        <p class="text-indigo-100 text-sm mt-1">Ubah detail request atau update status.</p>
                    </div>
                </div>

                <div class="p-8">
                    {{-- ERROR SUMMARY --}}
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

                    <form action="{{ route('prints.update', $print->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- SECTION 1: INFO UMUM --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                                Informasi Project
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- User --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Nama Peminjam</label>
                                    <select name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ old('user_id', $print->user_id) == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Project Name --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Nama File / Judul Project</label>
                                    <input type="text" name="project_name" value="{{ old('project_name', $print->project_name) }}" 
                                           class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    @error('project_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Printer Selection --}}
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Pilih Mesin Printer</label>
                                    <select name="printer_id" id="printer_id" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                        <option value="" data-category="">-- Pilih Mesin --</option>
                                        @foreach ($printers as $printer)
                                            <option value="{{ $printer->id }}" 
                                                    data-category="{{ $printer->category }}" 
                                                    {{ old('printer_id', $print->printer_id) == $printer->id ? 'selected' : '' }}>
                                                {{ $printer->name }} (Tipe: {{ strtoupper($printer->category) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('printer_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: WAKTU --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                                Jadwal Pengerjaan
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {{-- Tanggal --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Tanggal</label>
                                    <input type="date" name="date" value="{{ old('date', $print->date) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- Start Time --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Jam Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($print->start_time)->format('H:i')) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                {{-- End Time --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Jam Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($print->end_time)->format('H:i')) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3: MATERIAL --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                                Kebutuhan Material
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Material Type --}}
                                <div class="md:col-span-2">
                                    <label class="block text-gray-700 font-bold mb-2">Jenis Material</label>
                                    <select name="material_type_id" id="material_type_id"
                                            class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-gray-50">
                                        <option value="" data-category="" data-unit="">-- Pilih Mesin Terlebih Dahulu --</option>
                                        @foreach($materials as $m)
                                            <option value="{{ $m->id }}" 
                                                    data-category="{{ $m->category }}" 
                                                    data-unit="{{ $m->unit }}"
                                                    {{ old('material_type_id', $print->material_type_id) == $m->id ? 'selected' : '' }}>
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
                                        <input type="number" step="0.1" name="material_amount" value="{{ old('material_amount', $print->material_amount) }}"
                                               class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Satuan</label>
                                        <input type="text" id="material_unit_display" name="material_unit" readonly 
                                               class="w-full border-gray-300 bg-gray-100 rounded-xl text-gray-600 shadow-sm" 
                                               value="{{ old('material_unit', $print->material_unit) }}">
                                    </div>
                                </div>

                                {{-- Source --}}
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Sumber Material</label>
                                    <select name="material_source" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                        <option value="lab" {{ old('material_source', $print->material_source) == 'lab' ? 'selected' : '' }}>Lab (Potong Stok)</option>
                                        <option value="pribadi" {{ old('material_source', $print->material_source) == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                        <option value="penelitian" {{ old('material_source', $print->material_source) == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                                        <option value="dosen" {{ old('material_source', $print->material_source) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    </select>
                                </div>

                                {{-- Lecturer Name --}}
                                <div id="lecturer_field" class="{{ old('material_source', $print->material_source) == 'dosen' ? '' : 'hidden' }}">
                                    <label class="block text-gray-700 font-bold mb-2">Nama Dosen</label>
                                    <input type="text" name="lecturer_name" value="{{ old('lecturer_name', $print->lecturer_name) }}"
                                           class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4: FILE & NOTES --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold mb-4 border-b pb-2 flex items-center gap-2">
                                <span class="bg-indigo-100 text-indigo-600 w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                                File & Catatan
                            </h4>

                            {{-- File Upload PDF / IMAGE --}}
                            <div class="mb-6">
                                <label class="block text-gray-700 font-bold mb-2">Upload File Pengajuan Baru (Opsional)</label>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                     {{-- Status File Saat Ini --}}
                                     <div class="p-3 bg-gray-50 border rounded-xl">
                                        <p class="text-xs text-gray-500 font-bold mb-1">File Saat Ini:</p>
                                        @if($print->file_path)
                                            <a href="{{ route('prints.file', $print->id) }}" class="text-blue-600 hover:underline text-sm flex items-center gap-1" target="_blank">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                {{ $print->file_name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm">Tidak ada file.</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Input File Baru (Hanya Local untuk Edit biar simple) --}}
                                    <div>
                                        <input type="file" name="file_upload" class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 placeholder-gray-400 border border-gray-300 rounded-lg">
                                        <p class="text-[10px] text-gray-400 mt-1">Upload untuk mengganti file lama (Max 10MB)</p>
                                    </div>
                                </div>
                                <div x-data="remoteUploadComponent" @open-remote-upload.window="openModal()" class="mt-2 text-right">
                                     <button type="button" @click="$dispatch('open-remote-upload')" class="text-xs text-indigo-500 hover:text-indigo-700 underline">
                                         + Scan dari HP (Ganti File)
                                     </button>
                                     <input type="hidden" name="file_upload_url" x-model="fileUrl">
                                     {{-- Copy the Modal and Logic from create.blade.php if we want remote upload here too, skipping for brevity but keeping button as placeholder warning if not implemented --}}
                                </div>
                            </div>

                            {{-- File Upload STL --}}
                            <div class="mb-6">
                                <label class="block text-gray-700 font-bold mb-2">Upload File 3D (STL/OBJ/ZIP)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-3 bg-gray-50 border rounded-xl">
                                         <p class="text-xs text-gray-500 font-bold mb-1">File STL Saat Ini:</p>
                                        @if($print->stl_path)
                                            <span class="text-green-600 text-sm break-all">{{ basename($print->stl_path) }}</span>
                                            <span class="text-xs text-gray-400 block">(File aman di server)</span>
                                        @else
                                            <span class="text-gray-400 text-sm">Tidak ada file 3D.</span>
                                        @endif
                                    </div>
                                    <div>
                                         <input type="file" name="stl_file" accept=".stl,.obj,.zip" class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 placeholder-gray-400 border border-gray-300 rounded-lg">
                                          <p class="text-[10px] text-gray-400 mt-1">Upload untuk mengganti model 3D (Max 50MB)</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Catatan Tambahan</label>
                                <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('notes', $print->notes) }}</textarea>
                            </div>
                        </div>

                         {{-- SECTION 5: STATUS --}}
                         <div class="mb-8 bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                             <h4 class="text-yellow-800 font-bold mb-4 flex items-center gap-2">
                                 <span class="bg-yellow-200 text-yellow-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">!</span>
                                 Update Status
                             </h4>
                             <div>
                                <label class="font-semibold text-gray-700">Status Pengerjaan</label>
                                <select name="status" class="w-full border border-yellow-300 rounded-lg p-2 mt-1 focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="pending"   {{ $print->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                    <option value="printing"  {{ $print->status == 'printing' ? 'selected' : '' }}>Printing (Sedang Dicetak)</option>
                                    <option value="done"      {{ $print->status == 'done' ? 'selected' : '' }}>Done (Selesai/Diambil)</option>
                                    <option value="canceled"  {{ $print->status == 'canceled' ? 'selected' : '' }}>Canceled (Dibatalkan)</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Mengubah status ke 'Printing' (jika dari Pending) akan memotong stok material secara otomatis.</p>
                            </div>
                         </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <a href="{{ route('prints.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-bold shadow-lg hover:shadow-indigo-500/30">
                                Update Data
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JS SAMA SEPERTI CREATE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printerSelect = document.getElementById('printer_id');
            const materialSelect = document.getElementById('material_type_id');
            const unitInput = document.getElementById('material_unit_display');
            
            const allMaterialOptions = Array.from(materialSelect.options);
            const oldMaterialId = "{{ old('material_type_id', $print->material_type_id) }}";

            function updateMaterials() {
                const selectedPrinter = printerSelect.options[printerSelect.selectedIndex];
                const category = selectedPrinter.getAttribute('data-category');

                materialSelect.innerHTML = '';
                
                const filteredOptions = allMaterialOptions.filter(option => {
                    const materialCat = option.getAttribute('data-category');
                    return materialCat === category || option.value === '';
                });

                filteredOptions.forEach(option => materialSelect.appendChild(option));
                
                if (category) {
                    materialSelect.disabled = false;
                } else {
                    materialSelect.disabled = true;
                }

                if (oldMaterialId) {
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

            const sourceSelect = document.querySelector('select[name="material_source"]');
            const lecturerField = document.getElementById('lecturer_field');

            function toggleLecturerField() {
                if (sourceSelect.value === 'dosen') {
                    lecturerField.classList.remove('hidden');
                } else {
                    lecturerField.classList.add('hidden');
                }
            }

            printerSelect.addEventListener('change', updateMaterials);
            materialSelect.addEventListener('change', updateUnit);
            sourceSelect.addEventListener('change', toggleLecturerField);

            if (printerSelect.value) {
                updateMaterials();
            }
        });
    </script>
    
    {{-- Include Alpine Component for Remote Upload if needed (skipping full modal code for brevity in Edit, simplified to manual upload emphasis) --}}
    <script>
         document.addEventListener('alpine:init', () => {
            Alpine.data('remoteUploadComponent', () => ({
                fileUrl: '',
                openModal() {
                    alert('Fitur Scan QR belum aktif di halaman Edit. Silakan upload file manual atau buat request baru.');
                }
            }));
        });
    </script>
</x-app-layout>
