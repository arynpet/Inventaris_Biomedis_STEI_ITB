
@props([
    'variant' => 'primary', // primary, secondary, success, danger
    'size' => 'md',
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left'
])

@php
    $variants = [
        'primary' => 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700',
        'secondary' => 'from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700',
        'success' => 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700',
        'danger' => 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700',
        'purple' => 'from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700',
    ];
    
    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3',
        'lg' => 'px-8 py-4 text-lg'
    ];
    
    $baseClasses = 'group flex items-center bg-gradient-to-r text-white rounded-xl font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200';
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses {$variants[$variant]} {$sizes[$size]}"]) }}>
    
    @if($icon && $iconPosition === 'left')
        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    @endif
</button>
