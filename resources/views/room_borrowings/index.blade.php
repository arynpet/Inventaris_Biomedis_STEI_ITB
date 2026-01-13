<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman Ruangan') }}
        </h2>
    </x-slot>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-4 right-4 z-50 p-4 bg-emerald-500 text-white rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed top-20 right-4 z-50 p-4 bg-red-500 text-white rounded-xl shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="py-12" x-data="borrowRoomPage({{ json_encode($borrowings->pluck('id')) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Jadwal Ruangan</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar penggunaan dan reservasi ruangan aktif.</p>
                </div>
                
                <div class="flex gap-3">
                    {{-- TOMBOL HISTORY (BARU) --}}
                    <a href="{{ route('room_borrowings.history') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>

                    <a href="{{ route('room_borrowings.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Booking Ruangan
                    </a>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('room_borrowings.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm" placeholder="Cari Ruangan atau Peminjam...">
                    </div>
                    <div class="w-full md:w-auto">
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer h-[42px]">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition h-[42px]">Filter</button>
                </form>
            </div>

            {{-- TABLE --}}
            <form id="bulkActionForm" action="{{ route('room_borrowings.bulk_action') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="bulkActionType">

                <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-gray-100">
                            <thead class="bg-gray-50 text-gray-700">
                                <tr>
                                    <th class="px-4 py-4 w-10 text-center">
                                        <input type="checkbox" @click="toggleAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                    </th>
                                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Ruangan</th>
                                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Peminjam</th>
                                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Waktu</th>
                                    <th class="px-6 py-4 text-center font-bold uppercase tracking-wider text-xs">Surat</th>
                                    <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Status</th>
                                    <th class="px-6 py-4 text-right font-bold uppercase tracking-wider text-xs">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($borrowings as $b)
                                    <tr class="hover:bg-blue-50/50 transition" :class="{'bg-blue-50': selectedItems.includes({{ $b->id }})}">
                                        <td class="px-4 py-4 text-center">
                                            <input type="checkbox" name="selected_ids[]" value="{{ $b->id }}" 
                                                   @click="toggleItem({{ $b->id }}, {{ $loop->index }}, $event)"
                                                   :checked="selectedItems.includes({{ $b->id }})"
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4 cursor-pointer">
                                        </td>
                                        {{-- RUANGAN --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-gray-800">{{ $b->room->name }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $b->room->code }}</div>
                                        </td>

                                        {{-- PEMINJAM --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <div class="h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                                    {{ substr($b->user->name, 0, 1) }}
                                                </div>
                                                <span class="text-gray-700">{{ $b->user->name }}</span>
                                            </div>
                                        </td>

                                        {{-- WAKTU --}}
                                        <td class="px-6 py-4 text-gray-600 text-xs">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($b->start_time)->format('d M Y') }}</span>
                                                <span>{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}</span>
                                            </div>
                                        </td>

                                        {{-- SURAT --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($b->surat_peminjaman)
                                                <a href="{{ Storage::url($b->surat_peminjaman) }}" target="_blank" class="inline-flex items-center justify-center h-8 w-8 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Lihat Surat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                </a>
                                            @else
                                                <span class="text-gray-300 text-xs italic">-</span>
                                            @endif
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $color = match($b->status) {
                                                    'approved' => 'green',
                                                    'pending'  => 'yellow',
                                                    default    => 'gray'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200 capitalize">
                                                {{ $b->status }}
                                            </span>
                                        </td>

                                        {{-- AKSI --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                
                                                {{-- TOMBOL SETUJUI (Hanya muncul jika Pending) --}}
                                                @if($b->status === 'pending')
                                                    <form action="{{ route('room_borrowings.approve', $b->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition shadow-sm" title="Setujui">
                                                            Setujui
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- TOMBOL SELESAI (Hanya muncul jika Approved) --}}
                                                @if($b->status === 'approved')
                                                    <form action="{{ route('room_borrowings.return', $b->id) }}" method="POST" onsubmit="return confirm('Tandai peminjaman ini sebagai selesai?');">
                                                        @csrf @method('PUT')
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-emerald-600 transition shadow-sm" title="Selesai">
                                                            Selesai
                                                        </button>
                                                    </form>
                                                @endif

                                                <a href="{{ route('room_borrowings.edit', $b->id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                                <button type="button" @click="confirmDelete({{ $b->id }}, '{{ $b->room->name }}')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="font-medium">Tidak ada jadwal aktif.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="w-full">
                            @if($borrowings instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $borrowings->links() }}
                            @else
                                <div class="text-sm text-gray-500">Menampilkan semua {{ $borrowings->count() }} data.</div>
                            @endif
                        </div>
                        
                        <div class="whitespace-nowrap">
                            @if(request('show_all'))
                                <a href="{{ request()->fullUrlWithQuery(['show_all' => null]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Batasi Per Halaman
                                </a>
                            @else
                                <a href="{{ request()->fullUrlWithQuery(['show_all' => 1]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="mr-2 -ml-1 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    Tampilkan Semua
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- FLOATING BULK ACTION --}}
        <div x-show="selectedItems.length > 0" x-transition class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-white px-6 py-4 rounded-2xl shadow-2xl border border-gray-200 z-40 flex items-center gap-6">
            <div class="flex items-center gap-2 text-gray-700 font-medium"><span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-bold" x-text="selectedItems.length"></span><span>Dipilih</span></div>
            <button @click="submitBulkAction('delete')" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl transition text-sm font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Hapus Terpilih
            </button>
        </div>

        {{-- DELETE MODAL --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
            <div @click.away="showModal = false" class="bg-white w-96 rounded-xl shadow-2xl p-6 transform transition-all">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Hapus Jadwal?</h2>
                <p class="text-gray-500 text-sm mb-6">Yakin ingin menghapus booking untuk <b class="text-gray-800" x-text="deleteName"></b>?</p>
                <div class="flex justify-end gap-3">
                    <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition" @click="showModal = false">Batal</button>
                    <form :action="deleteUrl" method="POST">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold shadow transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function borrowRoomPage(pageIds = []) {
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
                    if(confirm('Yakin ingin menghapus ' + this.selectedItems.length + ' peminjaman ruangan?')) {
                        document.getElementById('bulkActionType').value = type;
                        document.getElementById('bulkActionForm').submit();
                    }
                },
                confirmDelete(id, name) {
                    this.showModal = true;
                    this.deleteName = name;
                    this.deleteUrl = `/room_borrowings/${id}`;
                }
            }
        }
    </script>
</x-app-layout>