<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Printer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            {{-- HEADER CARD --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl overflow-hidden mb-6">
                <div class="p-8 flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-white shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                        <div class="text-white">
                            <h3 class="text-3xl font-bold">{{ $printer->name }}</h3>
                            <p class="text-blue-100 text-lg uppercase tracking-widest mt-1">{{ $printer->category }} PRINTER</p>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        @php
                            $statusBg = match($printer->status) {
                                'available' => 'bg-green-500',
                                'in_use' => 'bg-orange-500',
                                'maintenance' => 'bg-red-600',
                                default => 'bg-gray-500'
                            };
                            $statusText = match($printer->status) {
                                'in_use' => 'Sedang Dipakai',
                                'available' => 'Tersedia',
                                'maintenance' => 'Maintenance',
                                default => $printer->status
                            };
                        @endphp
                        <span class="px-6 py-2 {{ $statusBg }} text-white font-bold rounded-full shadow-lg text-sm uppercase tracking-wide">
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- MAIN INFO (LEFT) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- DETAIL SPEK --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Spesifikasi & Info</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Nama Mesin</p>
                                <p class="text-gray-900 font-semibold">{{ $printer->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Tipe Teknologi</p>
                                <p class="text-gray-900 font-semibold">{{ $printer->category }} ({{ $printer->category == 'FDM' ? 'Filament' : 'Resin' }})</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Lokasi</p>
                                <p class="text-gray-900 font-semibold">{{ $printer->location ?? 'Laboratorium Print 3D' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Terakhir Dipakai</p>
                                <p class="text-gray-900 font-semibold">{{ $printer->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- MATERIAL COMPATIBILITY --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Material Kompatibel
                        </h4>
                        @if($printer->materialTypes->count())
                            <div class="flex flex-wrap gap-2">
                                @foreach($printer->materialTypes as $mt)
                                    <div class="flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></div>
                                        <span class="font-bold text-sm">{{ $mt->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 italic text-sm">Belum ada material yang dihubungkan ke printer ini.</p>
                        @endif
                    </div>

                </div>

                {{-- SIDEBAR ACTION (RIGHT) --}}
                <div class="space-y-6">
                    
                    {{-- STATUS CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2">Status Saat Ini</p>
                        <div class="text-4xl font-bold {{ $printer->status == 'available' ? 'text-green-600' : ($printer->status == 'maintenance' ? 'text-red-600' : 'text-orange-500') }}">
                            @if($printer->status == 'available')
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                READY
                            @elseif($printer->status == 'maintenance')
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                ERROR
                            @else
                                <svg class="w-16 h-16 mx-auto mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                BUSY
                            @endif
                        </div>
                        @if($printer->status == 'in_use')
                            <p class="text-sm text-gray-500 mt-2">Estimasi selesai: <br> <span class="font-bold text-gray-800">{{ $printer->available_at_formatted ?? '-' }}</span></p>
                        @endif
                    </div>

                    {{-- ACTIONS --}}
                    <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                        <a href="{{ route('printers.edit', $printer->id) }}" class="block w-full py-3 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold rounded-xl text-center shadow-sm transition mb-3">
                            Edit Printer
                        </a>
                        <form action="{{ route('printers.destroy', $printer->id) }}" method="POST" onsubmit="return confirm('Hapus permanen?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="block w-full py-3 bg-white border-2 border-red-100 text-red-600 font-bold rounded-xl text-center hover:bg-red-50 transition">
                                Hapus Printer
                            </button>
                        </form>
                    </div>

                    <a href="{{ route('printers.index') }}" class="block text-center text-gray-500 hover:text-gray-800 font-medium text-sm">
                        &larr; Kembali ke Daftar
                    </a>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>