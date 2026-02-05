<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-12">
        <div class="container mx-auto px-4 max-w-5xl">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Profile & Customization</h1>
                    <p class="text-gray-500">Manage your avatar, bio, and badge loadout.</p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('profile.show', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        <i class="fa-solid fa-eye text-blue-500"></i> View Public Card
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- LEFT COLUMN: Mini Stats & Preview -->
                <div class="space-y-6">
                    <!-- Mini Player Card -->
                    <div class="bg-gradient-to-br from-indigo-900 to-blue-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                         <!-- Background Deco -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center">
                            <div class="w-24 h-24 rounded-full border-4 border-white/30 shadow-lg overflow-hidden mb-3 bg-white">
                                @if($user->avatar_path)
                                    <img src="{{ Storage::url($user->avatar_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400 text-3xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold">{{ $user->name }}</h2>
                            <div class="inline-flex items-center gap-1 bg-black/30 px-3 py-1 rounded-full text-xs font-medium mt-1 mb-4 backdrop-blur-sm border border-white/10">
                                <span class="text-yellow-400">Lvl {{ $stats->level }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-blue-200">{{ $stats->rank_name }}</span>
                            </div>

                            <div class="w-full bg-white/10 rounded-lg p-3 grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <div class="text-blue-200 text-xs">Total XP</div>
                                    <div class="font-bold">{{ number_format($stats->xp) }}</div>
                                </div>
                                <div>
                                    <div class="text-blue-200 text-xs">Online</div>
                                    <div class="font-bold">{{ $stats->time_online }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Resources Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple text-green-500"></i> Impact Stats
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Items Created</span>
                                <span class="font-bold text-gray-800">{{ number_format($stats->creates) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Items Updated</span>
                                <span class="font-bold text-gray-800">{{ number_format($stats->updates) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Items Deleted</span>
                                <span class="font-bold text-gray-800">{{ number_format($stats->deletes) }}</span>
                            </div>
                            <div class="h-px bg-gray-100 my-2"></div>
                            <p class="text-xs text-center text-gray-400 italic">
                                Keep contributing to unlock more badges!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Edit Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('profile.setup.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <!-- Tabs / Sections -->
                            <div class="p-6 space-y-8">
                                
                                <!-- 1. Identity Section -->
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Identity</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Avatar Input -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Change Avatar</label>
                                            <div class="flex items-center gap-3">
                                                <div class="relative w-full">
                                                    <input type="file" name="avatar" id="avatarInput" class="hidden" onchange="previewImage(event)">
                                                    <label for="avatarInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                                                            <p class="text-xs text-gray-500">Click to upload image</p>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-400">Max size: 2MB. Formats: JPG, PNG.</p>
                                        </div>

                                        <!-- Bio Input -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio / Tagline</label>
                                            <textarea name="bio" rows="4" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Write something cool about your role...">{{ old('bio', $user->bio) }}</textarea>
                                            <p class="mt-1 text-xs text-gray-400 text-right">0/255 characters</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2. Badge Loadout -->
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex justify-between items-center">
                                        <span>Badge Loadout</span>
                                        <span class="text-xs font-normal bg-blue-100 text-blue-700 px-2 py-1 rounded-full">
                                            Unlocked: {{ count($stats->unlocked_badges) }}
                                        </span>
                                    </h3>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @php
                                            // Define all possible badges to show Locked state too (Optional enhancement)
                                            // For now, we list unlocked ones + a generic locked one.
                                        @endphp

                                        @foreach($stats->unlocked_badges as $badge)
                                            <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition-all hover:bg-gray-50 hover:shadow-md {{ $user->equipped_badge == $badge ? 'border-blue-500 bg-blue-50/50 ring-1 ring-blue-500' : 'border-gray-200' }}">
                                                <input type="radio" name="equipped_badge" value="{{ $badge }}" class="peer hidden" {{ $user->equipped_badge == $badge ? 'checked' : '' }}>
                                                
                                                <!-- Icon Mapping (Simple) -->
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 
                                                    {{ $user->equipped_badge == $badge ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                                                    <i class="fa-solid fa-medal"></i>
                                                </div>

                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-800 peer-checked:text-blue-700">{{ $badge }}</div>
                                                    <div class="text-xs text-gray-500">Unlocked reward</div>
                                                </div>

                                                <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100">
                                                    <i class="fa-solid fa-check-circle"></i>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <!-- Locked Badges Teaser -->
                                    <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                        <h4 class="text-sm font-bold text-gray-500 mb-2">Locked Badges (Keep Grinding!)</h4>
                                        <div class="flex flex-wrap gap-2 opacity-50 grayscale">
                                            <span class="px-3 py-1 bg-white border rounded-full text-xs">🔒 Architect (100 Creates)</span>
                                            <span class="px-3 py-1 bg-white border rounded-full text-xs">🔒 Maintainer (200 Updates)</span>
                                            <span class="px-3 py-1 bg-white border rounded-full text-xs">🔒 Legend (Lvl 50)</span>
                                            <span class="px-3 py-1 bg-white border rounded-full text-xs">🔒 Godlike (Lvl 80)</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- Save Actions -->
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
                                <button type="reset" class="px-4 py-2 text-gray-600 hover:text-gray-900 font-medium transition">Cancel</button>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                    <i class="fa-solid fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Simple JS image preview -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                // We could update a preview img tag here if we had one explicitly for upload preview
                // For now, simpler is better.
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>