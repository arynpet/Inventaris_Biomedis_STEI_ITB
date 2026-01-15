<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Pengguna (Admin)') }}
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

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-4 right-4 z-50 p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="py-12" x-data="adminUserPage({{ json_encode($users->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header Actions --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Akun Admin</h3>
                    <p class="text-sm text-gray-500">Kelola akses staff inventory & superadmin.</p>
                </div>
                <a href="{{ route('superadmin.users.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User Baru
                </a>
            </div>

            {{-- Bulk Action Form --}}
            <form id="bulkActionForm" action="{{ route('superadmin.users.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 w-10 text-center">
                                        <input type="checkbox" @click="toggleAll"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Bergabung</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-indigo-50/30 transition"
                                        :class="{'bg-indigo-50/50': selectedItems.includes({{ $user->id }})}">
                                        <td class="px-4 py-4 text-center">
                                            @if($user->id !== auth()->id())
                                                <input type="checkbox" name="selected_ids[]" value="{{ $user->id }}"
                                                    @click="toggleItem({{ $user->id }}, {{ $loop->index }}, $event)"
                                                    :checked="selectedItems.includes({{ $user->id }})"
                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center font-bold text-gray-600 text-lg">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->role === 'superadmin')
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">Super
                                                    Admin</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Admin</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($user->id !== auth()->id())
                                                <button type="button"
                                                    @click="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">Hapus</button>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Akun Anda</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>

        {{-- Floating Action --}}
        <div x-show="selectedItems.length > 0" x-transition
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-700 font-medium"><span
                    class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-lg text-sm font-bold"
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

        {{-- Single Delete Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div x-show="showModal" @click.away="showModal = false"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
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
                                <h3 class="text-base font-semibold leading-6 text-gray-900">Hapus Admin User</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Yakin hapus user <span class="font-bold"
                                            x-text="deleteName"></span>?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Ya,
                                Hapus</button>
                        </form>
                        <button type="button" @click="showModal = false"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Batal</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function adminUserPage(pageIds = []) {
            const currentUserId = {{ auth()->id() }};
            // Filter out own ID from toggleAll logic
            const toggleableIds = pageIds.filter(id => id != currentUserId);

            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',
                selectedItems: [],
                pageIds: toggleableIds,
                lastCheckedIndex: null,

                toggleItem(id, index, event) {
                    if (event.shiftKey && this.lastCheckedIndex !== null) {
                        const start = Math.min(this.lastCheckedIndex, index);
                        const end = Math.max(this.lastCheckedIndex, index);
                        // We need to re-find specific IDs in the table as indices might not match 1:1 if sorted differently in JS (unlikely here)
                        // Actually easier: just loop through the provided togglableIds if we had index logic
                        // But since we pass pageIds as full list, we might have issues if we use blade index.
                        // Let's stick to simple individual toggle if shift is complex, 
                        // BUT user asked for shift.
                        // The loop->index in blade corresponds to the rendered order.
                        // So we should be fine assuming pageIds is in same order as rendered.
                        // Wait, 'pageIds' passed to alpine is pluck('id'). 
                        // The loop is foreach($users). Order is preserved.
                        // Ideally we grab the subset of IDs from the full list based on index range.

                        // We need the FULL list of IDs in order to slice them.
                        const allIds = {{ json_encode($users->pluck('id')) }};
                        const subset = allIds.slice(start, end + 1);

                        subset.forEach(i => {
                            if (i != currentUserId) {
                                if (!this.selectedItems.includes(i)) this.selectedItems.push(i);
                            }
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
                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/superadmin/users/${id}`;
                },
                submitBulkAction(type) {
                    if (confirm('Yakin hapus ' + this.selectedItems.length + ' user?')) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                }
            }
        }
    </script>
</x-app-layout>