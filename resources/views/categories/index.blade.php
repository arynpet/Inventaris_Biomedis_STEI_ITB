<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories Management') }}
        </h2>
    </x-slot>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-4 right-4 z-50 p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="py-12" x-data="categoryPage({{ json_encode($categories->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Kategori Barang</h3>
                <a href="{{ route('categories.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                    + Tambah Kategori
                </a>
            </div>

            <form id="bulkActionForm" action="{{ route('categories.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" @click="toggleAll"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kategori</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-blue-50 transition"
                                        :class="{'bg-blue-50': selectedItems.includes({{ $category->id }})}">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $category->id }}"
                                                @click="toggleItem({{ $category->id }}, {{ $loop->index }}, $event)"
                                                :checked="selectedItems.includes({{ $category->id }})"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#{{ $category->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $category->description ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('categories.edit', $category->id) }}"
                                                    class="text-amber-600 hover:text-amber-900 bg-amber-50 p-2 rounded">Edit</a>
                                                <form action="{{ route('categories.destroy', $category->id) }}"
                                                    method="POST" onsubmit="return confirm('Hapus kategori ini?');"
                                                    class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            <div class="mt-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="w-full">
                    @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $categories->links() }}
                    @else
                        <div class="text-sm text-gray-500">Menampilkan semua {{ $categories->count() }} data.</div>
                    @endif
                </div>

                <div class="whitespace-nowrap">
                    @if(request('show_all'))
                        <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Batasi Per Halaman
                        </a>
                    @else
                        <a href="{{ request()->fullUrlWithQuery(['show_all' => 1]) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Tampilkan Semua
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- FLOATING BULK ACTION --}}
        <div x-show="selectedItems.length > 0" x-transition
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-700 font-medium"><span
                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold"
                    x-text="selectedItems.length"></span><span>Dipilih</span></div>
            <button @click="submitBulkAction('delete')"
                class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                    </path>
                </svg> Hapus Terpilih
            </button>
        </div>

    </div>

    <script>
        function categoryPage(pageIds = []) {
            return {
                selectedItems: [],
                pageIds: pageIds,
                lastCheckedIndex: null,

                toggleItem(id, index, event) {
                    if (event.shiftKey && this.lastCheckedIndex !== null) {
                        const start = Math.min(this.lastCheckedIndex, index);
                        const end = Math.max(this.lastCheckedIndex, index);
                        this.pageIds.slice(start, end + 1).forEach(i => {
                            if (!this.selectedItems.includes(i)) this.selectedItems.push(i);
                        });
                    } else {
                        if (this.selectedItems.includes(id)) this.selectedItems = this.selectedItems.filter(i => i !== id);
                        else this.selectedItems.push(id);
                        this.lastCheckedIndex = index;
                    }
                },
                toggleAll(e) {
                    this.selectedItems = e.target.checked ? [...this.pageIds] : [];
                },
                submitBulkAction(type) {
                    if (confirm('Yakin ingin menghapus ' + this.selectedItems.length + ' kategori?')) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>