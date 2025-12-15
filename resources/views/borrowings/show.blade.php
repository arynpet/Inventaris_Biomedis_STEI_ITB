<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Peminjaman</h1>

            <a href="{{ route('borrowings.index') }}"
               class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                Back
            </a>
        </div>

        {{-- CARD WRAPPER --}}
        <div class="bg-white border shadow-sm p-6 rounded-2xl space-y-6">

            {{-- INFO PEMINJAM --}}
            <div>
                <h2 class="font-semibold text-lg text-gray-700 mb-2">Data Peminjam</h2>

                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-medium">{{ $borrow->borrower->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-medium">{{ $borrow->borrower->nim ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $borrow->borrower->email ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">No Telepon</p>
                        <p class="font-medium">{{ $borrow->borrower->phone ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-medium capitalize">{{ $borrow->borrower->role ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- INFORMASI BARANG --}}
            <div>
                <h2 class="font-semibold text-lg text-gray-700 mb-2">Data Barang</h2>

                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p class="text-sm text-gray-500">Nama Barang</p>
                        <p class="font-medium">{{ $borrow->item->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">No. Asset</p>
                        <p class="font-medium">{{ $borrow->item->asset_number ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Ruangan</p>
                        <p class="font-medium">
                            {{ $borrow->item->room->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status Barang</p>
                        <p class="font-medium capitalize">{{ $borrow->item->status }}</p>
                    </div>
                </div>
            </div>

            <hr>

            {{-- DETAIL PEMINJAMAN --}}
            <div>
                <h2 class="font-semibold text-lg text-gray-700 mb-2">Detail Peminjaman</h2>

                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pinjam</p>
                        <p class="font-medium">{{ $borrow->borrow_date }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kembali</p>
                        <p class="font-medium">
                            {{ $borrow->return_date ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 rounded-full text-sm
                            @if($borrow->status === 'borrowed') bg-yellow-100 text-yellow-700
                            @elseif($borrow->status === 'returned') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($borrow->status) }}
                        </span>
                    </div>

                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Catatan</p>
                        <p class="font-medium">{{ $borrow->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
