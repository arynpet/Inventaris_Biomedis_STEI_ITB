<x-app-layout>
    {{-- Container utama diperlebar jadi max-w-7xl --}}
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Success Notification --}}
        @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 2500)"
                class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
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
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>

                    {{-- Filter Role --}}
                    <div>
                        <select name="role" class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
                            <option value="">Semua Role</option>
                            <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>

                    {{-- Filter Pelatihan --}}
                    <div>
                        <select name="is_trained" class="w-full py-2.5 rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('is_trained') == '1' ? 'selected' : '' }}>Sudah Pelatihan</option>
                            <option value="0" {{ request('is_trained') === '0' ? 'selected' : '' }}>Belum Pelatihan</option>
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
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Nama</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">NIM / Identitas</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Kontak</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Role</th>
                            <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Pelatihan</th>
                            <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-xs">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-blue-50/50 transition duration-150">
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
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200 capitalize">
                                        {{ $user->role ?? '-' }}
                                    </span>
                                </td>

                                {{-- Status Pelatihan --}}
                                <td class="px-6 py-4">
                                    @if($user->is_trained)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Belum
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('peminjam-users.edit', $user->id) }}"
                                           class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg transition border border-amber-100" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        {{-- Delete Button --}}
                                        <button x-data
                                                @click="$dispatch('open-modal', { id: 'delete-{{ $user->id }}' })"
                                                class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition border border-red-100" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Delete Modal (Pindah ke dalam loop agar ID unik dan context user benar) --}}
                            <div x-data="{ open: false }"
                                 x-show="open"
                                 x-on:open-modal.window="if($event.detail.id === 'delete-{{ $user->id }}') open = true"
                                 x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                                 style="display: none;">
                                
                                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 border border-gray-100" 
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-90"
                                     x-transition:enter-end="opacity-100 scale-100">
                                    
                                    <div class="text-center mb-6">
                                        <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Hapus Peminjam?</h3>
                                        <p class="text-sm text-gray-500 mt-2">Anda yakin ingin menghapus <strong>{{ $user->name }}</strong>? Data ini tidak dapat dikembalikan.</p>
                                    </div>
                                    
                                    <div class="flex justify-end gap-3">
                                        <button @click="open = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition text-sm font-semibold">
                                            Batal
                                        </button>
                                        <form method="POST" action="{{ route('peminjam-users.destroy', $user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl transition text-sm font-semibold shadow-md">
                                                Ya, Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-4 bg-gray-50 rounded-full mb-3">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
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

        {{-- Pagination --}}
        <div class="mt-6 px-2">
            {{ $users->withQueryString()->links() }}
        </div>

    </div>
</x-app-layout>