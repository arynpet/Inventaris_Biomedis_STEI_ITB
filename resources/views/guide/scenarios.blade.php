<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Panduan Praktis (Studi Kasus)') }}
            </h2>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Header Section --}}
            <div class="text-center mb-12">
                <h1 class="text-3xl font-extrabold text-blue-900 sm:text-4xl">
                    Pusat Bantuan
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Temukan solusi cepat untuk aktivitas sehari-hari di Laboratorium.
                </p>
                <div class="mt-6">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        Topik Populer
                    </span>
                </div>
            </div>

            <div class="space-y-6">

                {{-- Skenario 1 --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-indigo-100 p-2.5 rounded-lg text-indigo-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Bagaimana cara memasukkan data barang
                                        baru?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Jika Anda hanya ingin input 1 atau 2 barang
                                        secara manual.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-indigo-50/30 border-t border-gray-100">
                        <div class="prose prose-indigo max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Tenang, prosesnya sangat sederhana. Ikuti langkah berikut:</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk ke menu <span class="font-bold text-indigo-700">Inventory</span> (Item).</li>
                                <li>Klik tombol <span class="font-bold text-indigo-700">+ Tambah Barang</span> di pojok
                                    kanan atas.</li>
                                <li>Isi data-data wajib (Nama Barang, Kategori, Kondisi, dll).</li>
                                <li>Klik <span class="font-bold text-indigo-700">Simpan</span>.</li>
                            </ol>
                            <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 text-sm text-blue-700 rounded-r">
                                <strong>Tips:</strong> Pastikan Anda memilih kategori yang tepat agar pencarian di
                                kemudian hari lebih mudah.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Skenario 2 --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-green-100 p-2.5 rounded-lg text-green-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Saya punya 100 barang, bagaimana cara
                                        input cepat?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Menggunakan fitur Batch Import via Excel.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-gray-50 border-t border-gray-100">
                        <div class="prose prose-indigo max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Jangan khawatir, Anda tidak perlu input satu per satu. Gunakan Excel:</p>
                            <ol class="list-decimal pl-5 space-y-3">
                                <li>Masuk ke menu <strong>Import</strong> (biasanya ada di submenu Inventory).</li>
                                <li><strong>Unduh Template Excel</strong> yang sudah disediakan sistem.</li>
                                <li>Isi data barang Anda ke dalam file Excel tersebut.</li>
                                <li>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 my-2">
                                        <div class="flex">
                                            <div class="shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                                    Penting: Auto-Generate Serial
                                                </h3>
                                                <div class="mt-2 text-sm leading-5 text-yellow-700">
                                                    <p>
                                                        Kolom <strong>Serial Number BOLEH DIKOSONGKAN</strong>. Jika
                                                        kosong, sistem akan otomatis membuatkan kode unik untuk barang
                                                        tersebut. Ini sangat menghemat waktu!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>Upload file Excel yang sudah diisi kembali ke sistem.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 3 --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-purple-100 p-2.5 rounded-lg text-purple-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Ada mahasiswa yang ingin meminjam alat,
                                        apa yang harus saya lakukan?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mencatat peminjaman keluar agar stok terlacak.
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-purple-50/30 border-t border-gray-100">
                        <div class="prose prose-purple max-w-none text-gray-600 ml-14">
                            <p class="mb-2">Pastikan mahasiswa tersebut sudah terdaftar di sistem ya. Langkahnya:</p>

                            <div
                                class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4 text-sm text-yellow-800 rounded-r shadow-sm">
                                <strong class="font-bold">Info:</strong> Jika nama mahasiswa belum muncul, silakan input
                                data mereka terlebih dahulu di menu
                                <b>Inventory > Data Peminjam</b>.
                            </div>

                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk menu <span class="font-bold">Peminjaman</span>.</li>
                                <li>Klik tombol <strong>+ Peminjaman Baru</strong>.</li>
                                <li><strong>Scan QR Barang</strong> yang akan dipinjam (atau bisa cari manual di kolom
                                    pencarian).</li>
                                <li>Pilih <strong>Nama Peminjam</strong> dari database.</li>
                                <li>Tentukan <strong>Tanggal Kembali</strong> (Estimasi).</li>
                                <li>Klik tombol <span
                                        class="bg-purple-600 text-white px-2 py-0.5 rounded text-xs font-bold">Pinjamkan</span>.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 4 --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-orange-100 p-2.5 rounded-lg text-orange-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Barang sudah dikembalikan, bagaimana
                                        cara update statusnya?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mengembalikan stok barang ke Lab.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-orange-50/30 border-t border-gray-100">
                        <div class="prose prose-orange max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Bagus! Segera update statusnya agar stok tidak selisih.</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Buka menu <span class="font-bold">Peminjaman Aktif</span> (atau langsung Scan QR
                                    lewat HP).</li>
                                <li>Cari transaksinya, lalu klik tombol <span
                                        class="font-bold text-green-600">Kembalikan / Return</span>.</li>
                                <li>Lakukan <strong>Cek Fisik</strong> sebentar (pastikan barang tidak rusak).</li>
                                <li>Konfirmasi simpan. Barang statusnya akan kembali menjadi "Available".</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 5: Mutasi Barang --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-100 p-2.5 rounded-lg text-blue-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Alat dipindahkan ke Laboratorium lain,
                                        bagaimana update lokasinya?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mutasi barang antar ruangan.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-blue-50/30 border-t border-gray-100">
                        <div class="prose prose-blue max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Jangan sampai data lokasi di sistem basi! Gunakan fitur pindah ruangan:</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk ke menu <span class="font-bold">Inventory > Data Barang</span>.</li>
                                <li>Cari barang yang dipindahkan.</li>
                                <li>Klik tombol <span class="font-bold text-blue-600">Edit (Pensil)</span>.</li>
                                <li>Ubah kolom <strong>Lokasi / Ruangan</strong> ke ruangan baru.</li>
                                <li>Klik Simpan.</li>
                            </ol>
                            <div class="mt-2 text-xs text-gray-500 italic">
                                *Untuk pemindahan massal (banyak barang sekaligus), Anda bisa menggunakan menu
                                "Fasilitas > Manajemen Ruangan" lalu pilih "Pindah Barang Massal".
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Skenario 6: Disposal --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-red-100 p-2.5 rounded-lg text-red-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Barang rusak parah / hilang, harus
                                        diapakan?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Prosedur penghapusan aset (Disposal).</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-red-50/30 border-t border-gray-100">
                        <div class="prose prose-red max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Hati-hati, jangan asal hapus! Gunakan fitur <strong>Barang Keluar</strong>
                                agar tercatat di berita acara:</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Di tabel barang, klik tombol <span class="font-bold text-orange-600">Panah Keluar
                                        (Orange)</span>.</li>
                                <li>Pilih Tanggal Keluar.</li>
                                <li>Isi Keterangan (Contoh: "Rusak total karena korsleting" atau "Hilang saat
                                    peminjaman").</li>
                                <li>Unggah Bukti Foto/Dokumen jika ada.</li>
                                <li>Simpan. Barang akan hilang dari daftar aktif tapi tersimpan di "Log Barang Keluar".
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 7: Restore --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-yellow-100 p-2.5 rounded-lg text-yellow-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Waduh! Tidak sengaja terhapus. Apakah
                                        data hilang selamanya?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mengembalikan data dari Tong Sampah (Restore).
                                    </p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-yellow-50/30 border-t border-gray-100">
                        <div class="prose prose-yellow max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Tenang, sistem menggunakan <em>Soft Delete</em>. Data Anda aman selama belum
                                dihapus permanen.</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk ke menu <strong>Inventory</strong>.</li>
                                <li>Klik tab/tombol <span class="font-bold text-red-500">Sampah / Trash</span> di bagian
                                    atas.</li>
                                <li>Cari barang yang terhapus.</li>
                                <li>Klik tombol <span class="font-bold text-green-600">Restore (Putar Balik)</span>.
                                </li>
                                <li>Selesai! Barang kembali muncul di daftar utama.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 8: Booking Ruangan --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-green-100 p-2.5 rounded-lg text-green-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Mahasiswa ingin meminjam Lab untuk
                                        kegiatan, caranya?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Booking ruangan agar jadwal tidak bentrok.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-green-50/30 border-t border-gray-100">
                        <div class="prose prose-green max-w-none text-gray-600 ml-14">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk menu <span class="font-bold">Layanan > Booking Ruangan</span>.</li>
                                <li>Klik <strong>Tambah Booking</strong>.</li>
                                <li>Isi Judul Kegiatan (Misal: "Rapat Himpunan").</li>
                                <li>Pilih Ruangan, Tanggal Mulai, dan Tanggal Selesai.</li>
                                <li>Simpan. Jadwal akan muncul di kalender ruangan.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 9: 3D Print --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-indigo-100 p-2.5 rounded-lg text-indigo-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Ada request cetak 3D (3D Print),
                                        bagaimana alurnya?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Manajemen job printing dan stok filamen.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-indigo-50/30 border-t border-gray-100">
                        <div class="prose prose-indigo max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Fitur ini membantu menghitung sisa filamen secara otomatis.</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Pastikan stok filamen sudah diinput di menu <strong>Inventory > Stok
                                        Material</strong>.</li>
                                <li>Masuk menu <span class="font-bold">Layanan > 3D Print Request</span>.</li>
                                <li>Buat Request Baru. Upload file G-Code jika perlu.</li>
                                <li><strong>PENTING:</strong> Masukkan estimasi berat (gram) sesuai data dari slicer
                                    (Cura/PrusaSlicer).</li>
                                <li>Saat print selesai, ubah status menjadi "Completed". Stok filamen akan berkurang
                                    otomatis sesuai berat yang diinput.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Skenario 10: Log & Audit --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-gray-100 p-2.5 rounded-lg text-gray-600 shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Saya ingin tahu siapa yang terakhir
                                        mengubah data ini?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Audit Trail & Log Aktivitas.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-gray-50/30 border-t border-gray-100">
                        <div class="prose prose-gray max-w-none text-gray-600 ml-14">
                            <p>Sistem mencatat setiap tindakan penting.</p>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Masuk ke menu <strong>Admin Area > Log Aktivitas</strong>.</li>
                                <li>Anda bisa melihat tabel berisi: Siapa (User), Kapan (Waktu), Apa (Aksi), dan Detil
                                    Perubahan.</li>
                                <li>Gunakan fitur ini jika terjadi selisih stok atau perubahan data yang mencurigakan.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Skenario 11: Backup --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300"
                    x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full text-left px-6 py-5 focus:outline-none bg-white hover:bg-gray-50 transition ease-in-out duration-150 relative">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="bg-gray-800 p-2.5 rounded-lg text-white shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Jaga-jaga jika komputer rusak, bagaimana
                                        cara backup data?</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mengamankan database sistem.</p>
                                </div>
                            </div>
                            <div class="ml-4 shrink-0">
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200"
                                    :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" x-collapse style="display: none;"
                        class="px-6 pb-6 pt-2 bg-gray-50/30 border-t border-gray-100">
                        <div class="prose prose-gray max-w-none text-gray-600 ml-14">
                            <p class="mb-4">Disarankan melakukan backup rutin (mingguan/bulanan).</p>
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Masuk sebagai <strong>Superadmin</strong>.</li>
                                <li>Buka menu <span class="font-bold">Super Admin > Backup Database</span>.</li>
                                <li>Klik tombol <strong>Download Backup (.sql)</strong>.</li>
                                <li>Simpan file tersebut di tempat aman (Harddisk Eksternal / Google Drive).</li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-12 text-center text-sm text-gray-500">
                <p>Masih bingung? Hubungi Superadmin atau cek <a href="{{ route('guide.index') }}"
                        class="text-blue-600 hover:underline">Panduan Lengkap (SOP)</a>.</p>
            </div>

        </div>
    </div>
</x-app-layout>