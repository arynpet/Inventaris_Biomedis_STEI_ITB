<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ruangan
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 2500)"
             class="mx-4 my-4 p-4 bg-green-500 text-white rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="py-6" x-data="roomPage()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Ruangan</h3>

                <a href="{{ route('rooms.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                    + Tambah Ruangan
                </a>
            </div>

            {{-- TABLE WRAPPER --}}
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 text-gray-700 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left">Kode</th>
                                <th class="px-4 py-3 text-left">Nama Ruangan</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($rooms as $room)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-4 whitespace-normal break-words">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 font-bold text-xs">
                                        {{ $room->code }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 whitespace-normal break-words">
                                            <span class="inline-flex items-center gap-1.5 text-gray-700">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $room->name }}
                                            </span>
                                        </td>

                                    <td class="px-4 py-3 max-w-sm">
                                        <span class="line-clamp-2 text-gray-700">
                                            {{ $room->description ?? '-' }}
                                        </span>
                                    </td>

                                    {{-- STATUS BADGE --}}
                                   @php
                                            $statusStyles = [
                                                'sedia' => 'bg-gradient-to-r from-green-100 to-green-200 text-green-700 border-green-300',
                                                'dipinjam' => 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-700 border-yellow-300',
                                                'maintenance' => 'bg-gradient-to-r from-red-100 to-red-200 text-red-700 border-red-300',
                                            ];
                                        @endphp 

                                    <td class="px-4 py-4">
                                        <x-status-badge :status="$room->status" />
                                    </td>

                                    {{-- ACTION BUTTONS --}}
                                    <td class="px-4 py-3 space-x-2">

                                        {{-- TOMBOL DETAIL --}}
                                        <a href="{{ route('rooms.show', $room->id) }}"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Detail
                                        </a>

                                        {{-- TOMBOL EDIT --}}
                                        <a href="{{ route('rooms.edit', $room->id) }}"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Edit
                                        </a>

                                        {{-- TOMBOL DELETE --}}
                                        <button @click="confirmDelete({{ $room->id }}, '{{ $room->name }}')"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                            Delete
                                        </button>

                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">
                                        Belum ada data ruangan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION (optional, jika pakai paginate) --}}
                {{-- <div class="p-4">
                    {{ $rooms->links() }}
                </div> --}}
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="bg-white w-96 rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-800">Hapus Ruangan</h2>
                <p class="mt-2 text-gray-700">
                    Yakin ingin menghapus <b x-text="deleteName"></b>?
                </p>

                <div class="flex justify-end gap-2 mt-5">
                    <button 
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
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

    {{-- ALPINE SCRIPT --}}
    <script>
        function roomPage() {
            return {
                showModal: false,
                deleteUrl: '',
                deleteName: '',

                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/rooms/${id}`;
                }
            }
        }
    </script>

</x-app-layout>
