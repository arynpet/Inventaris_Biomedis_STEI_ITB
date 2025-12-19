
@props([
    'variant' => 'default', // default, gradient, bordered
    'padding' => 'p-8',
    'hover' => true
])

@php
    $baseClasses = 'rounded-3xl border transition-all duration-300';
    
    $variants = [
        'default' => 'bg-white shadow-xl border-gray-100',
        'gradient' => 'bg-gradient-to-br from-white to-gray-50 shadow-xl border-gray-100',
        'bordered' => 'bg-white border-2 border-gray-100'
    ];
    
    $hoverClass = $hover ? 'hover:shadow-2xl' : '';
    
    $classes = "$baseClasses {$variants[$variant]} $padding $hoverClass";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>