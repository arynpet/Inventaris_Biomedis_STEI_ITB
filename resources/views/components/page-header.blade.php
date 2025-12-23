@props([
    'title' => '',
    'description' => '',
])

<div {{ $attributes->merge(['class' => 'flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4']) }}>
    <div>
        <h3 class="text-2xl font-bold text-gray-800">{{ $title }}</h3>
        @if($description)
            <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="flex gap-3">
            {{ $actions }}
        </div>
    @endif
</div>