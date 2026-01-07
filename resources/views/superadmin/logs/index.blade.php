<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Log Sistem') }}
        </h2>
    </x-slot>

    {{-- Alert Success/Error (Sama seperti kodemu) --}}
    @if (session('success')) <div class="fixed top-4 right-4 z-50 p-4 bg-emerald-500 text-white rounded-xl shadow-lg">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="fixed top-20 right-4 z-50 p-4 bg-red-500 text-white rounded-xl shadow-lg">{{ session('error') }}</div> @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                
                {{-- 1. FILTER & SEARCH BAR --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <form method="GET" action="{{ route('superadmin.logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        
                        {{-- Search Input --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-bold text-gray-500 uppercase">Cari Data</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketikan deskripsi, ID, atau nama model..." 
                                   class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        </div>

                        {{-- Filter Action --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tipe Aksi</label>
                            <select name="action" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Semua Aksi</option>
                                @foreach($actions as $act)
                                    <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ strtoupper($act) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter User --}}
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">User</label>
                            <select name="user_id" class="w-full mt-1 border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Filter --}}
                        <div class="flex gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-bold shadow transition w-full">
                                Filter
                            </button>
                            <a href="{{ route('superadmin.logs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 rounded-md text-sm font-bold transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Header Actions (Clear All) --}}
                @if(auth()->user()->role === 'superadmin')
                <div class="px-6 py-2 bg-red-50 border-b border-red-100 flex justify-end">
                    <form action="{{ route('superadmin.logs.clear') }}" method="POST" onsubmit="return confirm('Yakin hapus SEMUA log?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline font-bold">Bersihkan Semua Log</button>
                    </form>
                </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                {{-- 2. SORTABLE HEADERS --}}
                                <th class="px-6 py-3 text-left">
                                    <a href="{{ route('superadmin.logs.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-300">
                                        Waktu
                                        <i class="fas fa-sort text-xs"></i>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left">User</th>
                                <th class="px-6 py-3 text-left">
                                    <a href="{{ route('superadmin.logs.index', array_merge(request()->query(), ['sort' => 'action', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-300">
                                        Aksi
                                        <i class="fas fa-sort text-xs"></i>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <a href="{{ route('superadmin.logs.index', array_merge(request()->query(), ['sort' => 'model', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center gap-1 hover:text-gray-300">
                                        Target Data
                                        <i class="fas fa-sort text-xs"></i>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-left">Detail Perubahan</th>
                                @if(auth()->user()->role === 'superadmin')
                                    <th class="px-6 py-3 text-right">Opsi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($logs as $log)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                        <div class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-700 whitespace-nowrap">
                                        {{ $log->user->name ?? 'System' }}
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
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <span class="font-semibold text-gray-800">{{ class_basename($log->model) }}</span>
                                                <span class="text-gray-500 text-xs">#{{ $log->model_id }}</span>
                                            </div>
                                            {{-- 3. TOMBOL HISTORY SPESIFIK --}}
                                            @if($log->model && $log->model_id)
                                                <a href="{{ route('superadmin.logs.history', ['model' => $log->model, 'id' => $log->model_id]) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-1.5 rounded-full" 
                                                   title="Lihat Timeline Data Ini">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 max-w-xs break-words text-xs font-mono">
                                        {{ Str::limit($log->description, 50) }}
                                    </td>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada log aktivitas yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $logs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>