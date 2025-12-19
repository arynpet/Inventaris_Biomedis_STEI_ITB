<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peminjaman Barang
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 3000)"
             class="mx-4 my-4 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="mx-4 my-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-md">
            <p class="font-bold">Terjadi Kesalahan:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- MAIN CONTENT WRAPPER WITH ALPINE DATA --}}
    <div class="py-6" x-data="borrowingPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER ACTIONS --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">Daftar Peminjaman</h3>
                    <p class="text-sm text-gray-500">Kelola barang yang sedang dipinjam</p>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('borrowings.history') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-xl hover:bg-gray-800 shadow-lg hover:shadow-xl transition-all">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        History
                    </a>

                    <a href="{{ route('borrowings.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Pinjam Baru
                    </a>
                </div>
            </div>

            {{-- TABLE WRAPPER --}}
            <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">#</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Barang</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Peminjam</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Tgl Pinjam</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Tenggat</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Status</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($borrowings as $borrow)
                                <tr class="hover:bg-blue-50/50 transition duration-150">
                                    <td class="px-6 py-4 text-gray-500">{{ $borrowings->firstItem() + $loop->index }}</td>
                                    
                                    {{-- ITEM --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $borrow->item->name ?? 'Item Dihapus' }}</div>
                                                <div class="text-xs text-gray-500">{{ $borrow->item->serial_number ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- BORROWER --}}
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-700">{{ $borrow->borrower->name ?? 'User Dihapus' }}</span>
                                    </td>

                                    {{-- DATES --}}
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $borrow->borrow_date->format('d M Y') }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        @if($borrow->return_date)
                                            <span class="{{ \Carbon\Carbon::parse($borrow->return_date)->isPast() ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                                {{ $borrow->return_date->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $statusClasses = [
                                                'borrowed' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'returned' => 'bg-green-100 text-green-800 border-green-200',
                                                'late'     => 'bg-red-100 text-red-800 border-red-200'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$borrow->status] ?? 'bg-gray-100' }}">
                                            {{ ucfirst($borrow->status) }}
                                        </span>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{-- Detail --}}
                                            <a href="{{ route('borrowings.show', $borrow->id) }}"
                                               class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Detail">
                                               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>

                                            {{-- Tombol Kembalikan (Trigger Modal) --}}
                                            <button @click="openReturnModal('{{ route('borrowings.return', $borrow->id) }}', '{{ $borrow->item->name ?? 'Item' }}', '{{ $borrow->borrower->name ?? 'User' }}')"
                                                    class="group flex items-center px-3 py-1.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 shadow-md transition-all text-xs font-semibold">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                Kembalikan
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-12">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <p class="text-gray-500 text-sm">Tidak ada barang yang sedang dipinjam.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $borrowings->links() }}
                </div>
            </div>

            {{-- ============================== --}}
            {{-- MODAL PENGEMBALIAN BARANG --}}
            {{-- ============================== --}}
            <div x-show="isReturnModalOpen" 
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all"
                     @click.away="isReturnModalOpen = false"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Konfirmasi Pengembalian
                        </h3>
                        <button @click="isReturnModalOpen = false" class="text-white/80 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <div class="bg-gray-50 rounded-lg p-4 mb-5 border border-gray-100">
                            <p class="text-sm text-gray-500">Barang:</p>
                            <p class="font-bold text-gray-800 text-lg" x-text="itemName"></p>
                            <div class="h-px bg-gray-200 my-2"></div>
                            <p class="text-sm text-gray-500">Peminjam:</p>
                            <p class="font-semibold text-gray-700" x-text="borrowerName"></p>
                        </div>

                        <form :action="returnActionUrl" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-5">
                                <label class="block text-gray-700 font-semibold mb-2">Kondisi Barang Saat Kembali</label>
                                <div class="relative">
                                    <select name="condition" required
                                            class="w-full rounded-xl border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 py-3 pl-4 pr-10 appearance-none bg-white shadow-sm">
                                        <option value="" disabled selected>-- Pilih Kondisi --</option>
                                        <option value="good">✨ Baik (Good)</option>
                                        <option value="damaged">⚠️ Rusak Ringan (Damaged)</option>
                                        <option value="broken">❌ Rusak Berat/Hilang (Broken)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    *Jika pilih <b>Rusak</b>, status barang otomatis berubah jadi <b>Maintenance</b>.
                                </p>
                            </div>

                            <div class="flex justify-end gap-3 mt-6">
                                <button type="button" @click="isReturnModalOpen = false"
                                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium transition">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-bold shadow-lg hover:shadow-emerald-500/30 transition transform active:scale-95">
                                    Simpan & Kembalikan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ALPINE SCRIPT --}}
    <script>
        function borrowingPage() {
            return {
                isReturnModalOpen: false,
                returnActionUrl: '',
                itemName: '',
                borrowerName: '',

                openReturnModal(url, item, borrower) {
                    this.returnActionUrl = url;
                    this.itemName = item;
                    this.borrowerName = borrower;
                    this.isReturnModalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>