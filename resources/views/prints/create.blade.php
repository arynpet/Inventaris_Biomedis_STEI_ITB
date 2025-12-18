<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Print 3D
        </h2>
    </x-slot>

    @php
        // pastikan menggunakan Carbon yang sudah terinstall
        $minDate = \Carbon\Carbon::now()->addDays(2)->format('Y-m-d');
        // optional: default date to minDate
        $defaultDate = old('date', $minDate);
    @endphp

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                {{-- Show validation errors --}}
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

                    <div class="form-group">
    <label for="printer_id">Pilih Mesin Printer</label>
    <select name="printer_id" id="printer_id" class="form-control">
        <option value="">-- Pilih Mesin --</option>
        @foreach ($printers as $printer)
            <option value="{{ $printer->id }}"
                {{ old('printer_id', $print->printer_id ?? '') == $printer->id ? 'selected' : '' }}>
                {{ $printer->name }} ({{ $printer->category }})
            </option>
        @endforeach
    </select>
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
    <select name="material_type_id" class="w-full border-gray-300 rounded mt-1">
        <option value="">-- Pilih Material --</option>
        @foreach($materials as $m)
            <option value="{{ $m->id }}"
                {{ old('material_type_id') == $m->id ? 'selected' : '' }}>
                {{ $m->category }} - {{ $m->name }}
                (Stock: {{ $m->stock_balance }} {{ $m->unit }})
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block font-medium text-sm text-gray-700">
            Jumlah Material Digunakan
        </label>
        <input type="number" step="0.1" name="material_amount"
               class="w-full border-gray-300 rounded mt-1 p-2"
               placeholder="Contoh: 20"
               value="{{ old('material_amount') }}">
    </div>

    <div>
        <label class="block font-medium text-sm text-gray-700">Unit</label>
        <select name="material_unit" class="w-full border-gray-300 rounded mt-1">
            <option value="">-</option>
            <option value="gram">Gram</option>
            <option value="ml">ml</option>
        </select>
    </div>
</div>

<p class="text-xs text-gray-500">
    ⚠ Material akan langsung dipotong dari stock saat disimpan
</p>


                    {{-- SOURCE --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700">Sumber Material</label>
                        <select name="material_source" class="w-full border-gray-300 rounded mt-1">
                            <option value="">-</option>
                            <option value="lab" {{ old('material_source')=='lab' ? 'selected' : '' }}>Lab</option>
                            <option value="penelitian" {{ old('material_source')=='penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="dosen" {{ old('material_source')=='dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="pribadi" {{ old('material_source')=='pribadi' ? 'selected' : '' }}>Pribadi</option>
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

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</x-app-layout>
