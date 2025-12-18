<!-- Navigation Bar -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 fixed top-0 right-0 left-0 z-40">
    <div class="px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Left: Breadcrumb or Page Title (optional space) -->
            <div class="flex items-center gap-4">
                <!-- You can add breadcrumbs here if needed -->
            </div>

            <!-- Right: Search & User Actions -->
            <div class="flex items-center gap-4">
                
                <!-- Search Bar (Optional) -->
                <div class="hidden md:block">
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Search..."
                            class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <!-- Notification Badge -->
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Dropdown (Desktop) -->
                <div class="hidden sm:block">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button 
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 hover:bg-gray-50 border border-gray-200 transition-colors duration-200">
                                
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-medium text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                
                                <div class="text-left">
                                    <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                </div>

                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                    <span>Profil</span>
                                </div>
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="log-out" class="w-4 h-4 text-gray-500"></i>
                                        <span>Log Out</span>
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobile Menu Toggle -->
                <button 
                    @click="open = !open"
                    class="sm:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors duration-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="sm:hidden border-t border-gray-200 bg-white shadow-lg">
        
        <!-- Mobile Search -->
        <div class="px-4 py-3 border-b border-gray-100">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Search..."
                    class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute left-3 top-1/2 -translate-y-1/2">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="px-4 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900 text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Links -->
        <div class="py-2">
            <x-responsive-nav-link :href="route('profile.edit')">
                <div class="flex items-center gap-3">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Profil</span>
                </div>
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="flex items-center gap-3">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Log Out</span>
                    </div>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navbar -->
<div class="h-16"></div>