<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-red-600 leading-tight flex items-center gap-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                </path>
            </svg>
            {{ __('Developer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-red-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-red-100">
                <div class="p-8">

                    {{-- HEADER WARNING --}}
                    <div class="flex items-start gap-4 mb-8 bg-red-50 p-4 rounded-xl border border-red-200">
                        <div class="p-2 bg-red-100 text-red-600 rounded-lg flex-shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-800">Danger Zone</h3>
                            <p class="text-red-700 mt-1">
                                Halaman ini berisi alat-alat berbahaya untuk keperluan development.
                                Fitur di sini bisa menghapus data secara permanen. Gunakan dengan sangat hati-hati!
                            </p>
                        </div>
                    </div>

                    {{-- TOOLS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- 1. NUKE DATABASE --}}
                        <div
                            class="border border-red-200 rounded-xl p-6 hover:shadow-lg transition bg-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-32 h-32 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                </svg>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900 mb-2">Reset Database (Fresh Seed)</h4>
                            <p class="text-gray-500 text-sm mb-6">
                                Menjalankan `migrate:fresh --seed`. Semua data akan dihapus dan diganti dengan data
                                dummy awal (Seeder).
                            </p>

                            <form action="{{ route('dev.reset_db') }}" method="POST"
                                onsubmit="return confirm('⚠️⚠️⚠️ PERINGATAN KERAS! ⚠️⚠️⚠️\n\nApakah Anda YAKIN ingin MENGHAPUS SEMUA DATA?\n\nTindakan ini:\n1. Menghapus semua tabel\n2. Menjalankan migrasi ulang\n3. Mengisi data dummy (Seed)\n\nData yang hilang TIDAK BISA DIKEMBALIKAN!');">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Nuke Database ☢️
                                </button>
                            </form>
                        </div>

                        {{-- 2. USER IMPERSONATION --}}
                        <div class="border border-gray-200 rounded-xl bg-white shadow-sm md:col-span-2">
                            <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    User Impersonation (Login Sebagai)
                                </h3>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full font-bold">Total:
                                    {{ $users->count() }} Users</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm divide-y divide-gray-100">
                                    <thead class="bg-gray-50 text-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left">ID</th>
                                            <th class="px-4 py-3 text-left">Nama</th>
                                            <th class="px-4 py-3 text-left">Email</th>
                                            <th class="px-4 py-3 text-left">Role</th>
                                            <th class="px-4 py-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($users as $u)
                                                                            <tr class="hover:bg-gray-50">
                                                                                <td class="px-4 py-3 text-gray-500">#{{ $u->id }}</td>
                                                                                <td class="px-4 py-3 font-medium text-gray-900">{{ $u->name }}</td>
                                                                                <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                                                                                <td class="px-4 py-3">
                                                                                    <span
                                                                                        class="px-2 py-1 text-xs rounded-full 
                                                                                            {{ $u->role === 'dev' ? 'bg-purple-100 text-purple-700' :
                                            ($u->role === 'superadmin' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                                                                        {{ ucfirst($u->role) }}
                                                                                    </span>
                                                                                </td>
                                                                                <td class="px-4 py-3 text-right">
                                                                                    @if($u->id !== auth()->id())
                                                                                        <form action="{{ route('dev.impersonate', $u->id) }}" method="POST">
                                                                                            @csrf
                                                                                            <button type="submit"
                                                                                                class="text-xs bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 transition shadow-sm font-bold flex items-center gap-1 justify-end ml-auto">
                                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                                                    viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                                                                                    </path>
                                                                                                </svg>
                                                                                                Login As
                                                                                            </button>
                                                                                        </form>
                                                                                    @else
                                                                                        <span class="text-xs text-gray-400 italic">Current User</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>