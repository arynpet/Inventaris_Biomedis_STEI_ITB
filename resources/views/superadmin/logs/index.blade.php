<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log Sistem') }}
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
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                
                {{-- HEADER WITH ACTIONS --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Riwayat Aktivitas User</h3>
                    
                    {{-- TOMBOL CLEAR ALL (HANYA SUPERADMIN) --}}
                    @if(auth()->user()->role === 'superadmin')
                        <form action="{{ route('superadmin.logs.clear') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus SELURUH riwayat log? Tindakan ini tidak bisa dibatalkan.');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 hover:underline flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Bersihkan Semua Log
                            </button>
                        </form>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">Waktu</th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">Aksi</th>
                                <th class="px-6 py-3 text-left">Target Data</th>
                                <th class="px-6 py-3 text-left">Detail Perubahan</th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th class="px-6 py-3 text-right">Hapus</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                        <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-700 whitespace-nowrap">
                                        {{ $log->user->name ?? 'System/Deleted' }}
                                        <div class="text-xs font-normal text-gray-400">{{ $log->user->role ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $color = match($log->action) {
                                                'create' => 'green',
                                                'update' => 'blue',
                                                'delete' => 'red',
                                                default  => 'gray'
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700 uppercase">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-semibold text-gray-800">{{ $log->model }}</span>
                                        <span class="text-gray-500 text-xs">#{{ $log->model_id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs break-words">
                                        {{ $log->description }}
                                    </td>
                                    
                                    {{-- TOMBOL HAPUS ITEM (HANYA SUPERADMIN) --}}
                                    @if(auth()->user()->role === 'superadmin')
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('superadmin.logs.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>