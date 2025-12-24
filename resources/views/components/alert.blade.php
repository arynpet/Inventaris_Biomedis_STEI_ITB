@props([
    'type' => 'success', // success, error, warning, info
    'dismissible' => true,
    'autoClose' => true,
    'duration' => 3000
])

@php
    $typeStyles = [
        'success' => 'bg-gradient-to-r from-green-500 to-green-600 text-white',
        'error' => 'bg-red-100 border-l-4 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-100 border-l-4 border-blue-500 text-blue-700',
    ];
    
    $typeIcons = [
        'success' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'error' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ];
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show"
    @if($autoClose)
        x-init="setTimeout(() => show = false, {{ $duration }})"
    @endif
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2"
    {{ $attributes->merge(['class' => "mx-4 my-4 p-4 rounded-xl shadow-lg flex items-center gap-2 " . $typeStyles[$type]]) }}>
    
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeIcons[$type] }}"></path>
    </svg>
    
    <div class="flex-1">
        {{ $slot }}
    </div>
    
    @if($dismissible)
        <button @click="show = false" class="flex-shrink-0 ml-2 hover:opacity-75 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    @endif
</div>