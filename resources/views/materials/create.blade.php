<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Material Type
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('materials.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Category
                        </label>
                        <select name="category" class="w-full border-gray-300 rounded mt-1">
                            <option value="filament">Filament</option>
                            <option value="resin">Resin</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Name
                        </label>
                        <input type="text" name="name"
                               class="w-full border-gray-300 rounded mt-1"
                               required>
                    </div>

                    <div class="mb-4">
    <label class="block font-medium text-sm text-gray-700">
        Stock Awal
    </label>
    <input type="number" step="0.01" name="stock_balance"
           class="w-full border-gray-300 rounded mt-1"
           placeholder="Contoh: 1000"
           required>
</div>

<div class="mb-4">
    <label class="block font-medium text-sm text-gray-700">
        Unit
    </label>
    <select name="unit" class="w-full border-gray-300 rounded mt-1">
        <option value="gr">Gram (gr)</option>
        <option value="ml">Milliliter (ml)</option>
    </select>
</div>


                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
