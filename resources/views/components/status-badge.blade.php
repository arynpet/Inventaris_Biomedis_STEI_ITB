@props(['status'])

@php
    $variants = [
        'success' => 'bg-gradient-to-r from-green-100 to-green-200 text-green-700 border-green-300',
        'warning' => 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-700 border-yellow-300',
        'danger'  => 'bg-gradient-to-r from-red-100 to-red-200 text-red-700 border-red-300',
        'neutral' => 'bg-gray-100 text-gray-700 border-gray-300', // Fallback
    ];

    $statusMap = [
        'available' => 'success',
        'sedia'     => 'success',
        'returned'  => 'success',
        
        'borrowed'  => 'warning',
        'dipinjam'  => 'warning',
        'pending'   => 'warning',
        
        'maintenance' => 'danger',
        'rusak'       => 'danger',
        'late'        => 'danger',
    ];

    $variantKey = $statusMap[strtolower($status)] ?? 'neutral';
    $classes = $variants[$variantKey];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold border shadow-sm {$classes}"]) }}>
    {{ ucfirst($status) }}
</span>