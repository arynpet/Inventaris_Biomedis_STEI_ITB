@php
    // DETEKSI APAKAH SEDANG DI DASHBOARD (NARA MODE)
    // $isDash tidak lagi mempengaruhi style via PHP, tapi via CSS Class 'nara-mode' di app.blade.php
    $isDash = request()->routeIs('dashboard');

    // --- VARIABEL TEMA STATIC (CLEAN / WHITE MODE) ---
    // Style "Nara Mode" (Hitam/Cyan) sekarang dihandle via CSS Overrides di app.blade.php

    // 1. Container Utama
    $sidebarClass = 'bg-white border-r border-gray-200 text-gray-900 sidebar-container';

    // 2. Logo Box
    $logoBoxClass = 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md';

    // 3. Divider Line
    $dividerClass = 'bg-gray-100';

    // 4. Section Title
    $sectionTitleClass = 'text-gray-400';

    // 5. User Profile Card Bottom
    $userCardClass = 'bg-gray-50 border-gray-100 text-gray-900';

    // 6. User Avatar
    $avatarClass = 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white';

    // --- FUNGSI ACTIVE LINK DINAMIS ---
    if (!function_exists('dynamicActive')) {
        function dynamicActive($route)
        {
            $isActive = request()->is($route . '*');

            // Default Clean Style
            $base = 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
            $active = 'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100 active-link';

            return $isActive ? $active : $base;
        }
    }
@endphp

<div class="fixed left-0 top-0 h-screen z-50 p-4 font-sans">

    <div :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="h-full rounded-2xl shadow-2xl flex flex-col transition-all duration-300 ease-in-out {{ $sidebarClass }}">

        <div
            class="flex items-center justify-between px-4 py-6 border-b {{ $isDash ? 'border-cyan-500/30' : 'border-gray-100' }}">
            <div class="flex items-center gap-3 overflow-hidden">
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg flex-shrink-0 {{ $logoBoxClass }}">
                    @if($isDash) <i class="fas fa-atom animate-spin-slow text-sm"></i> @else S @endif
                </div>
                <div x-show="sidebarOpen" x-transition class="min-w-0">
                    <h1
                        class="text-base font-bold truncate tracking-tight {{ $isDash ? 'text-cyan-100 font-mono' : 'text-gray-900' }}">
                        Biomedis
                    </h1>
                    <p
                        class="text-[10px] uppercase tracking-wider font-semibold truncate {{ $isDash ? 'text-cyan-600' : 'text-gray-500' }}">
                        Biomedis Inventory
                    </p>
                </div>
            </div>

            <button @click="sidebarOpen = !sidebarOpen"
                class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $isDash ? 'hover:bg-cyan-900/30 text-cyan-500' : 'hover:bg-gray-100 text-gray-500' }}">
                <i class="fa-solid" :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar">

            <a href="/dashboard" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mb-4 {{ dynamicActive('dashboard') }}">
                <i class="fa-solid fa-tachometer-alt w-5 h-5 flex items-center justify-center"></i>
                <span x-show="sidebarOpen" class="text-sm font-semibold">Dashboard</span>
            </a>

            <a href="/gamification" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mb-4 {{ dynamicActive('gamification') }}">
                <i class="fa-solid fa-trophy w-5 h-5 flex items-center justify-center text-yellow-500"></i>
                <span x-show="sidebarOpen" class="text-sm font-semibold">Leaderboard</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-2 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Inventory</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            <a href="/items" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('items') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-box w-5 h-5 flex items-center justify-center"></i>
                    @if (request()->is('items*')) <span
                        class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Barang</span>
            </a>

            <a href="/rooms" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('rooms') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-door-open w-5 h-5 flex items-center justify-center"></i>
                    @if (request()->is('rooms*')) <span
                        class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Ruangan</span>
            </a>

            <a href="/materials" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('materials') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-boxes-stacked w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Stok Material</span>
            </a>

            <a href="/categories" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('categories') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-tags w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Kategori</span>
            </a>

            <a href="/printers" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('printers') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-print w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Mesin 3D</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Layanan</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            <a href="/borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-bolt w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Pinjam Alat</span>
            </a>

            <a href="/room_borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('room_borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-calendar-check w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Booking Ruangan</span>
            </a>

            <a href="/prints" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('prints') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-layer-group w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Request Print</span>
            </a>

            @if(auth()->check() && auth()->user()->is_dev_mode)
                <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-red-500">Developer</p>
                </div>
                <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

                <a href="{{ route('dev.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('dev/dashboard') }}">
                    <div class="relative flex-shrink-0">
                        <i class="fa-solid fa-code w-5 h-5 flex items-center justify-center text-red-500"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate text-red-600">Dev
                        Dashboard</span>
                </a>
            @endif

            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Admin Area</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            @if(auth()->check() && auth()->user()->isSuperAdmin())
                <a href="{{ route('superadmin.users.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('superadmin/users') }}">
                    <div class="relative flex-shrink-0">
                        <i class="fa-solid fa-user-shield w-5 h-5 flex items-center justify-center text-indigo-600"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Kelola Admin</span>
                </a>

                <a href="{{ route('superadmin.logs.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('superadmin/logs') }}">
                    <div class="relative flex-shrink-0">
                        <i class="fa-solid fa-chart-line w-5 h-5 flex items-center justify-center text-amber-600"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Activity Logs</span>
                </a>

                <a href="{{ route('superadmin.backup.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('superadmin/backup') }}">
                    <div class="relative flex-shrink-0">
                        <i class="fa-solid fa-database w-5 h-5 flex items-center justify-center text-emerald-600"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Backup Data</span>
                </a>
            @endif

            <a href="/peminjam-users" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('peminjam-users') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-users w-5 h-5 flex items-center justify-center"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Peminjam</span>
            </a>

            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" x-show="sidebarOpen"
                    class="flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-database w-5 h-5 flex items-center justify-center"></i>
                        <span class="text-sm font-medium">Master Data</span>
                    </div>
                    <i class="fa-solid" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                </button>

                <a href="#" x-show="!sidebarOpen"
                    class="flex justify-center px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-database w-5 h-5 flex items-center justify-center"></i>
                </a>

                <div x-show="expanded && sidebarOpen" x-collapse class="pl-4 space-y-1 mt-1">
                    @php
                        $subLinkClass = 'text-gray-500 hover:text-blue-600';
                        $dotClass = 'bg-gray-300';
                    @endphp

                    <a href="/suppliers"
                        class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Supplier
                    </a>
                    <a href="/locations"
                        class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Lokasi
                    </a>
                    <a href="/fundings"
                        class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ $subLinkClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> Sumber Dana
                    </a>
                </div>
            </div>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] uppercase tracking-widest font-bold {{ $sectionTitleClass }}">Bantuan</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px mx-2 my-2 {{ $dividerClass }}"></div>

            <a href="{{ route('guide.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ dynamicActive('tutorial') }}">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-book-open w-5 h-5 flex items-center justify-center text-pink-600"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Panduan Sistem</span>
            </a>

            <a href="{{ route('public.catalog') }}" target="_blank"
                :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                <div class="relative flex-shrink-0">
                    <i class="fa-solid fa-globe w-5 h-5 flex items-center justify-center text-green-600"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Katalog Publik</span>
                <i x-show="sidebarOpen" class="fa-solid fa-external-link-alt w-3 h-3 ml-auto opacity-50"></i>
            </a>

        </nav>

        <div class="px-3 py-4 border-t {{ $isDash ? 'border-cyan-500/30' : 'border-gray-100' }}">
            <div :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl border {{ $userCardClass }}">

                <div class="flex items-center gap-3 min-w-0">
                    <div
                        class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm font-bold {{ $avatarClass }}">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition class="min-w-0 flex-1">
                        <p class="text-xs font-bold truncate {{ $isDash ? 'text-cyan-100' : 'text-gray-900' }}">
                            {{ auth()->user()->name }}
                        </p>
                        <p
                            class="text-[10px] uppercase tracking-wide font-medium {{ $isDash ? 'text-cyan-600' : 'text-gray-500' }}">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                </div>

                <div x-show="sidebarOpen" x-transition class="flex-shrink-0">
                    <a href="/profile"
                        class="transition {{ $isDash ? 'text-gray-500 hover:text-cyan-400' : 'text-gray-400 hover:text-blue-600' }}">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>