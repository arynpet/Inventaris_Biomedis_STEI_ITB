<div class="w-64 bg-gradient-to-b from-white to-gray-50 border-r border-gray-200 h-screen fixed left-0 top-0 pt-6 shadow-xl flex flex-col">

    <!-- HEADER LOGO -->
    <div class="flex items-center gap-3 px-6 pb-5 border-b border-gray-200 mb-2">
        <div class="w-12 h-12 bg-gradient-to-br from-[#00afef] to-[#0088cc] text-white rounded-2xl flex items-center justify-center font-bold text-xl shadow-lg shadow-[#00afef]/30 ring-2 ring-[#00afef]/20">
            S
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">STEIKA</h1>
            <p class="text-xs text-gray-500 font-medium">Biomedis Inventory</p>
        </div>
    </div>

    <nav class="px-3 pt-2 space-y-1 flex-1 overflow-y-auto">

        <!-- Example: Active detection -->
        @php
            function activeLink($route) {
                return request()->is($route.'*') ? 
                    'bg-gradient-to-r from-[#00afef]/10 to-[#00afef]/5 text-[#00afef] font-semibold shadow-sm' : 
                    'text-gray-700 hover:bg-gray-100/80';
            }
        @endphp


        <!-- Dashboard -->
        <a href="/dashboard" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('dashboard') }}">
            
            @if (request()->is('dashboard*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="home" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('dashboard*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('dashboard*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Dashboard</span>
        </a>


        <!-- Items -->
        <a href="/items" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('items') }}">
            
            @if (request()->is('items*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="package" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                    {{ request()->is('items*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('items*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Data Induk Barang</span>
        </a>


        <!-- Rooms -->
        <a href="/rooms" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('rooms') }}">
            
            @if (request()->is('rooms*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="door-open" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('rooms*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('rooms*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Data Ruangan</span>
        </a>

                <!-- Users -->
        <a href="/peminjam-users" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('peminjam-users') }}">
            
            @if (request()->is('peminjam-users*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="user" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('peminjam-users*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('peminjam-users*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Data Peminjam</span>
        </a>

                        <!-- Bahan -->
        <a href="/materials" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('materials') }}">
            
            @if (request()->is('material*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="user" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('materials*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('materials*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Data Material</span>
        </a>

        


        <!-- SECTION -->
        <div class="pt-4 pb-2">
            <div class="flex items-center gap-2 px-4">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Peminjaman</p>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>
        </div>


        <!-- Borrowings -->
        <a href="/borrowings" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('borrowings') }}">
            
            @if (request()->is('borrowings*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif
            
            <div class="relative">
                <i data-lucide="zap" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('borrowings*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('borrowings*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Peminjaman Cepat</span>
        </a>


        <!-- Room Borrowings -->
        <a href="/room_borrowings" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('room_borrowings') }}">
            
            @if (request()->is('room_borrowings*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="camera" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('room_borrowings*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('room_borrowings*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Peminjaman Ruangan</span>
        </a>

                <a href="/prints" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('prints') }}">
            
            @if (request()->is('printers*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif
            
            <div class="relative">
                <i data-lucide="zap" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('prints*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('prints*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">3D Print</span>
        </a>


        <!-- SECTION -->
        <div class="pt-4 pb-2">
            <div class="flex items-center gap-2 px-4">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Manajemen</p>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>
        </div>


        <a href="/suppliers" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('suppliers') }}">
            
            @if (request()->is('suppliers*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="truck" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('suppliers*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('suppliers*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Supplier</span>
        </a>


        <a href="/categories" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('categories') }}">
            
            @if (request()->is('categories*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="tags" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('categories*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('categories*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Kategori</span>
        </a>


        <a href="/locations" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('locations') }}">
            
            @if (request()->is('locations*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="map-pin" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('locations*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('locations*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Lokasi Barang</span>
        </a>


        <a href="/fundings" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('fundings') }}">
            
            @if (request()->is('fundings*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="wallet" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('fundings*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('fundings*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Sumber Dana</span>
        </a>


        <!-- SECTION -->
        <div class="pt-4 pb-2">
            <div class="flex items-center gap-2 px-4">
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Pengaturan</p>
                <div class="h-px flex-1 bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>
        </div>

        <a href="/settings" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('settings') }}">
            
            @if (request()->is('settings*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="settings" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('settings*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('settings*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Settings</span>
        </a>


        <a href="/profile" 
           class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:translate-x-1 relative {{ activeLink('profile') }}">
            
            @if (request()->is('profile*'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-1 bg-gradient-to-b from-[#00afef] to-[#0088cc] rounded-r shadow-md"></span>
            @endif

            <div class="relative">
                <i data-lucide="user" class="w-5 h-5 transition-all duration-200 group-hover:scale-110
                   {{ request()->is('profile*') ? 'text-[#00afef]' : '' }}"></i>
                @if (request()->is('profile*'))
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-[#00afef] rounded-full"></span>
                @endif
            </div>
            <span class="text-sm">Profile</span>
        </a>

    </nav>

    <!-- Footer (Optional) -->
    <div class="px-4 py-4 border-t border-gray-200 bg-gray-50/50">
        <div class="flex items-center gap-3 px-3 py-2 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="w-8 h-8 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                <i data-lucide="user" class="w-4 h-4 text-gray-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-800 truncate">Admin User</p>
                <p class="text-xs text-gray-500">Online</p>
            </div>
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
        </div>
    </div>

</div>