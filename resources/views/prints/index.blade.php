<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peminjaman 3D Print') }}
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 tracking-tight">Antrian Print 3D</h3>
                    <p class="text-sm text-gray-500 mt-1">Daftar antrian cetak yang sedang berjalan.</p>
                </div>
                
                <div class="flex gap-3">
                    <a href="{{ route('prints.history') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>
                    <a href="{{ route('prints.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Request Print
                    </a>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('prints.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="w-full md:flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm placeholder-gray-400 shadow-sm" placeholder="Cari User, File, atau Mesin...">
                    </div>
                    <div class="w-full md:w-auto">
                        <select name="status" class="w-full rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 cursor-pointer h-[42px]">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="printing" {{ request('status') == 'printing' ? 'selected' : '' }}>Printing</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition h-[42px]">Filter</button>
                </form>
            </div>

            {{-- TABLE --}}
            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">User</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Project / File</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Mesin</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Material</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Waktu</th>
                                <th class="px-6 py-4 text-left font-bold uppercase tracking-wider text-xs">Status</th>
                                <th class="px-6 py-4 text-right font-bold uppercase tracking-wider text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($prints as $print)
                                <tr class="hover:bg-blue-50/50 transition">
                                    {{-- USER --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                                {{ substr($print->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <span class="text-gray-700 font-medium">{{ $print->user->name ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- PROJECT / FILE (BARU) --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $print->project_name }}</div>

                                    </td>

                                    {{-- MESIN --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $print->printer->name ?? '-' }}
                                    </td>

                                    {{-- MATERIAL --}}
                                    <td class="px-6 py-4">
                                        <div class="text-gray-800">{{ $print->materialType->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $print->material_amount ?? 0 }} {{ $print->material_unit }}
                                            <span class="bg-gray-100 px-1 rounded text-[10px] uppercase ml-1">{{ $print->material_source }}</span>
                                        </div>
                                    </td>

                                    {{-- WAKTU --}}
                                    <td class="px-6 py-4 text-gray-600 text-xs">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($print->date)->format('d M Y') }}</span>
                                            <span>{{ \Carbon\Carbon::parse($print->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($print->end_time)->format('H:i') }}</span>
                                        </div>
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $color = match($print->status) {
                                                'printing' => 'blue',
                                                'pending'  => 'yellow',
                                                default    => 'gray'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200 capitalize animate-pulse">
                                            {{ $print->status }}
                                        </span>
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            
                                            {{-- TOMBOL MULAI PRINT (Hanya Pending) --}}
                                            @if($print->status === 'pending')
                                                <form action="{{ route('prints.update', $print->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="printing">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-blue-700 transition shadow-sm" title="Mulai Print">
                                                        Mulai
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- TOMBOL SELESAI (Hanya Printing) --}}
                                            @if($print->status === 'printing')
                                                <form action="{{ route('prints.update', $print->id) }}" method="POST" onsubmit="return confirm('Tandai print ini selesai?');">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="done">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-emerald-600 transition shadow-sm" title="Selesai">
                                                        Selesai
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- TOMBOL BATAL --}}
                                            <form action="{{ route('prints.update', $print->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan? Material akan dikembalikan.');">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Batalkan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </form>

                                            {{-- Detail --}}
                                            <a href="{{ route('prints.show', $print->id) }}" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            <p class="font-medium">Tidak ada antrian print aktif.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $prints->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>