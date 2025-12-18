<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Material Type
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6">

                <form action="{{ route('materials.update', $material->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

<div class="mb-4">
    <label class="block font-medium text-sm text-gray-700">
        Stock Saat Ini
    </label>
    <input type="text"
           value="{{ number_format($material->stock_balance, 2) }} {{ $material->unit }}"
           class="w-full bg-gray-100 border-gray-300 rounded mt-1"
           disabled>
</div>


                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">
                            Name
                        </label>
                        <input type="text" name="name"
                               value="{{ $material->name }}"
                               class="w-full border-gray-300 rounded mt-1"
                               required>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Update
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>
