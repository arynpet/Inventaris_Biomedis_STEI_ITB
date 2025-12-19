@props([
    'color' => 'blue', // blue, purple, green, orange, indigo, etc
    'size' => 'md', // sm, md, lg
    'icon' => ''
])

@php
    $sizes = [
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16'
    ];
    
    $iconSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8'
    ];
    
    $colors = [
        'blue' => 'from-blue-500 to-blue-600',
        'purple' => 'from-purple-500 to-purple-600',
        'green' => 'from-green-500 to-green-600',
        'orange' => 'from-orange-500 to-orange-600',
        'indigo' => 'from-indigo-500 to-purple-600',
        'cyan' => 'from-cyan-500 to-blue-600',
    ];
@endphp

<div class="bg-gradient-to-br {{ $colors[$color] }} {{ $sizes[$size] }} rounded-xl flex items-center justify-center shadow-lg">
    <svg class="{{ $iconSizes[$size] }} text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
    </svg>
</div>
