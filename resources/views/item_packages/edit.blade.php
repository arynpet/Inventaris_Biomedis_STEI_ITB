<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Paket Praktikum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('item-packages.update', $itemPackage->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nama Paket -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Paket</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $itemPackage->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', $itemPackage->description) }}</textarea>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <!-- Item Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Barang (Dropdown)</label>
                            <p class="text-xs text-gray-500 mb-2">Tekan <strong class="text-gray-700">Ctrl</strong>
                                (Windows) atau <strong class="text-gray-700">Command</strong> (Mac) untuk memilih lebih
                                dari satu item.</p>

                            @if($allItems->isEmpty())
                                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg text-sm">
                                    Tidak ada barang kategori <strong>Praktikum</strong> yang tersedia.
                                </div>
                            @else
                                <div class="relative">
                                    <select name="item_ids[]" multiple
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm h-64">
                                        @foreach($allItems as $item)
                                            @php
                                                $isSelected = $currentItems->contains('id', $item->id);
                                            @endphp
                                            <option value="{{ $item->id }}" {{ $isSelected ? 'selected' : '' }}
                                                class="py-2 px-2 border-b border-gray-100 hover:bg-blue-50 cursor-pointer {{ $isSelected ? 'bg-blue-50 font-semibold text-blue-800' : '' }}">
                                                {{ $isSelected ? '✓ ' : '' }}{{ $item->name }} (SN:
                                                {{ $item->serial_number ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8">
                            <a href="{{ route('item-packages.index') }}"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-bold text-white hover:bg-blue-700 shadow-sm transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>