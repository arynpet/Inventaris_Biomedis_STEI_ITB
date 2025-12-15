<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peminjaman Ruangan
        </h2>
    </x-slot>

    {{-- SUCCESS NOTIFICATION --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 2500)"
             class="mx-4 my-4 p-4 bg-green-500 text-white rounded shadow">
            {{ session('success') }}
        </div>
    @endif


    <div class="py-6" x-data="borrowRoomPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Peminjaman Ruangan</h3>

                <a href="{{ route('room_borrowings.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                    + Tambah Peminjaman
                </a>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">Ruangan</th>
                            <th class="px-4 py-3 text-left">Peminjam</th>
                            <th class="px-4 py-3 text-left">Mulai</th>
                            <th class="px-4 py-3 text-left">Selesai</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($borrowings as $b)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3">{{ $b->room->name }}</td>
                                <td class="px-4 py-3">{{ $b->user->name }}</td>
                                <td class="px-4 py-3">{{ $b->start_time }}</td>
                                <td class="px-4 py-3">{{ $b->end_time }}</td>

                                @php
                                    $statusColor = [
                                        'pending' => 'yellow',
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                        'finished' => 'blue'
                                    ];
                                    $color = $statusColor[$b->status] ?? 'gray';
                                @endphp

                                <td class="px-4 py-3">
                                    <span class="
                                        px-2 py-1 rounded text-xs
                                        bg-{{ $color }}-100
                                        text-{{ $color }}-700
                                        border border-{{ $color }}-300
                                    ">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 space-x-2">
                                    <a href="{{ route('room_borrowings.show', $b->id) }}"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Detail
                                    </a>

                                    <a href="{{ route('room_borrowings.edit', $b->id) }}"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <button @click="confirmDelete({{ $b->id }}, '{{ $b->room->name }}')"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    Belum ada data peminjaman ruangan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $borrowings->links() }}
                </div>
            </div>

        </div>

        {{-- DELETE MODAL --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-800">Hapus Data</h2>
                <p class="mt-2 text-gray-700">
                    Yakin ingin menghapus peminjaman <b x-text="deleteName"></b>?
                </p>

                <div class="flex justify-end gap-2 mt-5">
                    <button class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                        @click="showModal = false">
                        Batal
                    </button>

                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function borrowRoomPage() {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',

                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/room_borrowings/${id}`;
                }
            }
        }
    </script>

</x-app-layout>
