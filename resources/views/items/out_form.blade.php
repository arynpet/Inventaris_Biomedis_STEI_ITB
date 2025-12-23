<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proses Pengeluaran Barang</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                
                {{-- INFO BARANG --}}
                <div class="mb-8 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-800">Detail Barang yang Dikeluarkan</h3>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Nama Barang</p>
                            <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase tracking-wider">Serial Number</p>
                            <p class="font-mono text-gray-900">{{ $item->serial_number }}</p>
                        </div>
                    </div>
                </div>

                {{-- FORM START --}}
                {{-- PERHATIKAN: enctype="multipart/form-data" WAJIB ADA --}}
                <form action="{{ route('items.out.store', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        
                        {{-- PENERIMA --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Penerima / Tujuan</label>
                            <input type="text" name="recipient_name" required placeholder="Contoh: PT. Maju Jaya / Pak Budi"
                                   class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- TANGGAL --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Keluar</label>
                                <input type="date" name="out_date" required value="{{ date('Y-m-d') }}"
                                       class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- UPLOAD FILE SURAT (REVISI DISINI) --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Upload Surat Jalan / Bukti</label>
                                <input type="file" name="reference_file" accept=".pdf,.jpg,.jpeg,.png"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-xl file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100
                                              border border-gray-200 rounded-xl cursor-pointer">
                                <p class="mt-1 text-xs text-gray-500">Format: PDF atau Gambar (Max. 2MB)</p>
                            </div>
                        </div>

                        {{-- ALASAN --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pengeluaran</label>
                            <textarea name="reason" rows="3" placeholder="Alasan barang dikeluarkan..."
                                      class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- BUTTONS --}}
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ route('items.index') }}" class="px-6 py-2.5 text-gray-600 font-medium hover:bg-gray-50 rounded-xl transition">Batal</a>
                            <button type="submit" class="px-8 py-2.5 bg-orange-600 text-white rounded-xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-700 transition transform hover:-translate-y-0.5">
                                Konfirmasi & Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>