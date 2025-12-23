@props([
    'action' => '',
    'method' => 'GET',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <form action="{{ $action }}" method="{{ $method }}" {{ $attributes->merge(['class' => 'flex flex-col lg:flex-row gap-4 items-end lg:items-center']) }}>
        @csrf
        {{ $slot }}
    </form>
</div>