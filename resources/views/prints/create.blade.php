<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Print 3D
        </h2>
    </x-slot>

    @php
        $minDate = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d');
        $defaultDate = old('date', $minDate);
    @endphp

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('prints.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    {{-- USER --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Peminjam (Harus Sudah Training)</label>
                        <select name="user_id" class="w-full border-gray-300 rounded mt-1">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- PRINTER --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Pilih Mesin Printer</label>
                        <select name="printer_id" id="printer_id" class="w-full border-gray-300 rounded mt-1">
                            <option value="" data-category="">-- Pilih Mesin --</option>
                            @foreach ($printers as $printer)
                                <option value="{{ $printer->id }}" 
                                        data-category="{{ $printer->category }}" 
                                        {{ old('printer_id') == $printer->id ? 'selected' : '' }}>
                                    {{ $printer->name }} ({{ $printer->category }})
                                </option>
                            @endforeach
                        </select>
                        @error('printer_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- DATE --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Tanggal</label>
                        <input type="date"
                               name="date"
                               class="w-full border-gray-300 rounded mt-1 p-2"
                               min="{{ $minDate }}"
                               value="{{ $defaultDate }}">
                        <p class="text-xs text-gray-500 mt-1">Tanggal minimal: {{ \Carbon\Carbon::parse($minDate)->translatedFormat('d M Y') }}</p>
                        @error('date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- TIME --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Start Time</label>
                            <input type="time" name="start_time" class="w-full border-gray-300 rounded mt-1 p-2"
                                   value="{{ old('start_time') }}">
                            @error('start_time') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">End Time</label>
                            <input type="time" name="end_time" class="w-full border-gray-300 rounded mt-1 p-2"
                                   value="{{ old('end_time') }}">
                            @error('end_time') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- MATERIAL --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Jenis Material</label>
                        <select name="material_type_id" id="material_type_id" class="w-full border-gray-300 rounded mt-1" disabled>
                            <option value="" data-category="" data-unit="">-- Pilih Material --</option>
                            @foreach($materials as $m)
                                <option value="{{ $m->id }}" 
                                        data-category="{{ $m->category }}" 
                                        data-unit="{{ $m->unit }}"
                                        {{ old('material_type_id') == $m->id ? 'selected' : '' }}>
                                    {{ $m->name }} (Stock: {{ $m->stock_balance }})
                                </option>
                            @endforeach
                        </select>
                        @error('material_type_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- UNIT & AMOUNT --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Jumlah</label>
                            <input type="number" step="0.1" name="material_amount" class="w-full border-gray-300 rounded mt-1 p-2" value="{{ old('material_amount') }}">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Unit</label>
                            <input type="text" id="material_unit_display" name="material_unit" readonly class="w-full border-gray-300 bg-gray-100 rounded mt-1 p-2" value="{{ old('material_unit', '-') }}">
                        </div>
                    </div>

                    {{-- SOURCE --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Sumber Material</label>
                        <select name="material_source" class="w-full border-gray-300 rounded mt-1">
                            <option value="">-</option>
                            @foreach(['lab', 'penelitian', 'dosen', 'pribadi'] as $source)
                                <option value="{{ $source }}" {{ old('material_source') == $source ? 'selected' : '' }}>
                                    {{ ucfirst($source) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- NOTES --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Catatan</label>
                        <textarea name="notes" class="w-full border-gray-300 rounded mt-1 p-2">{{ old('notes') }}</textarea>
                    </div>

                    {{-- FILE --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Upload File (PDF/JPG/PNG)</label>
                        <input type="file" name="file_upload" class="w-full border-gray-300 rounded mt-1 p-2">
                        @error('file_upload') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end items-center space-x-4">
                        <p class="text-xs text-gray-500">
                            ⚠ Material akan langsung dipotong dari stock saat disimpan
                        </p>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printerSelect = document.getElementById('printer_id');
            const materialSelect = document.getElementById('material_type_id');
            const unitInput = document.getElementById('material_unit_display');
            
            // Mengonversi HTMLOptionsCollection menjadi Array untuk persistensi data selama siklus hidup DOM
            const allMaterialOptions = Array.from(materialSelect.options);

            function updateMaterials() {
                const selectedPrinter = printerSelect.options[printerSelect.selectedIndex];
                const category = selectedPrinter.getAttribute('data-category');

                // Membersihkan child nodes dari elemen select material
                materialSelect.innerHTML = '';
                
                // Predikat filtrasi berdasarkan atribut data-category
                const filteredOptions = allMaterialOptions.filter(option => {
                    const materialCat = option.getAttribute('data-category');
                    return materialCat === category || option.value === '';
                });

                // Rekonstruksi DocumentFragment ke dalam elemen select
                filteredOptions.forEach(option => materialSelect.appendChild(option));
                
                // Sinkronisasi status disabled berdasarkan ketersediaan kategori
                materialSelect.disabled = !category;
                updateUnit();
            }

            function updateUnit() {
                const selectedMaterial = materialSelect.options[materialSelect.selectedIndex];
                const unit = selectedMaterial.getAttribute('data-unit');
                unitInput.value = unit ? unit : '-';
            }

            // Registrasi event listener untuk mendeteksi perubahan state pada elemen input
            printerSelect.addEventListener('change', updateMaterials);
            materialSelect.addEventListener('change', updateUnit);

            // Eksekusi inisial untuk menangani state setelah post-back atau validation errors
            if (printerSelect.value) updateMaterials();
        });
    </script>
</x-app-layout>