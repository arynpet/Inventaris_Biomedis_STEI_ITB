@props([
    'headers' => [],
    'pagination' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>