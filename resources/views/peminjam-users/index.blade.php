<x-app-layout>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="peminjamPage({{ json_encode($users->pluck('id')) }})">

        {{-- Success Notification --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)"
                class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Peminjam</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data mahasiswa, dosen, dan staff.</p>
            </div>

            <a href="{{ route('peminjam-users.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Peminjam
            </a>
        </div>

        {{-- SEARCH & FILTER SECTION --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm mb-6">
            <form method="GET" action="{{ route('peminjam-users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- Input Search --}}
                    <div class="col-span-1 md:col-span-2 relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm transition"
                            placeholder="Cari Nama, NIM, atau Email...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    {{-- Filter Role --}}
                    <div>
                        <select name="role"
                            class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
                            <option value="">Semua Role</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa
                            </option>
                            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    {{-- Filter Pelatihan --}}
                    <div>
                        <select name="is_trained"
                            class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_trained') == '1' ? 'selected' : '' }}>Sudah Pelatihan
                            </option>
                            <option value="0" {{ request('is_trained') === '0' ? 'selected' : '' }}>Belum Pelatihan
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Action Buttons Filter --}}
                <div class="mt-4 flex justify-end gap-3">
                    @if(request()->anyFilled(['search', 'role', 'is_trained']))
                        <a href="{{ route('peminjam-users.index') }}"
                            class="px-5 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-sm font-medium">
                            Reset
                        </a>
                    @endif
                    <button type="submit"
                        class="px-6 py-2.5 bg-gray-800 text-white hover:bg-gray-900 rounded-xl transition text-sm font-medium shadow-md">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Content --}}
        <form id="bulkActionForm" action="{{ route('peminjam-users.bulk_action') }}" method="POST">
            @csrf
            <input type="hidden" name="action_type" id="bulkActionType">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-4 w-10 text-center">
                                    <input type="checkbox" @click="toggleAll"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                </th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Nama</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">NIM /
                                    Identitas</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Kontak</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Role</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Pelatihan
                                </th>
                                <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $user)
                                <tr class="hover:bg-blue-50/50 transition duration-150"
                                    :class="{'bg-blue-50': selectedItems.includes({{ $user->id }})}">
                                    <td class="px-4 py-4 text-center">
                                        <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                                            @click="toggleItem({{ $user->id }}, {{ $loop->index }}, $event)"
                                            :checked="selectedItems.includes({{ $user->id }})"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-600">
                                        {{ $user->nim ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-900">{{ $user->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->phone ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200 capitalize">
                                            {{ $user->role ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- Status Pelatihan --}}
                                    <td class="px-6 py-4">
                                        @if($user->is_trained)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Sudah
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Belum
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            {{-- Edit Button --}}
                                            <a href="{{ route('peminjam-users.edit', $user->id) }}"
                                                class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition border border-amber-100"
                                                title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            {{-- Delete Button (Trigger Single Modal) --}}
                                            <button type="button"
                                                @click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition border border-red-100"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="p-4 bg-gray-50 rounded-full mb-3">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-base font-medium text-gray-900">Data tidak ditemukan.</p>
                                            <p class="text-sm text-gray-500">Coba ubah kata kunci pencarian atau filter.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        {{-- Pagination & Layout Control --}}
        <div class="mt-6 px-2 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full">
                @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $users->withQueryString()->links() }}
                @else
                    <div class="text-sm text-gray-500">Menampilkan semua {{ $users->count() }} data.</div>
                @endif
            </div>

            <div class="whitespace-nowrap">
                @if(request('show_all'))
                    <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        Batasi Per Halaman
                    </a>
                @else
                    <a href="{{ request()->fullUrlWithQuery(['show_all' => 1]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Tampilkan Semua
                    </a>
                @endif
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

        {{-- SINGLE DELETE MODAL --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div x-show="showModal" x-transition.opacity
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="showModal = false"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Peminjam</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-800"
                                            x-text="deleteName"></span>?
                                        Data yang dihapus tidak dapat dikembalikan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                Ya, Hapus
                            </button>
                        </form>
                        <button type="button" @click="showModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function peminjamPage(pageIds = []) {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',
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
                    if (confirm('Yakin ingin menghapus ' + this.selectedItems.length + ' data peminjam?')) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                },
                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/peminjam-users/${id}`;
                }
            }
        }
    </script>
</x-app-layout>