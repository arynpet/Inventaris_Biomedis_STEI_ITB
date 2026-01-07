@php
    // DETEKSI APAKAH SEDANG DI DASHBOARD (NARA MODE)
    $isDash = request()->routeIs('dashboard');

    // --- VARIABEL TEMA (HITAM/CYAN vs PUTIH/BIRU) ---
    
    // 1. Container Utama
    $sidebarClass = $isDash 
        ? 'nara-glass border-r border-cyan-500/30 text-cyan-100' 
        : 'bg-white border-r border-gray-200 text-gray-900';

    // 2. Logo Box
    $logoBoxClass = $isDash
        ? 'bg-cyan-950 border border-cyan-500 text-cyan-400 shadow-[0_0_15px_rgba(0,243,255,0.4)]'
        : 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md';

    // 3. Divider Line
    $dividerClass = $isDash ? 'bg-cyan-900/50' : 'bg-gray-100';

    // 4. Section Title (Inventory, Layanan, Admin)
    $sectionTitleClass = $isDash ? 'text-cyan-600' : 'text-gray-400';

    // 5. User Profile Card Bottom
    $userCardClass = $isDash
        ? 'bg-gray-900/50 border-cyan-500/30 text-cyan-100'
        : 'bg-gray-50 border-gray-100 text-gray-900';

    // 6. User Avatar
    $avatarClass = $isDash
        ? 'bg-cyan-900 text-cyan-400 border border-cyan-600'
        : 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white';

    // --- FUNGSI ACTIVE LINK DINAMIS ---
    if (!function_exists('dynamicActive')) {
        function dynamicActive($route, $isDash) {
            $isActive = request()->is($route.'*');
            
            if ($isDash) {
                // Style Active untuk Dashboard (NEON GLOW)
                return $isActive 
                    ? 'bg-cyan-900/40 text-cyan-300 border-l-2 border-cyan-400 shadow-[0_0_15px_rgba(0,243,255,0.15)]' 
                    : 'text-gray-400 hover:bg-cyan-900/20 hover:text-cyan-200';
            } else {
                // Style Active untuk Halaman Biasa (NORMAL BLUE)
                return $isActive 
                    ? 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' 
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
            }
        }
    }
@endphp

<div x-data="{ sidebarOpen: true }" class="fixed left-0 top-0 h-screen z-50 p-4 font-sans">
    
    <div :class="sidebarOpen ? 'w-64' : 'w-20'" 
         class="h-full rounded-2xl shadow-2xl flex flex-col transition-all duration-300 ease-in-out {{ $sidebarClass }}">

        <div class="flex items-center justify-between px-4 py-6 border-b {{ $isDash ? 'border-cyan-500/30' : 'border-gray-100' }}">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg flex-shrink-0 {{ $logoBoxClass }}">
                    @if($isDash) <i class="fas fa-atom animate-spin-slow text-sm"></i> @else S @endif
                </div>
                <div x-show="sidebarOpen" x-transition class="min-w-0">
                    <h1 class="text-base font-bold truncate tracking-tight {{ $isDash ? 'text-cyan-100 font-mono' : 'text-gray-900' }}">
                        STEIKA
                    </h1>
                    <p class="text-[10px] uppercase tracking-wider font-semibold truncate {{ $isDash ? 'text-cyan-600' : 'text-gray-500' }}">
                        Biomedis Inventory
                    </p>
                </div>
            </div>
            
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $isDash ? 'hover:bg-cyan-900/30 text-cyan-500' : 'hover:bg-gray-100 text-gray-500' }}">
                <i :data-lucide="sidebarOpen ? 'chevron-left' : 'chevron-right'" class="w-4 h-4"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar">

            <a href="/dashboard" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mb-4 {{ dynamicActive('dashboard', $isDash) }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span x-show="sidebarOpen" class="text-sm font-semibold">Dashboard</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-2 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Inventory</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            <a href="/items" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('items', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    @if (request()->is('items*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 {{ $isDash ? 'bg-cyan-400 shadow-[0_0_5px_#00f3ff]' : 'bg-blue-600' }} rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Barang</span>
            </a>

            <a href="/rooms" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('rooms', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="door-open" class="w-5 h-5"></i>
                    @if (request()->is('rooms*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 {{ $isDash ? 'bg-cyan-400' : 'bg-blue-600' }} rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Ruangan</span>
            </a>

            <a href="/materials" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('materials', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="container" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Stok Material</span>
            </a>

            <a href="/printers" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('printers', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Mesin 3D</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Layanan</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            <a href="/borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('borrowings', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Pinjam Alat</span>
            </a>

            <a href="/room_borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('room_borrowings', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Booking Ruangan</span>
            </a>

            <a href="/prints" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('prints', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Request Print</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Admin Area</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            @if(auth()->check() && auth()->user()->role === 'superadmin')
                <a href="{{ route('superadmin.users.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('superadmin/users', $isDash) }}">
                    <div class="relative flex-shrink-0">
                        <i data-lucide="shield-check" class="w-5 h-5 {{ $isDash ? 'text-indigo-400' : 'text-indigo-600' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Kelola Admin</span>
                </a>

                <a href="{{ route('superadmin.logs.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('superadmin/logs', $isDash) }}">
                    <div class="relative flex-shrink-0">
                        <i data-lucide="file-clock" class="w-5 h-5 {{ $isDash ? 'text-rose-400' : 'text-rose-600' }}"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Audit Log</span>
                </a>
            @endif

            <a href="/peminjam-users" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('peminjam-users', $isDash) }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Peminjam</span>
            </a>

            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" 
                        x-show="sidebarOpen"
                        class="flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 {{ $isDash ? 'text-gray-400 hover:bg-cyan-900/20 hover:text-cyan-200' : 'text-gray-600 hover:bg-gray-50' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="database" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Master Data</span>
                    </div>
                    <i :data-lucide="expanded ? 'chevron-down' : 'chevron-right'" class="w-4 h-4"></i>
                </button>

                <a href="#" x-show="!sidebarOpen" class="flex justify-center px-3 py-2.5 rounded-xl {{ $isDash ? 'text-gray-400 hover:bg-cyan-900/20' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </a>

                <div x-show="expanded && sidebarOpen" x-collapse class="pl-4 space-y-1 mt-1">
                    @php
                        $subLinkClass = $isDash 
                            ? 'text-gray-500 hover:text-cyan-400 hover:bg-cyan-900/10' 
                            : 'text-gray-500 hover:text-blue-600';
                        $dotClass = $isDash ? 'bg-gray-600' : 'bg-gray-300';
                    @endphp

                    <a href="/suppliers" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Supplier
                    </a>
                    <a href="/categories" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Kategori
                    </a>
                    <a href="/locations" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Lokasi
                    </a>
                    <a href="/fundings" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Sumber Dana
                    </a>
                </div>
            </div>

        </nav>

        <div class="px-3 py-4 border-t {{ $isDash ? 'border-cyan-500/30' : 'border-gray-100' }}">
            <div :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                 class="flex items-center gap-3 px-3 py-2.5 rounded-xl border {{ $userCardClass }}">
                
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm font-bold {{ $avatarClass }}">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition class="min-w-0 flex-1">
                        <p class="text-xs font-bold truncate {{ $isDash ? 'text-cyan-100' : 'text-gray-900' }}">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] uppercase tracking-wide font-medium {{ $isDash ? 'text-cyan-600' : 'text-gray-500' }}">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>

                <div x-show="sidebarOpen" x-transition class="flex-shrink-0">
                    <a href="/profile" class="transition {{ $isDash ? 'text-gray-500 hover:text-cyan-400' : 'text-gray-400 hover:text-blue-600' }}">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>