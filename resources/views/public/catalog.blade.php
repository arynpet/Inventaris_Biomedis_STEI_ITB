<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Katalog Lab Biomedis STEI ITB</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hide Scrollbar for Filter Chips but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ showModal: false, activeItem: null, showLoginModal: {{ $errors->has('email') || $errors->has('nim') ? 'true' : 'false' }}, showRegisterModal: {{ $errors->has('name') ? 'true' : 'false' }}, showPasswordModal: {{ $errors->has('current_password') || $errors->has('new_password') ? 'true' : 'false' }} }">
    
    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-emerald-500 text-white rounded-full shadow-lg flex items-center gap-2 text-sm font-bold animate-bounce-short">
            <span>✅ {{ session('success') }}</span>
        </div>
    @endif
     @if (session('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-blue-500 text-white rounded-full shadow-lg flex items-center gap-2 text-sm font-bold animate-bounce-short">
            <span>ℹ️ {{ session('info') }}</span>
        </div>
    @endif
    
    {{-- GENERIC ERROR ALERT (Validation) --}}
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-36 left-1/2 transform -translate-x-1/2 z-50 px-6 py-4 bg-red-500 text-white rounded-xl shadow-2xl flex flex-col items-center gap-1 text-sm animate-bounce-short text-center min-w-[300px]">
            <span class="font-bold text-lg">⚠️ Gagal Mengajukan!</span>
            <ul class="list-disc list-inside text-xs mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- STICKY HEADER --}}
    <header class="fixed top-0 w-full z-40 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                {{-- Top Row: Logo & Title & Auth --}}
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-600 text-white p-2 rounded-lg shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 leading-none">Katalog Lab</h1>
                            <p class="text-xs text-gray-500 font-medium">Biomedis STEI ITB</p>
                        </div>
                    </div>
                    
                    {{-- AUTH BUTTONS --}}
                    <div>
                        @if(Auth::guard('student')->check())
                            <div class="flex items-center gap-3">
                                <div class="hidden sm:block text-right">
                                    <p class="text-xs font-bold text-gray-900">{{ Auth::guard('student')->user()->name }}</p>
                                    <p class="text-[10px] text-gray-500">{{ Auth::guard('student')->user()->nim }}</p>
                                </div>
                                <a href="{{ route('student.loans.index') }}" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-100 border border-blue-200">
                                    Peminjaman Saya
                                </a>
                                <button @click="showPasswordModal = true" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-200 border border-gray-200">
                                    Ganti Pass
                                </button>
                                <form action="{{ route('student.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-200">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            <button @click="showLoginModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition active:scale-95">
                                Login Mahasiswa
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Search Bar --}}
                <form action="{{ route('public.catalog') }}" method="GET" class="relative">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}"
                           placeholder="Cari mikroskop, sensor, dll..." 
                           class="w-full bg-gray-100 border-none rounded-xl py-3 pl-11 pr-4 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-inner placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                </form>

                {{-- Horizontal Filter Chips --}}
                <div class="mt-3 flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    {{-- Chip: Semua --}}
                    <a href="{{ route('public.catalog', array_merge(request()->except(['category', 'page']), ['category' => null])) }}" 
                       class="shrink-0 px-4 py-1.5 rounded-full text-xs font-bold transition-all border
                       {{ !request('category') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-400' }}">
                       Semua
                    </a>

                    {{-- Chip: Tersedia (Status Filter Shortcut) --}}
                    <a href="{{ route('public.catalog', array_merge(request()->except(['status', 'page']), ['status' => request('status') == 'available' ? null : 'available'])) }}" 
                       class="shrink-0 px-4 py-1.5 rounded-full text-xs font-bold transition-all border flex items-center gap-1
                       {{ request('status') == 'available' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-700 border-green-200 hover:bg-green-50' }}">
                       <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                       Tersedia
                    </a>

                    {{-- Dynamic Categories --}}
                    @foreach($categories as $cat)
                        <a href="{{ route('public.catalog', array_merge(request()->except(['category', 'page']), ['category' => $cat->id])) }}" 
                           class="shrink-0 px-4 py-1.5 rounded-full text-xs font-bold transition-all border
                           {{ request('category') == $cat->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300' }}">
                           {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="pt-48 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen">
        
        {{-- Results Count & Greeting --}}
        <div class="mb-4 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
            <div>
                 @if(Auth::guard('student')->check())
                     <h2 class="text-lg font-bold text-gray-900">Halo, {{ Str::words(Auth::guard('student')->user()->name, 1, '') }}! 👋</h2>
                 @endif
                <p class="text-sm font-semibold text-gray-500">
                    Menampilkan {{ $items->count() }} dari {{ $items->total() }} barang
                </p>
            </div>
            
        </div>

        {{-- EMPTY STATE --}}
        @if($items->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-white p-6 rounded-full shadow-sm mb-4">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Barang tidak ditemukan</h3>
                <p class="text-gray-500 text-sm max-w-xs mx-auto">Coba cari dengan kata kunci lain atau pilih kategori berbeda.</p>
                <a href="{{ route('public.catalog') }}" class="mt-6 px-6 py-2 bg-gray-900 text-white text-sm font-bold rounded-full hover:bg-gray-800 transition">
                    Reset Filter
                </a>
            </div>
        @else
            {{-- GRID ITEMS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($items as $item)
                    {{-- ITEM CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300 relative group"
                         @click="showModal = true; activeItem = {{ Js::from($item) }}">
                        
                        {{-- Status Badge (Absolute Top Right) --}}
                        <div class="absolute top-3 right-3 z-10">
                            @if($item->status === 'available')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-green-500 text-white shadow-sm backdrop-blur-sm">
                                    Tersedia
                                </span>
                            @elseif($item->status === 'borrowed')
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-red-500 text-white shadow-sm">
                                    Dipinjam
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-gray-500 text-white shadow-sm">
                                    Maintenance
                                </span>
                            @endif
                        </div>

                        {{-- Image Area --}}
                        <div class="h-48 bg-gray-50 flex items-center justify-center overflow-hidden group-hover:bg-blue-50/50 transition-colors relative">
                            <img src="{{ $item->optimized_image }}" 
                                 alt="{{ $item->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/400x300?text=No+Image'">
                             
                             {{-- Optional: Overlay gradient for better text readability if we had text over image --}}
                             <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>

                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 text-base leading-tight mb-1 line-clamp-2">
                                {{ $item->name }}
                            </h3>
                            <div class="flex items-center text-xs text-gray-500 mb-4">
                                <span class="max-w-[150px] truncate block">{{ $item->brand ?? 'No Brand' }} {{ $item->type ? ' - ' . $item->type : '' }}</span>
                            </div>

                            <button class="w-full py-2.5 rounded-xl text-sm font-bold bg-gray-100 text-gray-700 hover:bg-black hover:text-white transition-all active:scale-95 flex items-center justify-center gap-2">
                                <span>Lihat Detail</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $items->onEachSide(1)->links() }} 
            </div>
        @endif

    </main>


    {{-- 1. ITEM DETAIL MODAL --}}
    <div x-show="showModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl transform transition-all"
             @click.away="showModal = false"
             x-show="showModal"
             x-transition.scale.origin.bottom>
            
            {{-- Modal Header --}}
            <div class="bg-gray-50 p-4 flex justify-between items-start border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 pr-4" x-text="activeItem?.name"></h3>
                <button @click="showModal = false" class="bg-gray-200 text-gray-500 rounded-full p-2 hover:bg-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 space-y-4">
                
                {{-- Status Large Badge --}}
                <div class="flex justify-center">
                   <template x-if="activeItem?.status === 'available'">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">
                             ✅ Tersedia untuk dipinjam
                        </span>
                   </template>
                   <template x-if="activeItem?.status === 'borrowed'">
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">
                             ⏳ Sedang dipinjam
                        </span>
                   </template>
                   <template x-if="activeItem?.status === 'maintenance' || activeItem?.status === 'broken'">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold border border-gray-200">
                             🔧 Dalam Perbaikan
                        </span>
                   </template>
                </div>

                {{-- Detail Table --}}
                <div class="text-sm border rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b flex justify-between">
                        <span class="text-gray-500">Merk / Brand</span>
                        <span class="font-semibold text-gray-900 text-right" x-text="activeItem?.brand || '-'"></span>
                    </div>
                    <div class="bg-white px-4 py-2 border-b flex justify-between">
                        <span class="text-gray-500">Tipe</span>
                        <span class="font-semibold text-gray-900 text-right" x-text="activeItem?.type || '-'"></span>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 flex justify-between items-center">
                        <span class="text-gray-500">Kode Serial</span>
                        <span class="font-mono bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs border border-yellow-200" x-text="activeItem?.serial_number || '-'"></span>
                    </div>
                </div>

                <p class="text-xs text-center text-gray-500 mt-2">
                    Tunjukkan kode serial kepada petugas lab saat ingin meminjam.
                </p>

                {{-- Action if Logged In --}}
                {{-- Action if Logged In --}}
                @if(Auth::guard('student')->check())
                    <div x-data="{ showLoanForm: false }">
                        <button x-show="!showLoanForm" @click="showLoanForm = true" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 mt-2 transition">
                            Ajukan Peminjaman
                        </button>

                        {{-- LOAN FORM --}}
                        <form x-show="showLoanForm" x-transition action="{{ route('student.loans.request') }}" method="POST" class="mt-4 space-y-3 bg-blue-50 p-4 rounded-xl border border-blue-100">
                            @csrf
                            <input type="hidden" name="item_id" :value="activeItem?.id">
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Pinjam</label>
                                <input type="date" name="borrow_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rencana Kembali</label>
                                <input type="date" name="return_date" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jumlah</label>
                                <input type="number" name="quantity" value="1" class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-500 text-sm cursor-not-allowed focus:ring-0 focus:border-gray-300" readonly>
                                <p class="text-[10px] text-gray-400 mt-1">*Maksimal 1 unit per peminjaman via web.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Keperluan</label>
                                <textarea name="purpose" rows="2" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Praktikum Modul 2" required></textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Penanggung Jawab / Pembimbing</label>
                                <input type="text" name="penanggung_jawab" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Dr. Eng. Widih atau Pak Aslab" required>
                            </div>

                            <div class="flex gap-2 pt-2">
                                <button type="button" @click="showLoanForm = false" class="flex-1 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50">Batal</button>
                                <button type="submit" class="flex-1 py-2 bg-blue-600 rounded-lg text-xs font-bold text-white hover:bg-blue-700 shadow-md">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                @else
                    <button @click="showModal = false; showLoginModal = true" class="w-full py-3 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 mt-2 transition">
                        Login untuk Meminjam
                    </button>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="p-4 border-t border-gray-100 bg-gray-50 flex gap-2">
                <button type="button" @click="showModal = false" class="flex-1 py-3 rounded-xl font-bold bg-white border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- 2. LOGIN MODAL --}}
    <div x-show="showLoginModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl transform transition-all"
             @click.away="showLoginModal = false"
             x-show="showLoginModal"
             x-transition.scale.origin.bottom>
            
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Login Mahasiswa</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk untuk mulai meminjam alat.</p>
                </div>

                <form action="{{ route('student.login') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="nama@mahasiswa.itb.ac.id">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIM (Sebagai Password)</label>
                        <input type="text" name="nim" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" placeholder="1322XXXX">
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-500">Belum punya akun?</span>
                    <button @click="showLoginModal = false; showRegisterModal = true" class="font-bold text-blue-600 hover:text-blue-800">Daftar sekarang</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. REGISTER MODAL --}}
    <div x-show="showRegisterModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all max-h-[90vh] overflow-y-auto custom-scrollbar"
             @click.away="showRegisterModal = false"
             x-show="showRegisterModal"
             x-transition.scale.origin.bottom>
            
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Akun</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data diri Anda dengan benar.</p>
                </div>

                <form action="{{ route('student.register') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Budi Santoso">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NIM</label>
                            <input type="text" name="nim" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="132xxxxx">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kelas / Prodi</label>
                            <input type="text" name="class" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="K01 / EL">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Email</label>
                        <input type="email" name="email" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="email@itb.ac.id">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="0812...">
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-green-600 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 hover:bg-green-700 transition mt-2">
                        Daftar & Masuk
                    </button>
                </form>

                <div class="mt-6 text-center text-sm">
                    <span class="text-gray-500">Sudah punya akun?</span>
                    <button @click="showRegisterModal = false; showLoginModal = true" class="font-bold text-blue-600 hover:text-blue-800">Login disini</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. CHANGE PASSWORD MODAL --}}
    <div x-show="showPasswordModal" 
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/60 backdrop-blur-sm"
         x-transition.opacity>
        
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl transform transition-all"
             @click.away="showPasswordModal = false"
             x-show="showPasswordModal"
             x-transition.scale.origin.bottom>
            
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Ganti Password</h2>
                    <p class="text-sm text-gray-500 mt-1">Ubah password akun Anda.</p>
                </div>

                <form action="{{ route('student.password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('current_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        <p class="text-[10px] text-gray-400 mt-1">*Jika belum pernah diubah, gunakan NIM.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Baru</label>
                        <input type="password" name="new_password" required minlength="6" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        @error('new_password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" required minlength="6" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition">
                        Simpan Password
                    </button>
                    <button type="button" @click="showPasswordModal = false" class="w-full py-2 text-gray-500 text-sm font-bold hover:text-gray-700 transition">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>


</body>
</html>
