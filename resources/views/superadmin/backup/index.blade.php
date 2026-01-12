<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Backup & Ekspor Data') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ resetModal: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Notifications --}}
                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        {{ session('success') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        {{ session('error') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-emerald-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Backup Data Sistem</h3>
                            <p class="text-sm text-gray-500">Pilih modul data yang ingin Anda unduh dalam format CSV
                                (Kompatibel dengan Excel).</p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.backup.download') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                            {{-- Checkbox Item --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="item_check" name="modules[]" value="items" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="item_check" class="font-bold text-gray-700 block cursor-pointer">Data
                                        Barang (Items)</label>
                                    <p class="text-gray-500">Termasuk serial number, ruangan, dan status.</p>
                                </div>
                            </div>

                            {{-- Checkbox Room --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="room_check" name="modules[]" value="rooms" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="room_check" class="font-bold text-gray-700 block cursor-pointer">Data
                                        Ruangan</label>
                                    <p class="text-gray-500">Daftar ruangan dan lokasi penyimpanan.</p>
                                </div>
                            </div>

                            {{-- Checkbox Activity Log --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="log_check" name="modules[]" value="activity_logs" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="log_check" class="font-bold text-gray-700 block cursor-pointer">Log
                                        Aktivitas (Audit Trail)</label>
                                    <p class="text-gray-500">Riwayat perubahan data oleh user.</p>
                                </div>
                            </div>

                            {{-- Checkbox Users --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="user_check" name="modules[]" value="users" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="user_check" class="font-bold text-gray-700 block cursor-pointer">Data
                                        Pengguna</label>
                                    <p class="text-gray-500">Daftar admin dan superadmin.</p>
                                </div>
                            </div>

                            {{-- Checkbox Logs Keluar --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="item_out_check" name="modules[]" value="item_out_logs" type="checkbox"
                                        checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="item_out_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Riwayat Barang
                                        Keluar</label>
                                    <p class="text-gray-500">Data barang yang rusak/hilang/dimusnahkan.</p>
                                </div>
                            </div>

                            {{-- Checkbox Borrowing --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="borrowing_check" name="modules[]" value="borrowings" type="checkbox"
                                        checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="borrowing_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Peminjaman
                                        Barang</label>
                                    <p class="text-gray-500">Log peminjaman dan pengembalian barang.</p>
                                </div>
                            </div>

                            {{-- Checkbox Room Borrowing --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="room_borrowing_check" name="modules[]" value="room_borrowings"
                                        type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="room_borrowing_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Peminjaman
                                        Ruangan</label>
                                    <p class="text-gray-500">Riwayat booking ruangan laboratorium.</p>
                                </div>
                            </div>

                            {{-- Checkbox Prints --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="prints_check" name="modules[]" value="prints" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="prints_check" class="font-bold text-gray-700 block cursor-pointer">3D
                                        Printing
                                        Jobs</label>
                                    <p class="text-gray-500">Riwayat penggunaan printer 3D.</p>
                                </div>
                            </div>

                            {{-- Checkbox Printers --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="printers_check" name="modules[]" value="printers" type="checkbox" checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="printers_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Data
                                        Printer</label>
                                    <p class="text-gray-500">Daftar mesin printer 3D.</p>
                                </div>
                            </div>

                            {{-- Checkbox Materials --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="materials_check" name="modules[]" value="materials" type="checkbox"
                                        checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="materials_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Stok Material
                                        (Filamen)</label>
                                    <p class="text-gray-500">Jenis filamen dan sisa stok.</p>
                                </div>
                            </div>

                            {{-- Checkbox Peminjam Users --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="borrowers_check" name="modules[]" value="borrowers" type="checkbox"
                                        checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="borrowers_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Data Peminjam
                                        (Mahasiswa)</label>
                                    <p class="text-gray-500">User yang terdaftar sebagai peminjam.</p>
                                </div>
                            </div>

                            {{-- Checkbox Categories --}}
                            <div
                                class="relative flex items-start p-4 hover:bg-gray-50 border border-gray-200 rounded-xl cursor-pointer transition">
                                <div class="flex items-center h-5">
                                    <input id="categories_check" name="modules[]" value="categories" type="checkbox"
                                        checked
                                        class="focus:ring-emerald-500 h-5 w-5 text-emerald-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="categories_check"
                                        class="font-bold text-gray-700 block cursor-pointer">Kategori
                                        Barang</label>
                                    <p class="text-gray-500">Master data kategori.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Date Filter Section --}}
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200" x-data="{ dateRange: 'all' }">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Filter Rentang Waktu (Opsional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                {{-- Dropdown Preset --}}
                                <div>
                                    <select name="date_range" x-model="dateRange"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                        <option value="all">Semua Waktu (Full Backup)</option>
                                        <option value="today">Hari Ini</option>
                                        <option value="week">7 Hari Terakhir</option>
                                        <option value="month">30 Hari Terakhir</option>
                                        <option value="6months">6 Bulan Terakhir</option>
                                        <option value="year">1 Tahun Terakhir</option>
                                        <option value="custom">Kustom (Pilih Tanggal)</option>
                                    </select>
                                </div>

                                {{-- Custom Date Inputs --}}
                                <div x-show="dateRange === 'custom'" class="flex gap-2" style="display: none;">
                                    <input type="date" name="start_date" placeholder="Start Date"
                                        class="block w-full text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                    <span class="self-center text-gray-500">-</span>
                                    <input type="date" name="end_date" placeholder="End Date"
                                        class="block w-full text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <span x-show="dateRange === 'all'">Mengekspor seluruh data yang ada di database.</span>
                                <span x-show="dateRange !== 'all'">Hanya mengekspor data yang dibuat (created_at) dalam periode ini. Data Master (Items, Users, Rooms) mungkin tetap diekspor semua.</span>
                            </p>
                        </div>

                        <div class="flex justify-end border-t border-gray-100 pt-6 mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-105">
                                <svg class="w-5 h-5 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download Excel (CSV)
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            {{-- New Card: Database Backup (SQL) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-indigo-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Backup Database (SQL)</h3>
                            <p class="text-sm text-gray-500">Unduh seluruh database dalam format .sql (mysqldump-php).
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.backup.database') }}" method="POST">
                        @csrf
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-indigo-50 p-6 rounded-xl border border-indigo-100 gap-4">
                            <div>
                                <h4 class="font-bold text-indigo-700 text-lg">Full Database Dump</h4>
                                <p class="text-sm text-indigo-600 mt-1 max-w-xl">Metode ini menggunakan library
                                    <b>ifsnop/mysqldump-php</b> untuk menghasilkan file SQL dump lengkap (Struktur +
                                    Data).
                                    File ini bisa digunakan untuk restore database di phpMyAdmin.
                                </p>
                            </div>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105 shrink-0">
                                <svg class="w-5 h-5 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download SQL
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- New Card: Import Data Barang (Excel) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-blue-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Import Data Barang</h3>
                            <p class="text-sm text-gray-500">Upload file Excel (.xlsx) untuk menambahkan data barang secara
                                massal.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.backup.import_items') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-blue-50 p-6 rounded-xl border border-blue-100 gap-4">
                            <div class="w-full">
                                <label class="block mb-2 text-sm font-medium text-blue-900" for="file_input">Upload
                                    File</label>
                                <input
                                    class="block w-full text-sm text-blue-900 border border-blue-300 rounded-lg cursor-pointer bg-blue-50 focus:outline-none"
                                    id="file_input" type="file" name="file" required accept=".xlsx, .xls, .csv">
                                <p class="mt-1 text-xs text-blue-500">
                                    Pastikan format kolom sesuai template (Heading Row). Kolom 'ID' digunakan untuk update data.
                                </p>
                            </div>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 shrink-0 mt-4 sm:mt-0">
                                <svg class="w-5 h-5 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Import Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- New Card: DANGER ZONE (Reset Database) --}}
            <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg mt-8 border border-red-200">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-red-100 p-3 rounded-full">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-900">Danger Zone: Reset Transaksi & Logs</h3>
                            <p class="text-sm text-red-700">Tindakan ini akan <b>MENGHAPUS SEMUA</b> data transaksi dan
                                log aktivitas. Data Master (User, Item, Ruangan) <b>TIDAK</b> akan terhapus.</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-sm text-red-600">
                            Fitur ini berguna untuk membersihkan sistem sebelum semester baru atau maintenance tahunan.
                        </div>
                        <button type="button" @click="resetModal = true"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all transform hover:scale-105 shrink-0">
                            <svg class="w-5 h-5 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Reset Data
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi Password --}}
            <div x-show="resetModal" class="fixed z-50 inset-0 overflow-y-auto" style="display: none;">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                    <div x-show="resetModal" x-transition.opacity class="fixed inset-0 transition-opacity"
                        aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="resetModal" x-transition.scale
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="{{ route('superadmin.backup.reset') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div
                                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Konfirmasi Penghapusan Data
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Apakah Anda yakin ingin menghapus data <b>Logs & Transaksi</b>?
                                                Tindakan ini tidak dapat dibatalkan!
                                            </p>

                                            {{-- Date Filter for Reset --}}
                                            <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-100" x-data="{ dateRange: 'all' }">
                                                <label class="block text-xs font-bold text-red-700 mb-1">Pilih Data yang Dihapus:</label>
                                                <div class="grid grid-cols-1 gap-2">
                                                    <select name="date_range" x-model="dateRange"
                                                        class="block w-full text-sm border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                                        <option value="all">SEMUA DATA (Clean Sweep)</option>
                                                        <option value="today">Hari Ini</option>
                                                        <option value="week">7 Hari Terakhir</option>
                                                        <option value="month">30 Hari Terakhir</option>
                                                        <option value="6months">6 Bulan Terakhir</option>
                                                        <option value="year">1 Tahun Terakhir</option>
                                                        <option value="custom">Kustom (Rentang Tertentu)</option>
                                                    </select>

                                                    <div x-show="dateRange === 'custom'" class="flex gap-2" style="display: none;">
                                                        <input type="date" name="start_date" 
                                                            class="block w-full text-sm border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                                        <input type="date" name="end_date"
                                                            class="block w-full text-sm border-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 rounded-md">
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-2">
                                                Silakan masukkan password admin Anda untuk konfirmasi.
                                            </p>

                                            <div class="mt-4">
                                                <label for="password"
                                                    class="block text-sm font-medium text-gray-700">Password
                                                    Admin</label>
                                                <input type="text" onfocus="this.type='password';" name="password"
                                                    id="password" required autocomplete="new-password"
                                                    class="mt-1 focus:ring-red-500 focus:border-red-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                    placeholder="Masukkan password Anda...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Ya, Hapus Data
                                </button>
                                <button type="button" @click="resetModal = false"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>