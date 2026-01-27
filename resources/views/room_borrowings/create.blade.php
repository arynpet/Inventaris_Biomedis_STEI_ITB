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
                                <select name="user_id"
                                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
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
                                <select name="room_id"
                                    class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
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
                            <input type="text" name="purpose" value="{{ old('purpose') }}"
                                placeholder="Contoh: Rapat Koordinasi, Seminar..."
                                class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        </div>

                        {{-- UPLOAD SURAT (Hybrid) --}}
                        <div class="mb-6" x-data="{ fileUrl: '' }"
                            @remote-image-selected.window="fileUrl = $event.detail.url">
                            <label class="block text-gray-700 font-bold mb-2">Upload Surat Peminjaman (PDF /
                                Foto)</label>

                            {{-- HIDDEN URL INPUT --}}
                            <input type="hidden" name="surat_peminjaman_url" x-model="fileUrl">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Local Upload --}}
                                <div>
                                    <div class="flex items-center justify-center w-full">
                                        <label
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                                            :class="fileUrl ? 'opacity-50 pointer-events-none' : ''">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                                <p class="text-xs text-gray-500 text-center"><span
                                                        class="font-semibold">Klik Upload PDF/Foto</span></p>
                                                <p class="text-[10px] text-gray-400 mt-1">Max 2MB</p>
                                            </div>
                                            <input type="file" name="surat_peminjaman" class="hidden"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                @change="if($el.files[0]) { document.getElementById('filename-display').innerText = $el.files[0].name; fileUrl = ''; }" />
                                        </label>
                                    </div>
                                </div>

                                {{-- Remote / QR Upload --}}
                                <div>
                                    <div class="h-32 border-2 border-blue-200 border-dashed rounded-xl bg-blue-50 flex flex-col items-center justify-center text-blue-700 cursor-pointer hover:bg-blue-100 transition relative"
                                        @click="$dispatch('open-remote-upload')">

                                        {{-- If URL exists, show preview/success state --}}
                                        <div x-show="fileUrl"
                                            class="absolute inset-0 bg-white rounded-xl flex flex-col items-center justify-center z-10 p-2">
                                            <span class="text-green-600 font-bold text-sm mb-1">✅ File Terupload!</span>
                                            <p class="text-[10px] text-gray-400 break-all px-2 text-center"
                                                x-text="fileUrl.split('/').pop()"></p>
                                            <button type="button" @click.stop="fileUrl = ''"
                                                class="mt-2 text-xs text-red-500 hover:underline">Hapus / Ganti</button>
                                        </div>

                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z">
                                            </path>
                                        </svg>
                                        <span class="font-bold text-sm">Scan QR dari HP</span>
                                        <span class="text-[10px] opacity-70">Ambil foto dokumen langsung</span>
                                    </div>
                                </div>
                            </div>

                            <p id="filename-display" class="text-sm text-blue-600 mt-2 font-medium"></p>
                            @error('surat_peminjaman') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex items-center justify-end gap-3 border-t pt-6">
                            <a href="{{ route('room_borrowings.index') }}"
                                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold shadow-lg hover:shadow-blue-500/30">
                                Ajukan Peminjaman
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
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
                                <h3 class="text-lg leading-6 text-gray-900 font-bold" id="modal-title">
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