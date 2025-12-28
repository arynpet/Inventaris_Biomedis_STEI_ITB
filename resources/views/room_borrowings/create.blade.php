<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajukan Peminjaman Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-white font-bold text-lg">Formulir Peminjaman</h3>
                    <p class="text-blue-100 text-sm">Isi data dengan lengkap dan upload surat peminjaman.</p>
                </div>

                <div class="p-8">
                    {{-- PENTING: enctype="multipart/form-data" WAJIB ADA untuk upload file --}}
                    <form action="{{ route('room_borrowings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- USER (Hidden/Readonly jika user login, atau Select jika admin) --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-gray-700 font-bold mb-2">Nama Peminjam</label>
                                <select name="user_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    <option value="" disabled selected>-- Pilih Peminjam --</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                            {{ $u->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- RUANGAN --}}
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-gray-700 font-bold mb-2">Pilih Ruangan</label>
                                <select name="room_id" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                    <option value="" disabled selected>-- Pilih Ruangan --</option>
                                    @foreach ($rooms as $r)
                                        <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                            {{ $r->name }} ({{ $r->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- WAKTU MULAI --}}
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Waktu Mulai</label>
                                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}"
                                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                @error('start_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- WAKTU SELESAI --}}
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Waktu Selesai</label>
                                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"
                                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                @error('end_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- KEPERLUAN --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Keperluan / Acara</label>
                            <input type="text" name="purpose" value="{{ old('purpose') }}" placeholder="Contoh: Rapat Koordinasi, Seminar..."
                                class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        {{-- UPLOAD SURAT (BARU) --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Upload Surat Peminjaman (PDF)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-gray-500">PDF only (Max. 2MB)</p>
                                    </div>
                                    <input id="dropzone-file" type="file" name="surat_peminjaman" accept=".pdf" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name" />
                                </label>
                            </div>
                            <p id="file-name" class="text-sm text-blue-600 mt-2 font-medium"></p>
                            @error('surat_peminjaman') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end gap-3 border-t pt-6">
                            <a href="{{ route('room_borrowings.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg hover:shadow-blue-500/30">
                                Ajukan Peminjaman
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>