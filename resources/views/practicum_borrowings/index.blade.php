<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Peminjaman Paket Praktikum') }}
        </h2>
    </x-slot>

    @php
        $users = \App\Models\PeminjamUser::orderBy('name')->get();
    @endphp

    <div class="py-12 bg-gray-50/50 min-h-screen" x-data="{ 
        selectedPackage: null,
        openModal: false,
        openBorrowModal(pkg) {
            this.selectedPackage = pkg;
            this.openModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h3
                        class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-600 tracking-tight">
                        Pilih Paket Praktikum
                    </h3>
                    <p class="text-gray-500 mt-2 text-lg max-w-2xl">
                        Pilih paket peralatan yang sudah dikelompokkan untuk mempercepat proses peminjaman praktikum di
                        laboratorium.
                    </p>
                </div>

                {{-- Optional: Search or Filter Button (Placeholder) --}}
                {{-- <button
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-600 shadow-sm hover:bg-gray-50">Filter</button>
                --}}
            </div>

            {{-- Grid Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages as $package)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col relative group">

                        {{-- Decorative Gradient Blob --}}
                        <div
                            class="absolute top-0 right-0 -mt-8 -mr-8 w-24 h-24 bg-gradient-to-br from-teal-100 to-emerald-50 rounded-full blur-2xl opacity-50 group-hover:opacity-100 transition-opacity">
                        </div>

                        <div class="p-7 flex-1 flex flex-col relative z-10">
                            <div class="flex justify-between items-start mb-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-50 to-emerald-50 text-teal-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform duration-300 border border-teal-100">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                        </path>
                                    </svg>
                                </div>
                                <span
                                    class="bg-teal-50 text-teal-700 text-xs font-bold px-3 py-1.5 rounded-full border border-teal-100 shadow-sm">
                                    {{ $package->items_count }} Items
                                </span>
                            </div>

                            <h4
                                class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-teal-700 transition-colors">
                                {{ $package->name }}
                            </h4>
                            <p class="text-sm text-gray-500 mb-6 leading-relaxed line-clamp-3">
                                {{ $package->description ?? 'Tidak ada deskripsi rinci untuk paket ini.' }}
                            </p>
                        </div>

                        <div class="p-6 pt-0 mt-auto relative z-10">
                            <button @click="openBorrowModal({{ $package->toJson() }})"
                                class="w-full relative overflow-hidden group/btn flex items-center justify-center px-4 py-3.5 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-gray-900 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300">
                                {{-- Button Gradient Overlay on Hover --}}
                                <div
                                    class="absolute inset-0 w-full h-full bg-gradient-to-r from-teal-600 to-emerald-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300">
                                </div>

                                <div class="relative flex items-center gap-2">
                                    <span>Pinjam Paket Ini</span>
                                    <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty State --}}
            @if($packages->isEmpty())
                <div
                    class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300 shadow-sm opacity-75">
                    <div class="mx-auto h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-xl font-bold text-gray-900">Belum ada paket tersedia</h3>
                    <p class="mt-2 text-gray-500 max-w-md mx-auto">Silakan buat paket praktikum terlebih dahulu di menu
                        <strong>Item Packages</strong> agar bisa dipinjam di sini.</p>
                    <a href="{{ route('item-packages.create') }}"
                        class="inline-flex items-center mt-6 px-5 py-2.5 rounded-lg bg-teal-600 text-white font-semibold hover:bg-teal-700 transition shadow-md">
                        Buat Paket Baru
                    </a>
                </div>
            @endif

        </div>

        {{-- IMPROVED BORROW MODAL --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true" style="display: none;">
            {{-- Backdrop --}}
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="openModal = false"
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">

                    <form action="{{ route('practicum-borrowings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="item_package_id" :value="selectedPackage?.id">

                        {{-- Modal Header --}}
                        <div class="bg-gradient-to-r from-teal-600 to-emerald-600 px-6 py-5 flex items-center gap-4">
                            <div
                                class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-white backdrop-blur-sm">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            <div class="text-white">
                                <h3 class="text-lg font-bold" id="modal-title">Konfirmasi Peminjaman</h3>
                                <p class="text-teal-100 text-xs font-medium">Isi detail peminjaman untuk paket ini.</p>
                            </div>
                            <button type="button" @click="openModal = false"
                                class="ml-auto text-white/70 hover:text-white">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-6 py-6 space-y-5">

                            {{-- Selected Package Info --}}
                            <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-teal-600 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-bold text-teal-600 uppercase tracking-wide">Paket Terpilih
                                    </p>
                                    <p class="text-gray-900 font-bold text-lg" x-text="selectedPackage?.name"></p>
                                    <p class="text-sm text-gray-500"
                                        x-text="(selectedPackage?.items_count || 0) + ' Barang di dalam paket'"></p>
                                </div>
                            </div>

                            {{-- Form Fields --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Peminjam</label>
                                <select name="user_id" required
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm py-2.5">
                                    <option value="">-- Pilih Mahasiswa / Peminjam --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->nim }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pinjam</label>
                                    <input type="date" name="borrow_date" value="{{ date('Y-m-d') }}" required
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal
                                        Kembali</label>
                                    <input type="date" name="return_date" required
                                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm py-2.5">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Keperluan /
                                    Catatan</label>
                                <textarea name="notes" rows="2"
                                    class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                                    placeholder="Contoh: Praktikum Fisika Modul 3..."></textarea>
                            </div>

                        </div>

                        {{-- Footer --}}
                        <div
                            class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-gray-100">
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-teal-700 transition focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Proses Peminjaman
                            </button>
                            <button type="button" @click="openModal = false"
                                class="w-full sm:w-auto mt-3 sm:mt-0 inline-flex justify-center items-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>