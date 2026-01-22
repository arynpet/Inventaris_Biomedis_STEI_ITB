<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman - Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">
    <div class="max-w-3xl mx-auto p-6">
        
        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman Saya</h1>
            <a href="{{ route('public.catalog') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                &larr; Kembali ke Katalog
            </a>
        </div>

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- LIST --}}
        <div class="bg-white shadow rounded-2xl overflow-hidden">
            @if($loans->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Belum ada riwayat peminjaman.
                    <br>
                    <a href="{{ route('public.catalog') }}" class="text-blue-600 font-bold mt-2 inline-block">Mulai Pinjam Sekarang</a>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($loans as $loan)
                        <li class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold text-lg text-gray-900">{{ $loan->item->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        Qty: {{ $loan->quantity }} &middot; Tgl: {{ $loan->borrow_date->format('d M Y') }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 italic">"{{ $loan->purpose }}"</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="font-semibold">PJ:</span> {{ $loan->penanggung_jawab ?? '-' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($loan->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Menunggu Persetujuan
                                        </span>
                                    @elseif($loan->status == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui (Aktif)
                                        </span>
                                    @elseif($loan->status == 'returned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Sudah Dikembalikan
                                        </span>
                                    @elseif($loan->status == 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Ditolak
                                        </span>
                                        @if($loan->admin_note)
                                            <p class="text-xs text-red-600 mt-1">{{ $loan->admin_note }}</p>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>
