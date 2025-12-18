<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto" x-data="qrBorrowing()">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">New Borrowing</h1>

        <form action="{{ route('borrowings.store') }}" method="POST"
              class="bg-white shadow-sm border border-gray-100 p-6 rounded-2xl space-y-5">
            @csrf

            {{-- Borrower --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Borrower</label>
                <select name="user_id"
                        class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">-- Select Borrower --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ITEM + QR --}}
            <div>
                <label class="block mb-2 font-semibold text-gray-700">Item</label>

                <div class="flex gap-2 mb-2">
                    <select name="item_id"
                            x-ref="itemSelect"
                            class="flex-1 rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">-- Select Item --</option>
                        @foreach($items as $i)
                            <option value="{{ $i->id }}">{{ $i->name }}</option>
                        @endforeach
                    </select>

                    {{-- Toggle Camera --}}
                    <button type="button"
                            @click="toggleScanner"
                            class="px-4 py-2 rounded-xl text-white text-sm font-semibold
                                   bg-gradient-to-r from-indigo-500 to-purple-600
                                   hover:from-indigo-600 hover:to-purple-700">
                        📷 Scan QR
                    </button>
                </div>

                {{-- Status --}}
                <p class="text-sm" :class="statusColor" x-text="statusText"></p>
            </div>

            {{-- CAMERA --}}
            <div x-show="showScanner"
                 x-transition
                 class="border rounded-xl p-3 bg-gray-50">
                <div id="qr-reader" class="w-full"></div>

                <button type="button"
                        @click="stopScanner"
                        class="mt-3 w-full px-4 py-2 rounded-xl bg-gray-200 hover:bg-gray-300 text-sm font-semibold">
                    Tutup Kamera
                </button>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block mb-1 font-semibold text-gray-700">Notes</label>
                <textarea name="notes"
                          class="w-full rounded-xl border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500 h-24"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('borrowings.index') }}"
                   class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                    Cancel
                </a>

                <button class="px-4 py-2 bg-blue-600 rounded-xl text-white hover:bg-blue-700 transition">
                    Save
                </button>
            </div>
        </form>
    </div>

    {{-- QR SCRIPT --}}
    <script src="https://unpkg.com/html5-qrcode"></script>

<script>
function qrBorrowing() {
    return {
        showScanner: false,
        scanner: null,
        scanned: false,
        statusText: '',
        statusColor: 'text-gray-500',
        canSubmit: false,

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
            this.canSubmit = false

            this.statusText = 'Scanning QR Code...'
            this.statusColor = 'text-blue-600'

            this.scanner = new Html5Qrcode("qr-reader")

            await this.scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                qr => this.onScanSuccess(qr),
                () => {} // ❌ jangan tampilkan error per frame
            )
        },

        async stopScanner() {
            if (!this.scanner) return

            try {
                await this.scanner.stop()
                await this.scanner.clear()
            } catch (e) {}

            this.scanner = null
            this.showScanner = false
        },

        async onScanSuccess(qr) {
            if (this.scanned) return
            this.scanned = true

            this.statusText = 'QR terdeteksi, memproses...'
            this.statusColor = 'text-yellow-600'

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
                    this.statusText = data.message
                    this.statusColor = 'text-red-600'
                    this.scanned = false
                    return
                }

                // SET ITEM
                this.$refs.itemSelect.value = data.item.id
                this.$refs.itemSelect.dispatchEvent(new Event('change'))

                this.statusText = `Item siap dipinjam: ${data.item.name}`
                this.statusColor = 'text-green-600'
                this.canSubmit = true

                await this.stopScanner()

            } catch (err) {
                this.statusText = 'Gagal memproses QR'
                this.statusColor = 'text-red-600'
                this.scanned = false
            }
        }
    }
}
</script>


</x-app-layout>
