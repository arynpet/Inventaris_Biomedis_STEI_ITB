<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8" x-data="qrBorrowing()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">Form Peminjaman Baru</h1>
                    <p class="mt-1 text-sm text-gray-500">Isi formulir di bawah untuk mencatat transaksi peminjaman
                        barang.</p>
                </div>
                <a href="{{ route('borrowings.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">

                {{-- Progress/Status Bar (Optional Visual Touch) --}}
                <div class="h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>

                <form action="{{ route('borrowings.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- LEFT COLUMN --}}
                        <div class="space-y-6">

                            {{-- 1. PILIH PEMINJAM --}}
                            <div class="relative">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-bold text-gray-700">
                                        Nama Peminjam <span class="text-red-500">*</span>
                                    </label>
                                    <a href="{{ route('peminjam-users.create') }}" target="_blank" class="text-xs bg-green-100 text-green-700 py-1 px-2 rounded hover:bg-green-200 transition flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        + Peminjam Baru
                                    </a>
                                </div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                    <select name="user_id"
                                        class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm bg-gray-50 hover:bg-white transition-colors cursor-pointer"
                                        required>
                                        <option value="">-- Pilih Peminjam --</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->nim_nip ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Pastikan peminjam sudah terdaftar di Data
                                    Peminjam.</p>
                            </div>

                            {{-- 2. TANGGAL PINJAM --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Waktu Peminjaman <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="datetime-local" name="borrow_date"
                                        class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm"
                                        value="{{ old('borrow_date', now()->format('Y-m-d\TH:i')) }}" required>
                                </div>
                                @error('borrow_date')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- 3. TANGGAL KEMBALI --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Estimasi Kembali <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                                </label>
                                <input type="datetime-local" name="return_date"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm"
                                    value="{{ old('return_date') }}">
                                <p class="mt-1 text-xs text-gray-400">Kosongkan jika belum tahu kapan kembali.</p>
                                @error('return_date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="space-y-6">

                            {{-- 4. ITEM SELECTION (SEARCHABLE + QR) --}}
                            <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 relative">
                                <label class="block text-sm font-bold text-gray-800 mb-3">
                                    Pilih Barang / Alat <span class="text-red-500">*</span>
                                </label>

                                {{-- Hidden Input untuk Form Submission --}}
                                <input type="hidden" name="item_id" x-model="selectedItemId">

                                {{-- Search Input & QR Button --}}
                                <div class="flex gap-2 relative">
                                    <div class="relative flex-1">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" x-model="searchQuery" @input="openDropdown = true"
                                            @click="openDropdown = true" @click.away="openDropdown = false"
                                            placeholder="Ketik nama alat..."
                                            class="block w-full pl-10 pr-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm"
                                            autocomplete="off">

                                        {{-- Dropdown Results --}}
                                        <div x-show="openDropdown && filteredItems.length > 0"
                                            x-transition.opacity.duration.200ms
                                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto">
                                            <ul>
                                                <template x-for="item in filteredItems" :key="item.id">
                                                    <li @click="selectItem(item)"
                                                        class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors flex justify-between items-center group">
                                                        <span x-text="item.name"
                                                            class="font-medium text-gray-700 group-hover:text-blue-700"></span>
                                                        <span
                                                            class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full group-hover:bg-blue-100 group-hover:text-blue-600"
                                                            x-text="'ID: ' + item.id"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>

                                        {{-- No Results --}}
                                        <div x-show="openDropdown && filteredItems.length === 0"
                                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg p-4 text-center text-gray-500 text-sm">
                                            Tidak ada barang ditemukan.
                                        </div>
                                    </div>

                                    <button type="button" @click="toggleScanner"
                                        class="shrink-0 bg-gray-800 text-white p-3 rounded-xl hover:bg-gray-700 active:bg-gray-900 transition shadow-lg flex items-center justify-center tooltip"
                                        title="Scan QR Code">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Selected Item Display --}}
                                <div x-show="selectedItemId" x-transition
                                    class="mt-3 p-3 bg-white rounded-lg border border-blue-200 flex items-start gap-3 shadow-sm">
                                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Barang
                                            terpilih:</p>
                                        <p class="font-bold text-gray-800" x-text="selectedItemName"></p>
                                    </div>
                                    <button type="button" @click="resetSelection"
                                        class="ml-auto text-gray-400 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Status Message --}}
                                <div class="mt-2 text-sm font-medium transition-colors duration-300"
                                    :class="statusColor" x-text="statusText" x-show="statusText !== ''">
                                </div>

                                {{-- Scanner View --}}
                                <div x-show="showScanner" x-transition
                                    class="mt-4 border-2 border-dashed border-gray-300 rounded-xl p-2 bg-black/5 relative overflow-hidden">
                                    <div id="qr-reader" class="w-full rounded-lg overflow-hidden"></div>
                                    <button type="button" @click="stopScanner"
                                        class="absolute top-4 right-4 bg-red-600 text-white rounded-full p-1 shadow-lg hover:bg-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                            </div>

                            {{-- 5. CATATAN --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Catatan / Keperluan <span
                                        class="text-xs font-normal text-gray-500">(Opsional)</span>
                                </label>
                                <textarea name="notes"
                                    class="block w-full px-4 py-3 border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm h-32 resize-none"
                                    placeholder="Contoh: Digunakan untuk praktikum Fisika Dasar..."></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-4">
                        <a href="{{ route('borrowings.index') }}"
                            class="px-6 py-3 bg-white text-gray-700 rounded-xl font-bold text-sm border border-gray-200 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                            Batal
                        </a>
                        <button type="submit" :disabled="!selectedItemId || !canSubmit"
                            :class="{'opacity-50 cursor-not-allowed': !selectedItemId || !canSubmit}"
                            class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 active:bg-blue-800 transition shadow-lg hover:shadow-blue-500/30 flex items-center">
                            <span x-show="!selectedItemId">Pilih Barang Dulu</span>
                            <span x-show="selectedItemId">Simpan Peminjaman</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        function qrBorrowing() {
            return {
                // Data for Searchable Dropdown
                items: @json($items),
                searchQuery: '',
                selectedItemId: '',
                selectedItemName: '',
                openDropdown: false,

                // Scanner Data
                showScanner: false,
                scanner: null,
                scanned: false,
                statusText: '',
                statusColor: 'text-gray-500',
                canSubmit: true, // Default true for manual select

                get filteredItems() {
                    if (this.searchQuery === '') {
                        return this.items.slice(0, 10); // Show max 10 initially
                    }
                    return this.items.filter(item => {
                        return item.name.toLowerCase().includes(this.searchQuery.toLowerCase())
                    }).slice(0, 50); // Limit results for performance
                },

                selectItem(item) {
                    this.selectedItemId = item.id;
                    this.selectedItemName = item.name;
                    this.searchQuery = item.name;
                    this.openDropdown = false;
                    this.statusText = '';
                    this.canSubmit = true;
                },

                resetSelection() {
                    this.selectedItemId = '';
                    this.selectedItemName = '';
                    this.searchQuery = '';
                    this.statusText = '';
                },

                // --- SCANNER LOGIC ---
                toggleScanner() {
                    if (this.showScanner) {
                        this.stopScanner()
                    } else {
                        this.startScanner()
                    }
                },

                async startScanner() {
                    if (this.scanner) return

                    this.showScanner = true
                    this.scanned = false
                    // temporarily disable submit while scanning
                    this.statusText = 'Arahkan kamera ke QR Code barang...'
                    this.statusColor = 'text-blue-600'

                    this.scanner = new Html5Qrcode("qr-reader")

                    await this.scanner.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: 250 },
                        qr => this.onScanSuccess(qr),
                        () => { }
                    )
                },

                async stopScanner() {
                    if (!this.scanner) return

                    try {
                        await this.scanner.stop()
                        await this.scanner.clear()
                    } catch (e) { }

                    this.scanner = null
                    this.showScanner = false
                },

                async onScanSuccess(qr) {
                    if (this.scanned) return
                    this.scanned = true

                    this.statusText = 'QR terdeteksi! Mengecek database...'
                    this.statusColor = 'text-yellow-600 animate-pulse'

                    try {
                        const res = await fetch("{{ route('borrowings.scan') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ qr })
                        })

                        const data = await res.json()

                        if (!data.success) {
                            this.statusText = `Error: ${data.message}`
                            this.statusColor = 'text-red-500 font-bold'

                            // Play error sound/vibrate if possible
                            if (navigator.vibrate) navigator.vibrate(200);

                            setTimeout(() => {
                                this.scanned = false; // Allow rescanning after 2s
                                this.statusText = 'Silakan scan QR lain...';
                                this.statusColor = 'text-blue-600';
                            }, 2000)
                            return
                        }

                        // SUCCESS
                        if (navigator.vibrate) navigator.vibrate(50);

                        this.selectedItemId = data.item.id;
                        this.selectedItemName = data.item.name;
                        this.searchQuery = data.item.name;
                        this.openDropdown = false;

                        this.statusText = `✅ Sukses! Barang ditemukan: ${data.item.name}`
                        this.statusColor = 'text-green-600 font-bold'
                        this.canSubmit = true

                        await this.stopScanner()

                    } catch (err) {
                        console.error(err);
                        this.statusText = 'Gagal menghubungi server.'
                        this.statusColor = 'text-red-600'
                        this.scanned = false
                    }
                }
            }
        }
    </script>
</x-app-layout>