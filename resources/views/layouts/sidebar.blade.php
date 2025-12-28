<div x-data="{ sidebarOpen: true }" class="fixed left-0 top-0 h-screen z-50 p-4">
    
    <div :class="sidebarOpen ? 'w-64' : 'w-20'" 
         class="h-full bg-white rounded-2xl shadow-xl border border-gray-200 flex flex-col transition-all duration-300 ease-in-out font-sans">

        <div class="flex items-center justify-between px-4 py-6 border-b border-gray-100">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-lg flex-shrink-0 shadow-md">
                    S
                </div>
                <div x-show="sidebarOpen" x-transition class="min-w-0">
                    <h1 class="text-base font-bold text-gray-900 truncate tracking-tight">STEIKA</h1>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-semibold truncate">Biomedis Inventory</p>
                </div>
            </div>
            
            <button @click="sidebarOpen = !sidebarOpen" class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                <i :data-lucide="sidebarOpen ? 'chevron-left' : 'chevron-right'" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 custom-scrollbar">

           @php
                if (!function_exists('activeLink')) {
                    function activeLink($route) {
                        return request()->is($route.'*') ? 
                            'bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100' : 
                            'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
                    }
                }
            @endphp

            <a href="/dashboard" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 mb-4 {{ activeLink('dashboard') }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span x-show="sidebarOpen" class="text-sm font-semibold">Dashboard</span>
            </a>

            <div x-show="sidebarOpen" x-transition class="px-3 pt-2 pb-2">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Inventory</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px bg-gray-100 mx-2 my-2"></div>

            <a href="/items" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('items') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    @if (request()->is('items*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Barang</span>
            </a>

            <a href="/rooms" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('rooms') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="door-open" class="w-5 h-5"></i>
                    @if (request()->is('rooms*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Ruangan</span>
            </a>

            <a href="/materials" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('materials') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="container" class="w-5 h-5"></i>
                    @if (request()->is('materials*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Stok Material</span>
            </a>

            <a href="/printers" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('printers') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    @if (request()->is('printers*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Mesin 3D</span>
            </a>


            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Layanan</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px bg-gray-100 mx-2 my-2"></div>

            <a href="/borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                    @if (request()->is('borrowings*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Pinjam Alat</span>
            </a>

            <a href="/room_borrowings" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('room_borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                    @if (request()->is('room_borrowings*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Booking Ruangan</span>
            </a>

            <a href="/prints" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('prints') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                    @if (request()->is('prints*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span> @endif
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Request Print</span>
            </a>


            <div x-show="sidebarOpen" x-transition class="px-3 pt-4 pb-2">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Admin Area</p>
            </div>
            <div x-show="!sidebarOpen" class="h-px bg-gray-100 mx-2 my-2"></div>

            {{-- MENU KHUSUS SUPER ADMIN --}}
            @if(auth()->check() && auth()->user()->role === 'superadmin')
                
                {{-- Kelola Users --}}
                <a href="{{ route('superadmin.users.index') }}" 
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('superadmin/users') }}">
                    <div class="relative flex-shrink-0">
                        <i data-lucide="shield-check" class="w-5 h-5 text-indigo-600"></i>
                        @if (request()->is('superadmin/users*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-indigo-600 rounded-full"></span> @endif
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Kelola Admin</span>
                </a>

                {{-- AUDIT LOGS (YANG DIMINTA) --}}
                <a href="{{ route('superadmin.logs.index') }}" 
                   :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('superadmin/logs') }}">
                    <div class="relative flex-shrink-0">
                        <i data-lucide="file-clock" class="w-5 h-5 text-rose-600"></i>
                        @if (request()->is('superadmin/logs*')) <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-rose-600 rounded-full"></span> @endif
                    </div>
                    <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Audit Log</span>
                </a>

            @endif

            <a href="/peminjam-users" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ activeLink('peminjam-users') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span x-show="sidebarOpen" x-transition class="text-sm font-medium truncate">Data Peminjam</span>
            </a>

            <div x-data="{ expanded: false }">
                <button @click="expanded = !expanded" 
                        x-show="sidebarOpen"
                        class="flex items-center justify-between w-full gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl transition-all duration-200">
                    <div class="flex items-center gap-3">
                        <i data-lucide="database" class="w-5 h-5"></i>
                        <span class="text-sm font-medium">Master Data</span>
                    </div>
                    <i :data-lucide="expanded ? 'chevron-down' : 'chevron-right'" class="w-4 h-4"></i>
                </button>

                <a href="#" x-show="!sidebarOpen" class="flex justify-center px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl">
                    <i data-lucide="database" class="w-5 h-5"></i>
                </a>

                <div x-show="expanded && sidebarOpen" x-collapse class="pl-4 space-y-1 mt-1">
                    <a href="/suppliers" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-500 hover:text-blue-600 rounded-lg">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Supplier
                    </a>
                    <a href="/categories" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-500 hover:text-blue-600 rounded-lg">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Kategori
                    </a>
                    <a href="/locations" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-500 hover:text-blue-600 rounded-lg">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Lokasi
                    </a>
                    <a href="/fundings" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-500 hover:text-blue-600 rounded-lg">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span> Sumber Dana
                    </a>
                </div>
            </div>

        </nav>

        <div class="px-3 py-4 border-t border-gray-100">
            <div :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                 class="flex items-center gap-3 px-3 py-2.5 bg-gray-50 rounded-xl border border-gray-100">
                
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" x-transition class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wide font-medium">{{ auth()->user()->role }}</p>
                    </div>
                </div>

                {{-- Logout / Settings Button Small --}}
                <div x-show="sidebarOpen" x-transition class="flex-shrink-0">
                    <a href="/profile" class="text-gray-400 hover:text-blue-600 transition">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>