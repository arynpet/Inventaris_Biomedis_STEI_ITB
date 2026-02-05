<x-app-layout>
    <div class="min-h-screen bg-gray-50/50">

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
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-3xl font-bold shadow-lg ring-4 ring-white/20 text-white">
                            {{ substr(auth()->user()->name, 0, 1) }}
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
                                                <div class="text-xs text-gray-400 font-medium">{{ $user->rank_name }}</div>
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
                                        @if($user->badge)
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-purple-100 to-pink-100 text-purple-700 border border-purple-200 shadow-sm">
                                                {{ $user->badge }}
                                            </span>
                                        @else
                                            <span class="text-gray-300 text-xs">-</span>
                                        @endif
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
                                    <td class="px-4 py-2 text-gray-600">Novice</td>
                                    <td class="px-4 py-2 text-right text-gray-400">100+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">5 - 9</td>
                                    <td class="px-4 py-2 text-blue-600">Apprentice</td>
                                    <td class="px-4 py-2 text-right text-gray-400">2,500+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">10 - 14</td>
                                    <td class="px-4 py-2 text-green-600">Adept</td>
                                    <td class="px-4 py-2 text-right text-gray-400">10,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">15 - 19</td>
                                    <td class="px-4 py-2 text-teal-600">Specialist</td>
                                    <td class="px-4 py-2 text-right text-gray-400">22,500+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">20 - 29</td>
                                    <td class="px-4 py-2 text-indigo-600">Expert</td>
                                    <td class="px-4 py-2 text-right text-gray-400">40,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">30 - 39</td>
                                    <td class="px-4 py-2 text-purple-600 font-bold">Master</td>
                                    <td class="px-4 py-2 text-right text-gray-400">90,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">50+</td>
                                    <td class="px-4 py-2 text-yellow-600 font-bold">Divine</td>
                                    <td class="px-4 py-2 text-right text-gray-400">250,000+</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 font-medium">100</td>
                                    <td class="px-4 py-2 text-red-600 font-black">Admin of Universe</td>
                                    <td class="px-4 py-2 text-right text-gray-400">1,000,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. Badge Guide -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i data-lucide="medal" class="w-5 h-5 text-orange-500"></i>
                        Available Badges
                    </h3>
                    <div class="space-y-4">
                        <!-- Builder -->
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                <i data-lucide="hammer" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Builder / Architect</h4>
                                <p class="text-xs text-gray-500">Awarded for creating new items. Tiers: 10, 100, 500
                                    items.</p>
                            </div>
                        </div>

                        <!-- Maintainer -->
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-green-100 rounded-lg text-green-600">
                                <i data-lucide="edit" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Editor / Polisher</h4>
                                <p class="text-xs text-gray-500">Awarded for updating existing items. Tiers: 50, 200,
                                    1000 edits.</p>
                            </div>
                        </div>

                        <!-- Cleaner -->
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-red-100 rounded-lg text-red-600">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Cleaner / Destroyer</h4>
                                <p class="text-xs text-gray-500">Awarded for deleting obsolete items. Tiers: 10, 50
                                    deletes.</p>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                                <i data-lucide="zap" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Inventory God</h4>
                                <p class="text-xs text-gray-500">The ultimate prestige for reaching massive XP
                                    milestones (10k, 50k, 100k).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>