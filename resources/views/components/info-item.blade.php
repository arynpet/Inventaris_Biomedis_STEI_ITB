
@props([
    'label' => '',
    'value' => '',
    'icon' => '',
    'color' => 'blue'
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-100 text-blue-600 hover:bg-blue-50',
        'purple' => 'bg-purple-100 text-purple-600 hover:bg-purple-50',
        'green' => 'bg-green-100 text-green-600 hover:bg-green-50',
        'orange' => 'bg-orange-100 text-orange-600 hover:bg-orange-50',
        'indigo' => 'bg-indigo-100 text-indigo-600 hover:bg-indigo-50',
    ];
@endphp

<div class="group flex items-start p-3 rounded-xl hover:{{ str_replace('hover:', '', explode(' ', $colorClasses[$color])[2]) }} transition-colors duration-200">
    <div class="flex-shrink-0 w-8 h-8 {{ explode(' ', $colorClasses[$color])[0] }} rounded-lg flex items-center justify-center mr-3 group-hover:{{ explode(' ', $colorClasses[$color])[1] }} transition-colors">
        <svg class="w-4 h-4 {{ explode(' ', $colorClasses[$color])[1] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
    </div>
    <div class="flex-1">
        <p class="text-xs text-gray-500 font-medium mb-1">{{ $label }}</p>
        <p class="text-sm font-semibold text-gray-800">{{ $value }}</p>
    </div>
</div>