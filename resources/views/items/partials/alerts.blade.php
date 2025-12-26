@if (session('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)"
         class="mx-4 my-4 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg text-sm flex items-center gap-3 transition-all duration-500 fixed top-16 right-4 z-50 max-w-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mx-4 my-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl shadow-lg text-sm fixed top-16 right-4 z-50">
        {{ session('error') }}
    </div>
@endif