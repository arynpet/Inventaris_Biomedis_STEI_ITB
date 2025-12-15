<x-app-layout>
    <div class="p-6 max-w-5xl mx-auto">

        {{-- Success Notification --}}
        @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => show = false, 2500)"
                class="mb-4 bg-green-100 border border-green-300 text-green-700 px-4 py-2 rounded-xl shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Data Peminjam</h1>

            <a href="{{ route('peminjam-users.create') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
                + Tambah Peminjam
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
<table class="w-full">
    <thead class="bg-gray-50 text-gray-600">
        <tr>
            <th class="px-4 py-3 text-left">Nama</th>
            <th class="px-4 py-3 text-left">NIM</th>
            <th class="px-4 py-3 text-left">Email</th>
            <th class="px-4 py-3 text-left">Phone</th>
            <th class="px-4 py-3 text-left">Role</th>
            <th class="px-4 py-3 text-left">Pelatihan</th>
            <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
    </thead>

    <tbody class="divide-y">
        @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">{{ $user->name }}</td>
                <td class="px-4 py-3">{{ $user->nim ?? '-' }}</td>
                <td class="px-4 py-3">{{ $user->email ?? '-' }}</td>
                <td class="px-4 py-3">{{ $user->phone ?? '-' }}</td>
                <td class="px-4 py-3 capitalize">{{ $user->role ?? '-' }}</td>

                {{-- Status Pelatihan --}}
                <td class="px-4 py-3">
                    @if($user->is_trained)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-600 rounded border border-green-300">
                            Sudah
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded border border-red-300">
                            Belum
                        </span>
                    @endif
                </td>

                <td class="px-4 py-3 text-center flex justify-center gap-2">

                                {{-- Edit Button --}}
                                <a href="{{ route('peminjam-users.edit', $user->id) }}"
                                   class="px-3 py-1 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    Edit
                                </a>

                                {{-- Delete Button --}}
                                <button
                                    x-data
                                    @click="$dispatch('open-modal', { id: 'delete-{{ $user->id }}' })"
                                    class="px-3 py-1 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        {{-- Delete Modal --}}
                        <div x-data="{ open: false }"
                             x-on:open-modal.window="if($event.detail.id === 'delete-{{ $user->id }}') open = true">
                            <div x-show="open"
                                 class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
                                 x-transition.opacity>
                                <div class="bg-white p-6 rounded-2xl shadow-xl max-w-sm w-full"
                                     x-transition.scale>
                                    <h2 class="text-lg font-semibold text-gray-800 mb-3">
                                        Hapus Peminjam?
                                    </h2>
                                    <p class="text-gray-600 text-sm mb-5">
                                        Data ini akan terhapus secara permanen.
                                    </p>

                                    <div class="flex justify-end gap-3">
                                        <button @click="open = false"
                                                class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300 transition">
                                            Batal
                                        </button>

                                        <form method="POST"
                                              action="{{ route('peminjam-users.destroy', $user->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data peminjam.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>
