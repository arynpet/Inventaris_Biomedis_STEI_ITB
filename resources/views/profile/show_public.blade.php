<x-app-layout>
    <!-- Background Gradient -->
    <div class="fixed inset-0 z-0 bg-gradient-to-br from-indigo-50 via-white to-blue-50 pointer-events-none"></div>

    <div class="relative z-10 min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-6xl">

            <!-- Hero Board: Identity & Main Stats -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100">
                <!-- Cover Banner -->
                <div class="h-48 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20">
                        <!-- Decorative pattern -->
                        <div
                            class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full mix-blend-overlay filter blur-xl opacity-50 transform -translate-x-10 -translate-y-10">
                        </div>
                        <div
                            class="absolute bottom-0 right-0 w-48 h-48 bg-pink-500 rounded-full mix-blend-overlay filter blur-2xl opacity-50 transform translate-x-10 translate-y-10">
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row items-end px-8 -mt-20 pb-8 gap-6">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-40 h-40 rounded-full border-4 border-white shadow-2xl overflow-hidden bg-white">
                            @if($user->avatar_path)
                                <img src="{{ Storage::url($user->avatar_path) }}" class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500 text-5xl font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div
                            class="absolute bottom-2 right-2 bg-indigo-600 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-md">
                            Lvl {{ $stats->level }}
                        </div>
                    </div>

                    <!-- Name & Bio -->
                    <div class="flex-1 text-center md:text-left mb-2">
                        <h1 class="text-3xl font-black text-gray-900 flex flex-col md:flex-row items-center gap-2">
                            {{ $user->name }}
                            @if($user->equipped_badge)
                                <span
                                    class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200 shadow-sm flex items-center gap-1">
                                    <i class="fa-solid fa-medal"></i> {{ $user->equipped_badge }}
                                </span>
                            @endif
                        </h1>
                        <p class="text-gray-500 font-medium italic mt-1 max-w-xl">
                            "{{ $user->bio ?? 'Ready to conquer the inventory!' }}"
                        </p>
                    </div>

                    <!-- Action Button -->
                    @if(Auth::id() == $user->id)
                        <div class="mb-4">
                            <a href="{{ route('profile.setup') }}"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                                <i class="fa-solid fa-pen-to-square text-blue-500"></i> Edit Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- COLUMN 1: Level Progress & Rank -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Rank Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-indigo-50 relative overflow-hidden">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-bl-[4rem] -mr-4 -mt-4 opacity-50">
                        </div>
                        <h3 class="font-bold text-gray-400 text-xs uppercase tracking-widest mb-1">Current Rank</h3>
                        <div class="text-2xl font-black text-indigo-700 mb-4">{{ $stats->rank_name }}</div>

                        <div class="relative pt-1">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span
                                        class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                                        Progress
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-indigo-600">
                                        {{ number_format($stats->progress_percent, 1) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-100">
                                <div style="width:{{ $stats->progress_percent }}%"
                                    class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-1000">
                                </div>
                            </div>
                            <div class="text-xs text-gray-500 flex justify-between">
                                <span><i class="fa-solid fa-bolt text-yellow-500"></i> {{ number_format($stats->xp) }}
                                    XP</span>
                                <span>Next: {{ number_format($stats->next_level_xp) }} XP</span>
                            </div>
                            <div class="mt-2 text-center text-xs text-indigo-400 font-medium bg-indigo-50 py-1 rounded">
                                Needs {{ number_format(max(0, $stats->xp_needed)) }} XP to Level Up
                            </div>
                        </div>
                    </div>

                    <!-- Badge Case -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-award text-orange-500"></i> Trophy Case
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200 shadow-sm"
                                title="Equipped">
                                {{ $user->equipped_badge }}
                            </span>
                            @foreach($stats->unlocked_badges as $badge)
                                @if($badge !== $user->equipped_badge)
                                    <span
                                        class="px-3 py-1 bg-gray-50 text-gray-500 rounded-full text-xs font-medium border border-gray-100 opacity-75">
                                        {{ $badge }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- COLUMN 2 & 3: Statistics Dashboard -->
                <div class="md:col-span-2 space-y-6">

                    <!-- Core Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Total XP -->
                        <div
                            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                            <div
                                class="w-10 h-10 mx-auto bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <div class="text-2xl font-black text-gray-800">{{ number_format($stats->xp) }}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Total XP</div>
                        </div>

                        <!-- Time Online -->
                        <div
                            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                            <div
                                class="w-10 h-10 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-regular fa-clock"></i>
                            </div>
                            <div class="text-2xl font-black text-gray-800">{{ $stats->time_online }}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Online Time</div>
                        </div>

                        <!-- Total Creates -->
                        <div
                            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                            <div
                                class="w-10 h-10 mx-auto bg-teal-100 text-teal-600 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="text-2xl font-black text-gray-800">{{ number_format($stats->creates) }}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Items Created</div>
                        </div>

                        <!-- Updates -->
                        <div
                            class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 text-center hover:shadow-md transition">
                            <div
                                class="w-10 h-10 mx-auto bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-2">
                                <i class="fa-solid fa-pen"></i>
                            </div>
                            <div class="text-2xl font-black text-gray-800">{{ number_format($stats->updates) }}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Edits Made</div>
                        </div>
                    </div>

                    <!-- Activity Analysis -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Activity Breakdown -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-chart-pie text-gray-400"></i> Work Breakdown
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                        <span>CREATION ({{ number_format($stats->creates) }})</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full"
                                            style="width: {{ ($stats->creates + $stats->updates + $stats->deletes) > 0 ? ($stats->creates / ($stats->creates + $stats->updates + $stats->deletes) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                        <span>MAINTENANCE ({{ number_format($stats->updates) }})</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full"
                                            style="width: {{ ($stats->creates + $stats->updates + $stats->deletes) > 0 ? ($stats->updates / ($stats->creates + $stats->updates + $stats->deletes) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                                        <span>CLEANUP ({{ number_format($stats->deletes) }})</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full"
                                            style="width: {{ ($stats->creates + $stats->updates + $stats->deletes) > 0 ? ($stats->deletes / ($stats->creates + $stats->updates + $stats->deletes) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Pulse -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-heart-pulse text-red-500"></i> Last Pulse
                            </h3>
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $stats->last_action }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Observed {{ $stats->last_seen }}</div>

                                    <div
                                        class="mt-4 flex items-center gap-2 text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded inline-block">
                                        <i class="fa-solid fa-ratio-combined"></i> Edit/Create Ratio:
                                        {{ $stats->completion_rate }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>