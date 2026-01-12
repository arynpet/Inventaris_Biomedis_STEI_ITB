<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panduan Sistem (SOP)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Introduction --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Standard Operating Procedure (SOP)</h1>
                    <p class="text-gray-600">Panduan penggunaan Sistem Inventaris Barang Laboratorium Biomedis Teknik
                        Biomedis STEI ITB. Ikuti langkah-langkah di bawah ini untuk mengelola aset laboratorium.</p>
                </div>
            </div>

            <div class="space-y-12">

                {{-- A. Persiapan & Login --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">A</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Persiapan & Login</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2 mb-4">
                                <li>Pastikan Anda terhubung ke jaringan internet/intranet kampus.</li>
                                <li>Buka browser (Chrome/Edge/Firefox) dan akses URL aplikasi.</li>
                                <li>Masukkan <b>Email</b> dan <b>Password</b> akun Admin atau Superadmin Anda.</li>
                            </ul>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2 uppercase font-bold tracking-wider">Contoh Tombol
                                    Login:</p>
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-default select-none">
                                    LOG IN
                                </button>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Halaman Login</span>
                                <!-- Ganti div ini dengan <img src="{{ asset('img/sop/login.png') }}" class="rounded-lg shadow-sm"> -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- B. Menambahkan Barang Baru --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">B</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Menambahkan Barang Baru (Aset Masuk)</h3>
                            <p class="text-gray-600 mb-4">Gunakan fitur ini saat ada pembelian alat baru atau pencatatan
                                aset lama yang belum terdaftar.</p>
                            <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                <li>Masuk ke menu <b class="text-blue-600">Inventory > Data Barang</b> di sidebar kiri.
                                </li>
                                <li>Klik tombol <b>Tambah Barang</b> di pojok kanan atas tabel.</li>
                                <li>Isi form dengan lengkap:
                                    <ul class="list-disc list-inside ml-5 text-sm mt-1">
                                        <li><b>Nama Barang</b>, <b>Merk</b>, <b>Tipe</b>.</li>
                                        <li><b>Tanggal Perolehan</b> (Penting untuk penyusutan).</li>
                                        <li><b>Jumlah</b>: Jika lebih dari 1, sistem akan generate Asset ID unik
                                            berurutan.</li>
                                    </ul>
                                </li>
                                <li>Klik <b>Simpan</b>.</li>
                            </ol>
                            <div class="flex gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-widest cursor-default select-none shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Barang
                                </button>
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-default select-none">
                                    Simpan
                                </button>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Form Tambah Barang</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- C. Cetak Label & QR Code --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">C</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Cetak Label & QR Code</h3>
                            <p class="text-gray-600 mb-4">Setiap barang memiliki label unik untuk inventarisasi dan scan
                                cepat.</p>
                            <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                <li>Di tabel <b>Data Barang</b>, cari barang yang ingin dicetak.</li>
                                <li>Klik tombol <b>Detail</b> (ikon mata <svg class="w-4 h-4 inline text-blue-500"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>).</li>
                                <li>Di halaman detail, klik tombol <b>Cetak QR</b>. Label akan otomatis terunduh atau
                                    terbuka di tab baru siap print.</li>
                                <li>Tempel label fisik pada aset di tempat yang mudah terlihat namun aman.</li>
                            </ol>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="flex items-center gap-2">
                                    <button
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 cursor-default">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                    <span class="text-gray-400 mx-2">-></span>
                                    <button
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-default">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6h6m-6 0H6"></path>
                                        </svg>
                                        CETAK QR
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Tombol Cetak QR</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- D. Meminjamkan Barang (Sirkulasi) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">D</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Meminjamkan Barang (Sirkulasi)</h3>
                            <p class="text-gray-600 mb-4">Catat setiap barang yang dibawa keluar oleh mahasiswa/dosen.
                            </p>
                            <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                <li>Masuk ke menu <b class="text-blue-600">Layanan > Pinjam Alat</b>.</li>
                                <li>Klik <b>Tambah Peminjaman</b>.</li>
                                <li>Pilih Nama Peminjam (User harus terdaftar dulu).</li>
                                <li>Scan QR Code barang atau cari nama barang di kolom pencarian.</li>
                                <li>Tentukan tanggal estimasi kembali.</li>
                                <li>Klik <b>Simpan</b>. Status barang akan berubah menjadi "Dipinjam".</li>
                            </ol>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <button type="button"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-widest cursor-default select-none shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Peminjaman
                                </button>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Form Peminjaman</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- E. Pengembalian Barang --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">E</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Pengembalian Barang</h3>
                            <p class="text-gray-600 mb-4">Verifikasi barang saat dikembalikan.</p>
                            <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                <li>Di tabel <b>Pinjam Alat</b>, cari transaksi yang statusnya "Sedang Dipinjam".</li>
                                <li>Klik tombol aksi berwarna <b>Hijau (Selesai/Kembali)</b> <svg
                                        class="w-4 h-4 inline text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>.</li>
                                <li>Cek kondisi barang fisik. Jika ada kerusakan, catat di kolom "Kondisi Akhir".</li>
                                <li>Klik Konfirmasi. Status barang akan kembali menjadi "Available".</li>
                            </ol>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <button
                                    class="inline-flex items-center justify-center p-2 bg-green-100 rounded-md text-green-600 hover:bg-green-200 focus:outline-none cursor-default">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <span class="text-sm font-semibold text-gray-700 ml-2">Tombol Selesaikan</span>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Modal Pengembalian</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- F. Barang Keluar / Penghapusan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                    <div class="absolute top-0 left-0 bg-blue-600 text-white px-3 py-1 rounded-br-lg font-bold">F</div>
                    <div class="mt-4 md:flex gap-8">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Barang Keluar / Penghapusan (Disposal)</h3>
                            <p class="text-gray-600 mb-4">Untuk barang rusak, hilang, atau habis pakai yang perlu
                                dihapus dari stok aktif.</p>
                            <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                <li><b>Jangan Hapus Langsung!</b> Gunakan fitur "Barang Keluar" agar tercatat di Log.
                                </li>
                                <li>Di tabel Data Barang, klik tombol <b>Sampah/Keluar</b> (Ikon Trash Merah).</li>
                                <li>Isi Berita Acara (Alasan penghapusan, misal: "Rusak Berat", "Hibah", "Hilang").</li>
                                <li>Klik Konfirmasi. Barang akan hilang dari daftar aktif tapi tersimpan di <b>Log
                                        Logistik (Riwayat Barang Keluar)</b>.</li>
                            </ol>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <button
                                    class="inline-flex items-center justify-center p-2 bg-red-100 rounded-md text-red-600 hover:bg-red-200 focus:outline-none cursor-default">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                                <span class="text-sm font-semibold text-gray-700 ml-2">Tombol Disposal</span>
                            </div>
                        </div>
                        <div class="md:w-1/2 mt-4 md:mt-0">
                            <!-- SCREENSHOT PLACEHOLDER -->
                            <div
                                class="w-full h-48 bg-gray-200 border-2 border-dashed border-gray-400 rounded-lg flex items-center justify-center text-gray-500 flex-col gap-2">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium">Screenshot Modal Disposal</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>