<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Ruangan
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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- ROOM INFO --}}
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-800">{{ $room->name }}</h3>
                <p class="text-gray-600 mt-1">{{ $room->description ?? '-' }}</p>

                <div class="mt-3">
                    @php
                        $colors = [
                            'sedia' => 'green',
                            'penuh' => 'red',
                            'maintenance' => 'yellow',
                        ];
                        $color = $colors[$room->status] ?? 'gray';
                    @endphp

                    <span class="px-3 py-1 bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-300 rounded text-sm">
                        Status: {{ ucfirst($room->status) }}
                    </span>
                </div>
            </div>

            {{-- ITEM LIST --}}
            <div class="bg-white shadow-lg rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">Barang di Ruangan Ini</h3>

                @if ($room->items->isEmpty())
                    <p class="text-gray-600">Tidak ada barang di ruangan ini.</p>
                @else
                    <div class="space-y-3">

                        @foreach ($room->items as $item)
                            <div class="p-4 border rounded-lg flex justify-between items-center hover:bg-gray-50">

                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $item->name }}</h4>
                                    <p class="text-gray-600 text-sm">
                                        No Asset: {{ $item->asset_number ?? '-' }}
                                    </p>
                                    <p class="text-gray-600 text-sm">
                                        Qty: {{ $item->quantity }}
                                    </p>
                                </div>

                                <button 
                                    @click="openMoveModal({{ $item->id }}, '{{ $item->name }}')"
                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Pindahkan
                                </button>

                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>

        {{-- MOVE MODAL --}}
        <div x-show="showMoveModal" x-cloak
            class="fixed inset-0 bg-black/40 flex items-center justify-center">

            <div class="bg-white w-96 rounded-lg shadow-lg p-6">

                <h2 class="text-lg font-bold text-gray-800">Pindahkan Barang</h2>
                <p class="mt-1 text-gray-700">
                    Pindahkan <b x-text="moveItemName"></b> ke ruangan lain.
                </p>

                <form action="{{ route('rooms.moveItem') }}" method="POST" class="mt-4">
                    @csrf

                    <input type="hidden" name="item_id" :value="moveItemId">

                    <label class="block mb-1 font-medium">Pilih Ruangan Baru</label>
                    <select name="new_room_id" class="w-full border-gray-300 rounded-lg">
                        @foreach ($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex justify-end gap-2 mt-5">
                        <button type="button"
                            @click="showMoveModal = false"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Batal
                        </button>

                        <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Pindahkan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
        function roomPage() {
            return {
                showMoveModal: false,
                moveItemId: null,
                moveItemName: '',

                openMoveModal(id, name) {
                    this.moveItemId = id;
                    this.moveItemName = name;
                    this.showMoveModal = true;
                }
            }
        }
    </script>

</x-app-layout>
