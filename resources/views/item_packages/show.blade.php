<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Paket Praktikum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $itemPackage->name }}</h3>
                            <p class="text-gray-500 mt-1">{{ $itemPackage->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('item-packages.edit', $itemPackage->id) }}"
                                class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-bold hover:bg-amber-600">
                                Edit Paket
                            </a>
                            <a href="{{ route('item-packages.index') }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200">
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Daftar Item dalam Paket ({{ $itemPackage->items->count() }})
                        </h4>

                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama
                                            Item</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Serial
                                            Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Asset
                                            Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">
                                            Kondisi</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($itemPackage->items as $item)
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $item->name }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-600">
                                                {{ $item->serial_number ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm font-mono text-gray-600">
                                                {{ $item->asset_number ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($item->condition) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <x-status-badge :status="$item->status" />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">
                                                Tidak ada item di dalam paket ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>