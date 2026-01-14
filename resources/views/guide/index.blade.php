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
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Manual Guide & SOP</h1>
                    berurutan dari manajemen dasar hingga fitur administrasi lanjutan.</p>

                    <div class="mt-6">
                        <a href="{{ route('guide.scenarios') }}"
                            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wider hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Lihat Panduan Praktis (Studi Kasus)
                        </a>
                        <p class="mt-2 text-xs text-gray-500">
                            *Cocok untuk pemula yang ingin belajar lewat contoh kasus nyata.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 relative">

                {{-- TABLE OF CONTENTS (Sticky Sidebar) --}}
                <div class="lg:col-span-1">
                    <div
                        class="sticky top-24 bg-white rounded-lg shadow-sm p-4 space-y-2 border border-blue-50 max-h-[80vh] overflow-y-auto">

                        {{-- Group 1 --}}
                        <div class="mb-4">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">I. Dasar &
                                Inventaris</h5>
                            <a href="#section-a"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">A.
                                Persiapan & Login</a>
                            <a href="#section-b"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">B.
                                Input Barang Baru</a>
                            <a href="#section-c"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">C.
                                Cetak Label QR</a>
                        </div>

                        {{-- Group 2 --}}
                        <div class="mb-4">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">II. Layanan
                                Sirkulasi</h5>
                            <a href="#section-d"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">D.
                                Data Peminjam</a>
                            <a href="#section-e"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">E.
                                Transaksi Peminjaman</a>
                            <a href="#section-f"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">F.
                                Pengembalian Barang</a>
                        </div>

                        {{-- Group 3 --}}
                        <div class="mb-4">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">III. Fasilitas &
                                Lab</h5>
                            <a href="#section-g"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">G.
                                Booking Ruangan</a>
                            <a href="#section-h"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">H.
                                3D Printing</a>
                        </div>

                        {{-- Group 4 --}}
                        <div class="mb-4">
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">IV. Maintenance
                            </h5>
                            <a href="#section-i"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">I.
                                Barang Keluar (Disposal)</a>
                            <a href="#section-j"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">J.
                                Recovery (Sampah)</a>
                        </div>

                        {{-- Group 5 --}}
                        <div>
                            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">V. Admin Area</h5>
                            <a href="#section-k"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">K.
                                Kelola Admin</a>
                            <a href="#section-l"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">L.
                                Log Aktivitas</a>
                            <a href="#section-m"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">M.
                                Backup & Restore</a>
                            <a href="#section-n"
                                class="block text-sm text-gray-600 hover:text-blue-600 hover:bg-blue-50 px-2 py-1.5 rounded transition">N.
                                Tips & Trik</a>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="lg:col-span-3 space-y-12">

                    {{-- GROUP 1: DASAR & INVENTARIS --}}

                    {{-- A. Persiapan & Login --}}
                    <div id="section-a"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-blue-500">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            BAB 1 - DASAR</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">A</span>
                                    Persiapan & Login
                                </h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Pastikan terhubung ke jaringan kampus.</li>
                                    <li>Akses URL aplikasi via browser.</li>
                                    <li>Login menggunakan akun <b>Admin</b> atau <b>Superadmin</b>.</li>
                                </ul>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-default select-none">
                                        LOG IN
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Login Screen</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- B. Menambahkan Barang Baru --}}
                    <div id="section-b"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-blue-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">B</span>
                                    Input Barang Baru
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Langkah pertama inventarisasi adalah memasukkan
                                    data aset ke dalam sistem.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Buka menu <b class="text-blue-600">Inventory > Data Barang</b>.</li>
                                    <li>Klik tombol <b>+ Tambah Barang</b>.</li>
                                    <li>Isi detail: Nama, Kategori, Kondisi, Tahun Perolehan.</li>
                                    <li>Klik Simpan.</li>
                                </ol>



                                {{-- VISUALISASI NOMOR SERI --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <h4 class="text-sm font-bold text-blue-800 mb-2 uppercase tracking-wide">
                                        Standar Penomoran Aset (PENTING)
                                    </h4>

                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="text-2xl font-mono font-black text-gray-800 tracking-wider">
                                            E-TRML-26003
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2 text-xs font-semibold">
                                        <span class="px-2 py-1 rounded bg-red-100 text-red-700 border border-red-200">
                                            E = Jenis (Elektronik)
                                        </span>
                                        <span
                                            class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            TRML = Singkatan (Terminal)
                                        </span>
                                        <span
                                            class="px-2 py-1 rounded bg-green-100 text-green-700 border border-green-200">
                                            26 = Tahun (2026)
                                        </span>
                                        <span
                                            class="px-2 py-1 rounded bg-blue-100 text-blue-700 border border-blue-200">
                                            003 = No. Urut
                                        </span>
                                    </div>

                                    <p class="mt-3 text-xs text-blue-600 italic">
                                        "Harap gunakan format ini saat mengisi kolom 'Nomor Seri' agar data inventaris
                                        rapi dan mudah dilacak."
                                    </p>
                                </div>

                                <div class="flex gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-widest cursor-default select-none shadow-sm">
                                        + Tambah Barang
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Form Input</span>
                                </div>
                            </div>
                        </div>

                        {{-- NEW: SMART SERIAL GENERATOR GUIDE (FULL WIDTH) --}}
                        <div class="mt-8 mb-8 border-t border-gray-100 pt-6">
                            <h4 class="font-bold text-indigo-900 text-base mb-4 flex items-center">
                                <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </span>
                                Fitur Baru: Smart Serial Generator (Auto)
                            </h4>

                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4 opacity-5">
                                    <svg class="w-32 h-32 text-indigo-900" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>

                                <div class="relative z-10">
                                    <p class="text-sm text-indigo-800 mb-6 leading-relaxed max-w-2xl">
                                        Input barang kini lebih cepat. Anda tidak perlu lagi mengetik Serial Number
                                        secara manual untuk setiap item. Cukup tentukan jumlahnya, sistem yang akan
                                        bekerja.
                                    </p>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        {{-- Point 1 --}}
                                        <div
                                            class="flex items-start bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-indigo-600 text-white text-sm font-bold rounded-lg mr-4">
                                                1</div>
                                            <div>
                                                <strong class="text-indigo-900 text-sm block mb-1">Serial Number
                                                    Opsional</strong>
                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                    Kosongkan kolom Serial Number saat input. Sistem otomatis generate
                                                    kode unik <code
                                                        class="bg-gray-100 px-1 py-0.5 rounded text-indigo-600 font-mono font-bold">KODE-THN-URUT</code>.
                                                </p>
                                            </div>
                                        </div>

                                        {{-- Point 2 --}}
                                        <div
                                            class="flex items-start bg-white p-4 rounded-xl border border-indigo-100 shadow-sm">
                                            <div
                                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-indigo-600 text-white text-sm font-bold rounded-lg mr-4">
                                                2</div>
                                            <div>
                                                <strong class="text-indigo-900 text-sm block mb-1">Input Banyak (Batch
                                                    Mode)</strong>
                                                <p class="text-xs text-gray-600 leading-relaxed">
                                                    Isi "Jumlah Input Barang" (misal: 10). Sistem langsung membuat 10
                                                    data barang berurutan dalam sekali klik.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PENJELASAN KATEGORI BARANG --}}
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h4 class="text-base font-bold text-gray-800 mb-4 px-1">Penjelasan Kategori Barang</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- 1. Aset Tetap --}}
                                <div
                                    class="bg-white border text-left border-blue-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-900 text-sm">Aset Tetap (Fixed Asset)</h5>
                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                                Barang inventaris utama, masa pakai panjang (>1 tahun). Tidak habis
                                                dipakai.
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-1 rounded">
                                                Ex: Mikroskop, Alat Ukur, Mebel
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. Bahan Habis Pakai --}}
                                <div
                                    class="bg-white border text-left border-orange-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-orange-100 text-orange-600 p-2 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-900 text-sm">Bahan Habis Pakai (Consumables)
                                            </h5>
                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                                Barang yang berkurang jumlah/volumenya saat dipakai hingga habis.
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-1 rounded">
                                                Ex: Filamen, Resin, Alkohol, Tisu
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. Alat Riset --}}
                                <div
                                    class="bg-white border text-left border-purple-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-purple-100 text-purple-600 p-2 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                <path
                                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-900 text-sm">Alat Riset (Research)</h5>
                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                                Alat khusus untuk proyek penelitian/tugas akhir. Penggunaan
                                                terbatas/eksklusif.
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-1 rounded">
                                                Ex: Sensor Khusus, Komponen Hibah
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. Alat Praktikum --}}
                                <div
                                    class="bg-white border text-left border-green-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-green-100 text-green-600 p-2 rounded-lg">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 6.253v13zM12 6.253C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-gray-900 text-sm">Alat Praktikum (Teaching)</h5>
                                            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                                Alat untuk kegiatan belajar massal (modul lab). Jumlah banyak &
                                                perputaran cepat.
                                            </p>
                                            <p
                                                class="text-[10px] text-gray-400 mt-2 font-mono bg-gray-50 inline-block px-1 rounded">
                                                Ex: Kit Arduino Dasar, Multimeter
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- C. Cetak Label --}}
                    <div id="section-c"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-blue-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">C</span>
                                    Cetak Label QR
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Setelah barang masuk sistem, tempelkan label QR
                                    fisik pada aset.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Di tabel data barang, klik ikon <b>Mata (Detail)</b>.</li>
                                    <li>Klik tombol <b>Cetak QR</b>.</li>
                                    <li>Print label dan tempelkan pada barang.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-default">
                                        CETAK QR
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4h-4v-4H6v-4h6v4m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="text-xs">Label Review</span>
                                </div>
                            </div>
                        </div>

                        {{-- NEW SECTION: FUNGSI QR CODE --}}
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h4 class="text-base font-bold text-gray-800 mb-6 px-1 flex items-center gap-2">
                                <span class="bg-yellow-400 text-white p-1 rounded-md shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </span>
                                Mengapa Harus Ada QR Code?
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                {{-- Kolom 1-3: Penjelasan --}}
                                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    {{-- Feature 1 --}}
                                    <div
                                        class="flex flex-col items-center text-center p-3 rounded-xl bg-yellow-50/50 hover:bg-yellow-50 transition border border-transparent hover:border-yellow-200">
                                        <div
                                            class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-3 shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <h5 class="font-bold text-gray-800 text-sm mb-1">Identifikasi Kilat</h5>
                                        <p class="text-xs text-gray-600 leading-snug">
                                            Scan QR untuk buka detail barang di HP dalam <span
                                                class="font-semibold text-yellow-700">1 detik</span>. Tanpa cari manual.
                                        </p>
                                    </div>

                                    {{-- Feature 2 --}}
                                    <div
                                        class="flex flex-col items-center text-center p-3 rounded-xl bg-blue-50/50 hover:bg-blue-50 transition border border-transparent hover:border-blue-200">
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3 shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11">
                                                </path>
                                            </svg>
                                        </div>
                                        <h5 class="font-bold text-gray-800 text-sm mb-1">Sirkulasi Otomatis</h5>
                                        <p class="text-xs text-gray-600 leading-snug">
                                            Admin cukup <b>Scan</b> barang yang akan dipinjam siswa. Sistem otomatis
                                            mencatat.
                                        </p>
                                    </div>

                                    {{-- Feature 3 --}}
                                    <div
                                        class="flex flex-col items-center text-center p-3 rounded-xl bg-green-50/50 hover:bg-green-50 transition border border-transparent hover:border-green-200">
                                        <div
                                            class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-3 shadow-sm">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                                </path>
                                            </svg>
                                        </div>
                                        <h5 class="font-bold text-gray-800 text-sm mb-1">Audit & Stok Opname</h5>
                                        <p class="text-xs text-gray-600 leading-snug">
                                            Cek fisik tahunan jadi mudah. Scan barang untuk validasi "Hadir & Layak".
                                        </p>
                                    </div>
                                </div>

                                {{-- Kolom 4: Ilustrasi Stiker --}}
                                <div class="flex items-center justify-center">
                                    <div
                                        class="bg-white border-2 border-gray-800 rounded p-3 shadow-lg transform rotate-2 hover:rotate-0 transition duration-300 w-32 flex flex-col items-center">
                                        <div class="bg-gray-800 p-2 rounded mb-2">
                                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M3 3h6v6H3V3zm2 2v2h2V5H5zm8-2h6v6h-6V3zm2 2v2h2V5h-2zM3 11h6v6H3v-6zm2 2v2h2v-2H5zm13-2h3v3h-3v-3zm0 3h-3v3h3v-3zm3 0h-3v3h3v-3zm-6 3h3v3h-3v-3zm3 0h3v3h-3v-3zm-3-6h3v3h-3v-3zm3-3h3v3h-3v-3z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest text-gray-800">Scan
                                            Me</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- GROUP 2: SIRKULASI --}}

                    {{-- D. Data Peminjam --}}
                    <div id="section-d"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-indigo-500">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            BAB 2 - SIRKULASI</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">D</span>
                                    Database Peminjam
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Wajib! Daftarkan mahasiswa/dosen sebelum mereka
                                    bisa meminjam.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Menu <b class="text-blue-600">Inventory > Data Peminjam</b>.</li>
                                    <li>Klik <b>Tambah Peminjam</b>.</li>
                                    <li>Isi Nama, NIM/NIP, Status, No. WA.</li>
                                    <li>Simpan.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded text-xs">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                            </path>
                                        </svg>
                                        User Baru
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Daftar Peminjam</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- E. Peminjaman --}}
                    <div id="section-e"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-indigo-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">E</span>
                                    Transaksi Peminjaman
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Mencatat barang yang keluar sementara.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Menu <b class="text-blue-600">Layanan > Pinjam Alat</b>.</li>
                                    <li>Klik <b>Tambah Peminjaman</b>.</li>
                                    <li>Pilih Nama Peminjam & Scan/Pilih Barang.</li>
                                    <li>Set Tanggal Kembali & Simpan.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button type="button"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white tracking-widest cursor-default select-none shadow-sm">
                                        + Tambah Peminjaman
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Form Pinjam</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- F. Pengembalian --}}
                    <div id="section-f"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-indigo-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">F</span>
                                    Pengembalian Barang
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Menutup transaksi peminjaman.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Di tabel Peminjaman, cari status "Sedang Dipinjam".</li>
                                    <li>Klik tombol <b>Ceklis Hijau</b>.</li>
                                    <li>Periksa kondisi barang (Baik/Rusak).</li>
                                    <li>Konfirmasi Pengembalian.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button
                                        class="inline-flex items-center justify-center p-2 bg-green-100 rounded-md text-green-600 cursor-default">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Modal Kembali</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- GROUP 3: FASILITAS --}}

                    {{-- G. Ruangan --}}
                    <div id="section-g"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-green-500">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            BAB 3 - FASILITAS</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">G</span>
                                    Booking Ruangan
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Pencatatan penggunaan laboratorium & kelas.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Menu <b class="text-blue-600">Layanan > Booking Ruangan</b>.</li>
                                    <li>Klik <b>Pinjam Ruangan</b>.</li>
                                    <li>Pilih Ruangan, Tanggal, Jam Mulai & Selesai.</li>
                                    <li>Simpan.</li>
                                </ol>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Form Booking</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- H. 3D Printing --}}
                    <div id="section-h"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-green-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">H</span>
                                    Layanan 3D Printing
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Manajemen job & stok filamen.</p>
                                <ul class="list-disc list-inside text-gray-600 text-sm mb-4">
                                    <li><b>Stok Material</b>: Menu Inventory > Stok Material untuk update berat filamen.
                                    </li>
                                    <li><b>Input Print</b>: Menu Layanan > Request Print. Masukkan estimasi gram (dari
                                        slicer) untuk potong stok otomatis.</li>
                                </ul>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">3D Print Dash</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- GROUP 4: MAINTENANCE --}}

                    {{-- I. Disposal --}}
                    <div id="section-i"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-orange-500">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            BAB 4 - MAINTENANCE</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-orange-500 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">I</span>
                                    Barang Keluar (Disposal)
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Pencatatan resmi barang rusak/hibah/hilang.</p>
                                <div class="bg-yellow-50 border-1 border-yellow-200 p-3 rounded mb-4 text-xs">
                                    <span class="font-bold text-yellow-700">PENTING:</span> Gunakan tombol <b>ORANYE</b>
                                    untuk disposal resmi (tercatat di log distribusi). Tombol <b>MERAH</b> hanya untuk
                                    menghapus data salah (masuk tong sampah).
                                </div>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Klik tombol <b>Panah Oranye (Keluar)</b> pada tabel barang.</li>
                                    <li>Isi Berita Acara (Alasan, Penerima, Dokumen).</li>
                                    <li>Simpan. Barang akan berstatus "Dikeluarkan".</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button
                                        class="inline-flex items-center justify-center p-2 bg-orange-100 rounded-md text-orange-600 cursor-default">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Form Disposal</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- J. Recovery --}}
                    <div id="section-j"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-orange-500">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-orange-500 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">J</span>
                                    Recovery Data (Sampah)
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Memulihkan data yang tidak sengaja terhapus (Soft
                                    Delete).</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Klik tombol <b>Sampah (Outline Merah)</b> di atas tabel Inventory.</li>
                                    <li>Cari barang yang ingin dikembalikan.</li>
                                    <li>Klik tombol <b>Restore (Putar Balik)</b>.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <button
                                        class="inline-flex items-center px-3 py-2 bg-white border border-red-200 text-red-600 rounded-lg text-sm font-bold shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        SAMPAH
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    <span class="text-xs">Halaman Sampah</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- GROUP 5: ADMIN --}}

                    {{-- K. Kelola Admin --}}
                    <div id="section-k"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-gray-700">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            BAB 5 - SUPER ADMIN</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-gray-700 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">K</span>
                                    Kelola Admin
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Tambah/Hapus petugas akses aplikasi.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Masuk ke <b>Admin Area > Kelola Admin</b>.</li>
                                    <li>Klik <b>Tambah User Baru</b>.</li>
                                    <li>Tentukan Role (Admin Biasa / Superadmin)</li>
                                </ol>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span class="text-xs">User Management</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- L. Logs --}}
                    <div id="section-l"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-gray-700">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-gray-700 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">L</span>
                                    Log Aktivitas
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Audit trail keamanan.</p>
                                <ul class="list-disc list-inside text-gray-600 text-sm mb-4">
                                    <li>Menu <b>Activity Logs</b> menampilkan semua tindakan user.</li>
                                    <li>Contoh: "User A menghapus Barang B pada Jam C".</li>
                                </ul>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <table class="w-full text-xs text-left bg-white border">
                                        <tr class="bg-gray-100 border-b">
                                            <th class="p-1">User</th>
                                            <th class="p-1">Aksi</th>
                                        </tr>
                                        <tr class="border-b">
                                            <td class="p-1">Admin</td>
                                            <td class="p-1 text-red-600">DELETE Item</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- M. Backup --}}
                    <div id="section-m"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-gray-700">
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-gray-700 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">M</span>
                                    Backup & Restore
                                </h3>
                                <p class="text-gray-600 mb-4 text-sm">Penyelamatan data via Excel.</p>
                                <ol class="list-decimal list-inside text-gray-600 space-y-2 mb-4">
                                    <li>Menu <b>Backup & Reset</b>.</li>
                                    <li>Klik <b>Download Excel</b> untuk simpan data ke PC.</li>
                                    <li>Gunakan form <b>Import/Upload</b> untuk memulihkan data dari file Excel.</li>
                                </ol>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-center">
                                    <button
                                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg font-bold text-xs shadow">
                                        Download Backup
                                    </button>
                                </div>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span class="text-xs">Backup Page</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- N. Fitur Pintar --}}
                    <div id="section-n"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative scroll-mt-24 border-l-4 border-purple-500">
                        <div
                            class="absolute top-0 right-0 bg-gray-100 text-gray-500 px-3 py-1 rounded-bl-lg font-bold text-xs">
                            EXTRA</div>
                        <div class="mt-4 md:flex gap-8">
                            <div class="md:w-1/2">
                                <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                    <span
                                        class="bg-purple-600 text-white w-8 h-8 rounded-full flex items-center justify-center mr-3 text-sm">N</span>
                                    Tips & Fitur Pintar
                                </h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-2 mb-4 text-sm">
                                    <li><b>Bulk Action</b>: Centang banyak barang sekaligus untuk Hapus/Duplicate
                                        massal.</li>
                                    <li><b>Fix QR</b>: Tombol darurat di halaman barang untuk generate ulang gambar QR
                                        yang rusak.</li>
                                    <li><b>Filter & Search</b>: Gunakan kolom pencarian di setiap tabel untuk menemukan
                                        data cepat.</li>
                                </ul>
                            </div>
                            <div class="md:w-1/2 mt-4 md:mt-0">
                                <div
                                    class="w-full h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center text-gray-400 flex-col gap-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="text-xs">Bulk Action UI</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>