<!-- Sidebar Container with Alpine.js toggle -->
<div x-data="{ sidebarOpen: true }" class="fixed left-0 top-0 h-screen z-50 p-4">
    
    <!-- Floating Sidebar -->
    <div :class="sidebarOpen ? 'w-64' : 'w-20'" 
         class="h-full bg-white rounded-2xl shadow-md border border-gray-200 flex flex-col transition-all duration-300 ease-in-out">

        <!-- Header Section -->
        <div class="flex items-center justify-between px-4 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-semibold text-lg flex-shrink-0">
                    S
                </div>
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="min-w-0">
                    <h1 class="text-base font-bold text-gray-900 truncate">STEIKA</h1>
                    <p class="text-xs text-gray-500 truncate">Biomedis Inventory</p>
                </div>
            </div>
            
            <!-- Toggle Button -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="flex-shrink-0 w-7 h-7 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors duration-200">
                <i :data-lucide="sidebarOpen ? 'chevron-left' : 'chevron-right'" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

            @php
                function activeLink($route) {
                    return request()->is($route.'*') ? 
                        'bg-blue-50 text-blue-600' : 
                        'text-gray-700 hover:bg-gray-50';
                }
            @endphp

            <!-- Dashboard -->
            <a href="/dashboard" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('dashboard') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    @if (request()->is('dashboard*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Dashboard</span>
            </a>

            <!-- Items -->
            <a href="/items" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('items') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    @if (request()->is('items*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Data Induk Barang</span>
            </a>

            <!-- Rooms -->
            <a href="/rooms" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('rooms') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="door-open" class="w-5 h-5"></i>
                    @if (request()->is('rooms*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Data Ruangan</span>
            </a>

            <!-- Users -->
            <a href="/peminjam-users" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('peminjam-users') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    @if (request()->is('peminjam-users*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Data Peminjam</span>
            </a>

            <!-- Materials -->
            <a href="/materials" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('materials') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="box" class="w-5 h-5"></i>
                    @if (request()->is('materials*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Data Material</span>
            </a>

            <!-- Section Divider -->
            <div x-show="sidebarOpen" 
                 x-transition
                 class="pt-4 pb-2">
                <div class="flex items-center gap-2 px-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Peminjaman</p>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>
            </div>

            <!-- Divider when collapsed -->
            <div x-show="!sidebarOpen" 
                 x-transition
                 class="py-2">
                <div class="h-px bg-gray-200 mx-2"></div>
            </div>

            <!-- Borrowings -->
            <a href="/borrowings" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                    @if (request()->is('borrowings*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Peminjaman Cepat</span>
            </a>

            <!-- Room Borrowings -->
            <a href="/room_borrowings" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('room_borrowings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                    @if (request()->is('room_borrowings*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Peminjaman Ruangan</span>
            </a>

            <!-- 3D Print -->
            <a href="/prints" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('prints') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    @if (request()->is('prints*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">3D Print</span>
            </a>

            <!-- Section Divider -->
            <div x-show="sidebarOpen" 
                 x-transition
                 class="pt-4 pb-2">
                <div class="flex items-center gap-2 px-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Manajemen</p>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>
            </div>

            <div x-show="!sidebarOpen" 
                 x-transition
                 class="py-2">
                <div class="h-px bg-gray-200 mx-2"></div>
            </div>

            <!-- Suppliers -->
            <a href="/suppliers" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('suppliers') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="truck" class="w-5 h-5"></i>
                    @if (request()->is('suppliers*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Supplier</span>
            </a>

            <!-- Categories -->
            <a href="/categories" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('categories') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="tags" class="w-5 h-5"></i>
                    @if (request()->is('categories*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Kategori</span>
            </a>

            <!-- Locations -->
            <a href="/locations" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('locations') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                    @if (request()->is('locations*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Lokasi Barang</span>
            </a>

            <!-- Fundings -->
            <a href="/fundings" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('fundings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                    @if (request()->is('fundings*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Sumber Dana</span>
            </a>

            <!-- Section Divider -->
            <div x-show="sidebarOpen" 
                 x-transition
                 class="pt-4 pb-2">
                <div class="flex items-center gap-2 px-3">
                    <div class="h-px flex-1 bg-gray-200"></div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Pengaturan</p>
                    <div class="h-px flex-1 bg-gray-200"></div>
                </div>
            </div>

            <div x-show="!sidebarOpen" 
                 x-transition
                 class="py-2">
                <div class="h-px bg-gray-200 mx-2"></div>
            </div>

            <!-- Settings -->
            <a href="/settings" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('settings') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    @if (request()->is('settings*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Settings</span>
            </a>

            <!-- Profile -->
            <a href="/profile" 
               :class="sidebarOpen ? 'justify-start' : 'justify-center'"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ activeLink('profile') }}">
                <div class="relative flex-shrink-0">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    @if (request()->is('profile*'))
                        <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-blue-600 rounded-full"></span>
                    @endif
                </div>
                <span x-show="sidebarOpen" 
                      x-transition
                      class="text-sm font-medium truncate">Profile</span>
            </a>

        </nav>

        <!-- Footer User Info -->
        <div class="px-3 py-4 border-t border-gray-100">
            <div :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                 class="flex items-center gap-3 px-3 py-2.5 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div x-show="sidebarOpen" 
                         x-transition
                         class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-gray-900 truncate">Admin User</p>
                        <p class="text-xs text-gray-500">Online</p>
                    </div>
                </div>
                <div x-show="sidebarOpen" 
                     x-transition
                     class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0"></div>
            </div>
        </div>

    </div>
</div>

<!-- Main Content Wrapper (adjust margin based on sidebar state) -->
<!-- Add this to your main content container: -->
<!-- <div :class="sidebarOpen ? 'ml-72' : 'ml-28'" class="transition-all duration-300"> -->
<!--     Your main content here -->
<!-- </div> -->