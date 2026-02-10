<x-app-layout>
    <div class="min-h-screen bg-gray-50/50">
        @php
            $badges = [
                ['name' => 'Newbie', 'icon' => 'fa-user', 'color' => 'gray', 'desc' => 'Joined the system'],
                ['name' => 'Rookie', 'icon' => 'fa-seedling', 'color' => 'green', 'desc' => 'Reach Level 5'],
                ['name' => 'Veteran', 'icon' => 'fa-shield-halved', 'color' => 'blue', 'desc' => 'Reach Level 10'],
                ['name' => 'Elite', 'icon' => 'fa-gem', 'color' => 'indigo', 'desc' => 'Reach Level 20'],
                ['name' => 'Master', 'icon' => 'fa-crown', 'color' => 'yellow', 'desc' => 'Reach Level 30'],
                ['name' => 'Legend', 'icon' => 'fa-dragon', 'color' => 'red', 'desc' => 'Reach Level 50'],
                ['name' => 'Builder', 'icon' => 'fa-hammer', 'color' => 'teal', 'desc' => 'Create 10 Items'],
                ['name' => 'Architect', 'icon' => 'fa-city', 'color' => 'emerald', 'desc' => 'Create 100 Items'],
                ['name' => 'Creator', 'icon' => 'fa-paintbrush', 'color' => 'pink', 'desc' => 'Create 500 Items'],
                ['name' => 'Editor', 'icon' => 'fa-pen-nib', 'color' => 'orange', 'desc' => 'Update 50 Items'],
                ['name' => 'Maintainer', 'icon' => 'fa-screwdriver-wrench', 'color' => 'amber', 'desc' => 'Update 200 Items'],
                ['name' => 'Cleaner', 'icon' => 'fa-broom', 'color' => 'slate', 'desc' => 'Delete 10 Items'],
                ['name' => 'Destroyer', 'icon' => 'fa-bomb', 'color' => 'red', 'desc' => 'Delete 50 Items'],
                ['name' => 'Time Traveler', 'icon' => 'fa-hourglass-start', 'color' => 'cyan', 'desc' => 'Online 1+ Hour'],
                ['name' => 'Time Lord', 'icon' => 'fa-clock', 'color' => 'violet', 'desc' => 'Online 10+ Hours'],
                ['name' => 'Chronos', 'icon' => 'fa-infinity', 'color' => 'fuchsia', 'desc' => 'Online 100+ Hours'],
            ];
        @endphp

        <!-- 1. Header & My Stats Section -->
        <div
            class="bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-800 text-white pb-20 pt-8 rounded-b-[3rem] shadow-xl relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-blue-500 blur-3xl mix-blend-overlay">
                </div>
                <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-purple-500 blur-3xl mix-blend-overlay">
                </div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Gamification & Leaderboard</h1>
                        <p class="text-blue-200 mt-1">Track your contribution impact and level up!</p>
                    </div>
                    <!-- Mini Trophy for Mobile/Tablet -->
                    <div class="hidden md:block">
                        <i data-lucide="trophy" class="w-12 h-12 text-yellow-400 drop-shadow-lg"></i>
                    </div>
                </div>

                <!-- My Stats Card -->
                <div
                    class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 shadow-lg max-w-4xl mx-auto flex flex-col md:flex-row items-center gap-8">

                    <!-- Avatar Only -->
                    <div class="relative shrink-0">
                        <div
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-3xl font-bold shadow-lg ring-4 ring-white/20 text-white overflow-hidden">
                            @if(auth()->user()->avatar_path)
                                <img src="{{ Storage::url(auth()->user()->avatar_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(auth()->user()->name, 0, 1) }}
                            @endif
                        </div>
                        <div
                            class="absolute -bottom-2 -right-2 bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded-full border border-white">
                            Lvl {{ $currentUser->level }}
                        </div>
                    </div>

                    <!-- Progress & Stats -->
                    <div class="flex-1 w-full text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-2">
                            <div>
                                <h2 class="text-2xl font-bold text-white">{{ $currentUser->name }}</h2>
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-yellow-400/20 text-yellow-300 border border-yellow-400/30">
                                    {{ $currentUser->rank_name }}
                                </span>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-emerald-400">
                                    {{ number_format($currentUser->xp) }} <span
                                        class="text-base font-normal text-blue-200">XP</span>
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="relative h-4 bg-black/20 rounded-full overflow-hidden mb-2">
                            <div class="absolute top-0 left-0 h-full bg-gradient-to-r from-blue-400 to-cyan-300 transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(56,189,248,0.5)]"
                                style="width: {{ $currentUser->progress_percent }}%"></div>
                        </div>

                        <div class="flex justify-between text-xs text-blue-200 font-medium">
                            <span>Current XP: {{ number_format($currentUser->xp) }}</span>
                            <span>Next Level: {{ number_format($currentUser->next_level_xp) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Leaderboard Table Section -->
        <div class="container mx-auto px-4 -mt-12 relative z-20 pb-12">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="crown" class="w-5 h-5 text-yellow-500"></i>
                        Top Administrators
                    </h3>
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Updated Live</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                                <th class="px-6 py-4 w-16 text-center">Rank</th>
                                <th class="px-6 py-4">Admin Name</th>
                                <th class="px-6 py-4 text-center">Level</th>
                                <th class="px-6 py-4 text-center">Badges</th>
                                <th class="px-6 py-4 text-right">Inputs (C)</th>
                                <th class="px-6 py-4 text-right">Edits (U)</th>
                                <th class="px-6 py-4 text-right">Online</th>
                                <th class="px-6 py-4 text-right">Total XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($leaderboard as $index => $user)
                                @php
                                    $rank = $index + 1;
                                    $rowClass = "hover:bg-blue-50/50 transition-colors duration-150 group";

                                    // Top 3 Styling
                                    $rankBadge = null;
                                    if ($rank == 1)
                                        $rankBadge = '<div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold mx-auto">🥇</div>';
                                    elseif ($rank == 2)
                                        $rankBadge = '<div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold mx-auto">🥈</div>';
                                    elseif ($rank == 3)
                                        $rankBadge = '<div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold mx-auto">🥉</div>';
                                    else
                                        $rankBadge = '<span class="font-semibold text-gray-400">#' . $rank . '</span>';
                                @endphp

                                <tr
                                    class="{{ $rowClass }} {{ Auth::id() == $user->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}">
                                    <td class="px-6 py-4 text-center">
                                        {!! $rankBadge !!}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-bold text-gray-600 text-sm group-hover:scale-110 transition-transform overflow-hidden">
                                                @if($user->avatar_path)
                                                    <img src="{{ Storage::url($user->avatar_path) }}"
                                                        class="w-full h-full object-cover" alt="Avatar">
                                                @else
                                                    {{ $user->avatar_initial }}
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('profile.show', $user->id) }}"
                                                    class="font-bold text-gray-900 group-hover:text-blue-700 transition-colors hover:underline">
                                                    {{ $user->name }}
                                                    @if(Auth::id() == $user->id)
                                                        <span
                                                            class="ml-2 text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-bold">YOU</span>
                                                    @endif
                                                </a>
                                                <div
                                                    class="text-xs font-bold {{ $user->rank_color ?? 'text-gray-400' }} flex items-center gap-1.5 mt-0.5">
                                                    <i class="fa-solid {{ $user->rank_icon ?? 'fa-user' }}"></i>
                                                    {{ $user->rank_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-xs font-bold border border-indigo-100">
                                            Lv. {{ $user->level }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col gap-1.5 items-center">
                                            @forelse($user->equipped_badges as $badgeName)
                                                                                    @php
                                                                                        $b = collect($badges)->firstWhere('name', $badgeName);
                                                                                        $color = $b['color'] ?? 'gray';
                                                                                        $icon = $b['icon'] ?? 'fa-medal';

                                                                                        // Map Color Names to Tailwind Classes - Simplified/Shared
                                                                                        $style = match ($color) {
                                                                                            'green' => 'bg-green-100/80 text-green-700 border-green-200',
                                                                                            'blue' => 'bg-blue-100/80 text-blue-700 border-blue-200',
                                                                                            'indigo' => 'bg-indigo-100/80 text-indigo-700 border-indigo-200',
                                                                                            'purple' => 'bg-purple-100/80 text-purple-700 border-purple-200',
                                                                                            'yellow' => 'bg-yellow-100/80 text-yellow-700 border-yellow-200',
                                                                                            'red' => 'bg-red-100/80 text-red-700 border-red-200',
                                                                                            'teal' => 'bg-teal-100/80 text-teal-700 border-teal-200',
                                                                                            'emerald' => 'bg-emerald-100/80 text-emerald-700 border-emerald-200',
                                                                                            'pink' => 'bg-pink-100/80 text-pink-700 border-pink-200',
                                                                                            'orange' => 'bg-orange-100/80 text-orange-700 border-orange-200',
                                                                                            'amber' => 'bg-amber-100/80 text-amber-700 border-amber-200',
                                                                                            'cyan' => 'bg-cyan-100/80 text-cyan-700 border-cyan-200',
                                                                                            'violet' => 'bg-violet-100/80 text-violet-700 border-violet-200',
                                                                                            'fuchsia' => 'bg-fuchsia-100/80 text-fuchsia-700 border-fuchsia-200',
                                                                                            'slate' => 'bg-slate-100/80 text-slate-700 border-slate-200',
                                                                                            default => 'bg-gray-100/80 text-gray-700 border-gray-200'
                                                                                        };
                                                                                    @endphp
                                                 <span
                                                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border shadow-sm whitespace-nowrap w-fit {{ $style }}">
                                                                                        <i class="fa-solid {{ $icon }} mr-1.5 opacity-80"></i> {{ $badgeName }}
                                                                                    </span>
                                            @empty
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-green-600">
                                        +{{ number_format($user->total_creates) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-blue-600">
                                        {{ number_format($user->total_updates) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-500 font-mono text-xs">
                                        <i class="far fa-clock mr-1"></i> {{ $user->time_online }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-base font-black text-gray-800">{{ number_format($user->xp) }}</span>
                                        <span class="text-[10px] text-gray-400 font-semibold ml-0.5">XP</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100 text-center text-xs text-gray-400">
                    Points Calculation: Create (+100 XP), Update (+20 XP), Delete (+10 XP)
                </div>
            </div>

            <!-- Gamification Guide Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 1. Rank System -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i data-lucide="shield" class="w-5 h-5 text-indigo-500"></i>
                        Rank Progression
                    </h3>
                    <div class="overflow-y-auto max-h-60 custom-scrollbar">
                        <table class="w-full text-sm">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-2 text-left">Level</th>
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-right">XP Required</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="px-4 py-2 font-medium">1 - 4</td>
                                    <td class="px-4 py-2 text-gray-600"><i
                                            class="fa-solid fa-user mr-2 text-gray-400"></i> Novice</td>
                                    <td class="px-4 py-2 text-right text-gray-400">100+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">5 - 9</td>
                                    <td class="px-4 py-2 text-blue-600"><i class="fa-solid fa-scroll mr-2"></i>
                                        Apprentice</td>
                                    <td class="px-4 py-2 text-right text-gray-400">2,500+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">10 - 14</td>
                                    <td class="px-4 py-2 text-green-600"><i class="fa-solid fa-book-open mr-2"></i>
                                        Adept</td>
                                    <td class="px-4 py-2 text-right text-gray-400">10,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">15 - 19</td>
                                    <td class="px-4 py-2 text-teal-600"><i class="fa-solid fa-flask mr-2"></i>
                                        Specialist</td>
                                    <td class="px-4 py-2 text-right text-gray-400">22,500+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">20 - 29</td>
                                    <td class="px-4 py-2 text-indigo-600"><i class="fa-solid fa-star mr-2"></i> Expert
                                    </td>
                                    <td class="px-4 py-2 text-right text-gray-400">40,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">30 - 39</td>
                                    <td class="px-4 py-2 text-purple-600 font-bold"><i
                                            class="fa-solid fa-crown mr-2"></i> Master</td>
                                    <td class="px-4 py-2 text-right text-gray-400">90,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">50+</td>
                                    <td class="px-4 py-2 text-yellow-600 font-bold"><i class="fa-solid fa-sun mr-2"></i>
                                        Divine</td>
                                    <td class="px-4 py-2 text-right text-gray-400">250,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">100</td>
                                    <td class="px-4 py-2 text-red-600 font-black"><i class="fa-solid fa-globe mr-2"></i>
                                        Admin of Universe</td>
                                    <td class="px-4 py-2 text-right text-gray-400">1,000,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Badge Encyclopedia -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="book-open" class="w-5 h-5 text-orange-500"></i>
                        Badge Encyclopedia
                    </h3>

                    @php
                        $badges = [
                            ['name' => 'Newbie', 'icon' => 'fa-user', 'color' => 'gray', 'desc' => 'Joined the system'],
                            ['name' => 'Rookie', 'icon' => 'fa-seedling', 'color' => 'green', 'desc' => 'Reach Level 5'],
                            ['name' => 'Veteran', 'icon' => 'fa-shield-halved', 'color' => 'blue', 'desc' => 'Reach Level 10'],
                            ['name' => 'Elite', 'icon' => 'fa-gem', 'color' => 'indigo', 'desc' => 'Reach Level 20'],
                            ['name' => 'Master', 'icon' => 'fa-crown', 'color' => 'yellow', 'desc' => 'Reach Level 30'],
                            ['name' => 'Legend', 'icon' => 'fa-dragon', 'color' => 'red', 'desc' => 'Reach Level 50'],
                            ['name' => 'Builder', 'icon' => 'fa-hammer', 'color' => 'teal', 'desc' => 'Create 10 Items'],
                            ['name' => 'Architect', 'icon' => 'fa-city', 'color' => 'emerald', 'desc' => 'Create 100 Items'],
                            ['name' => 'Creator', 'icon' => 'fa-paintbrush', 'color' => 'pink', 'desc' => 'Create 500 Items'],
                            ['name' => 'Editor', 'icon' => 'fa-pen-nib', 'color' => 'orange', 'desc' => 'Update 50 Items'],
                            ['name' => 'Maintainer', 'icon' => 'fa-screwdriver-wrench', 'color' => 'amber', 'desc' => 'Update 200 Items'],
                            ['name' => 'Cleaner', 'icon' => 'fa-broom', 'color' => 'slate', 'desc' => 'Delete 10 Items'],
                            ['name' => 'Destroyer', 'icon' => 'fa-bomb', 'color' => 'red', 'desc' => 'Delete 50 Items'],
                            ['name' => 'Time Traveler', 'icon' => 'fa-hourglass-start', 'color' => 'cyan', 'desc' => 'Online 1+ Hour'],
                            ['name' => 'Time Lord', 'icon' => 'fa-clock', 'color' => 'violet', 'desc' => 'Online 10+ Hours'],
                            ['name' => 'Chronos', 'icon' => 'fa-infinity', 'color' => 'fuchsia', 'desc' => 'Online 100+ Hours'],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
                        @foreach($badges as $b)
                            @php
                                $color = $b['color'];
                                $iconClass = match ($color) {
                                    'green' => 'bg-green-100 text-green-600',
                                    'blue' => 'bg-blue-100 text-blue-600',
                                    'indigo' => 'bg-indigo-100 text-indigo-600',
                                    'purple' => 'bg-purple-100 text-purple-600',
                                    'yellow' => 'bg-yellow-100 text-yellow-600',
                                    'red' => 'bg-red-100 text-red-600',
                                    'teal' => 'bg-teal-100 text-teal-600',
                                    'emerald' => 'bg-emerald-100 text-emerald-600',
                                    'pink' => 'bg-pink-100 text-pink-600',
                                    'orange' => 'bg-orange-100 text-orange-600',
                                    'amber' => 'bg-amber-100 text-amber-600',
                                    'cyan' => 'bg-cyan-100 text-cyan-600',
                                    'violet' => 'bg-violet-100 text-violet-600',
                                    'fuchsia' => 'bg-fuchsia-100 text-fuchsia-600',
                                    default => 'bg-gray-100 text-gray-600'
                                };
                            @endphp
                            <div
                                class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:shadow-md transition-all">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $iconClass }}">
                                    <i class="fa-solid {{ $b['icon'] }}"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 text-sm leading-tight">{{ $b['name'] }}</h4>
                                    <p class="text-[10px] text-gray-500 uppercase font-semibold mt-0.5">{{ $b['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
</x-app-layout>