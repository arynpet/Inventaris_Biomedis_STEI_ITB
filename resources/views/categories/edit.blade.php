<x-app-layout>
    <div class="p-6 max-w-xl mx-auto">

        <h1 class="text-2xl font-bold mb-6">Edit Category</h1>

        <form action="{{ route('categories.update', $category->id) }}" method="POST"
              class="space-y-4 bg-white p-6 shadow rounded-lg">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name"
                       class="w-full border rounded-lg px-3 py-2"
                       value="{{ old('name', $category->name) }}" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full border rounded-lg px-3 py-2">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                   Cancel
                </a>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
