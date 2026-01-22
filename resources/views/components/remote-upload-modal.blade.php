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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('remoteUploadComponent', () => ({
            isOpen: false,
            loading: false,
            qrCodeSvg: '',
            token: null,
            pollInterval: null,
            pollAttempts: 0,
            maxAttempts: 300, // 300 * 2s = 600s (10 minutes)

            async openModal() {
                this.isOpen = true;
                this.loading = true;
                this.qrCodeSvg = '';
                this.token = null;
                this.pollAttempts = 0;

                try {
                    // 1. Get Token & QR from Server
                    const response = await fetch("{{ route('remote.token') }}");

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    if (data.token) {
                        this.token = data.token;
                        if (data.qr_code) {
                            this.qrCodeSvg = data.qr_code;
                        }
                        this.loading = false;
                        this.startPolling();
                    } else {
                        throw new Error("Token not found in response");
                    }

                } catch (error) {
                    console.error('Error initiating upload:', error);
                    alert('Gagal membuat sesi upload. Silakan coba lagi.');
                    this.closeModal();
                }
            },

            startPolling() {
                if (!this.token) return;

                // Clear existing if any
                if (this.pollInterval) clearInterval(this.pollInterval);

                this.pollInterval = setInterval(async () => {
                    // Stop if too many attempts
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
                            console.log('✅ Upload selesai! URL:', statusData.url);

                            // Success! Close modal
                            this.closeModal();

                            // ⚠️ PENTING: Dispatch ke WINDOW, bukan Alpine $dispatch
                            // Karena listener di input field pakai @remote-image-selected.window
                            window.dispatchEvent(new CustomEvent('remote-image-selected', {
                                detail: { url: statusData.url }
                            }));
                        }
                    } catch (e) {
                        console.error("Polling error (ignoring):", e);
                    }
                }, 2000);
            },

            closeModal() {
                this.isOpen = false;
                this.token = null;
                if (this.pollInterval) {
                    clearInterval(this.pollInterval);
                    this.pollInterval = null;
                }
            }
        }));
    });
</script>