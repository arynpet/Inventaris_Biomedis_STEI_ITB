<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Backup & Ekspor Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

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

                        <div class="flex justify-end border-t border-gray-100 pt-6">
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
        </div>
    </div>
</x-app-layout>